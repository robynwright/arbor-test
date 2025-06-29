<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\StudentLoginController;
use App\Services\WordListService;


class QuizController extends Controller
{
    public function index(Request $request)
    {
        $studentId = $request->input('student_id');

        $loginController = new StudentLoginController();

        // Check if student is already logged in
        if ($loginController->checkLoggedIn()) {
            $student = $loginController->getStudentSession();
        } else {
            if (!$request->session()->has('student')) {
                $student = $loginController->validateLogin($request);
    
                if (!$student) {
                    return redirect('/')
                        ->with('alert', 'Invalid Student ID. Please try again.');
                }
    
                $loginController->setStudentSession($student);
            }
        }

        // Generate puzzle and reset session data
        $puzzleString = $this->generatePuzzleString(14);
        
        $request->session()->put('puzzleString', $puzzleString);
        $request->session()->put('usedIndexes', []);
        $request->session()->put('words', []);

        return view('quiz', [
            'puzzleString' => $puzzleString,
            'usedIndexes' => [],
            'words' => [],
        ]);
    }

    public function play(Request $request)
    {
        if (!$request->session()->has('student')) {
            return redirect('/')
                ->with('alert', 'Please log in to finish the quiz.');
        }

        $word = strtolower($request->input('word'));
        $puzzleString = $request->session()->get('puzzleString');
        $usedIndexes = $request->session()->get('usedIndexes', []);
        $words = $request->session()->get('words', []);

        // Check letters available
        $puzzleArray = str_split($puzzleString);
        $available = [];
        foreach ($puzzleArray as $i => $char) {
            if (!in_array($i, $usedIndexes)) {
                $available[$i] = $char;
            }
        }

        $wordIndexes = [];
        $letters = str_split($word);
        foreach ($letters as $letter) {
            $found = false;
            foreach ($available as $i => $char) {
                if ($char === $letter) {
                    $wordIndexes[] = $i;
                    unset($available[$i]);
                    $found = true;
                    break;
                }
            }
        }

        if ($this->isValidEnglishWord($word) && $found) {

            // TODO: move to own method
            $studentId = $request->session()->get('student.id');
            $puzzleId = $request->session()->get('puzzleId');
            $score = strlen($word);
            
            $submission = new \App\Models\PuzzleSubmission();
            $submission->student_id = $studentId;
            $submission->puzzle_id = $puzzleId;
            $submission->submission_string = $word;
            $submission->score = $score;
            $submission->save();

            $puzzle = \App\Models\Puzzle::find($puzzleId);
            $puzzle->total_score += $score;
            $puzzle->save();
            
            // Save word + update used letters
            $usedIndexes = array_merge($usedIndexes, $wordIndexes);
            $words[] = ['word' => $word, 'score' => $score];
    
            $request->session()->put('usedIndexes', $usedIndexes);
            $request->session()->put('words', $words);
        } else {
            $found = false;
        }

        // if there are no more available letters, end the game
        // if (empty($available)) {
        //     return redirect()->route('quiz.finish');
        // }


        return view('quiz', [
            'studentId' => $request->session()->get('student.id'),
            'puzzleString' => $puzzleString,
            'usedIndexes' => $usedIndexes,
            'words' => $words,
            'alert' => isset($found) && $found ? null : 'Word not found or invalid.',
        ]);
    }

    public function finish(Request $request)
    {
        if (!$request->session()->has('student')) {
            return redirect('/')
                ->with('alert', 'Please log in to finish the quiz.');
        }

        $puzzleString = $request->session()->get('puzzleString');
        $usedIndexes = $request->session()->get('usedIndexes', []);
        $words = $request->session()->get('words', []);
        $totalScore = array_sum(array_column($words, 'score'));

        $remainingLetters = [];
        $puzzleArray = str_split($puzzleString);

        foreach ($puzzleArray as $i => $char) {
            if (!in_array($i, $usedIndexes)) {
                $remainingLetters[] = $char;
            }
        }

        $remainingWords = $this->getRemainingValidWords($remainingLetters);

        return view('quiz_finish', [
            'totalScore' => $totalScore,
            'puzzleString' => $puzzleString,
            'usedIndexes' => $usedIndexes,
            'words' => $words,
            'remainingLetters' => $remainingLetters,
            'remainingWords' => $remainingWords,
        ]);
    }

    private function getRemainingValidWords(array $letters): array
    {
        $wordList = new WordListService();
        $validWords = [];

        foreach ($wordList->getAllWords() as $word) {
            $tempLetters = $letters;
            $wordChars = str_split($word);
            $canForm = true;

            foreach ($wordChars as $char) {
                $index = array_search($char, $tempLetters);
                if ($index === false) {
                    $canForm = false;
                    break;
                }
                unset($tempLetters[$index]); // remove the letter
            }

            if ($canForm) {
                $validWords[] = [
                    'word' => $word,
                    'score' => strlen($word),
                ];
            }
        }

        return $validWords;
    }



    private function generatePuzzleString(int $length = 14): string
    {
        $wordList = new WordListService();

        $words = $wordList->getRandomWords(4, $length);

        $letters = str_split(strtolower(implode('', $words)));
        shuffle($letters);

        $puzzleString = implode('', $letters);
        
        // Save the puzzle to the database 
        // I would rather move this to its own class, but am keeping it here due to time constraints.
        $puzzle = new \App\Models\Puzzle();
        $puzzle->student_id = session('student.id');
        $puzzle->puzzle_string = $puzzleString;
        $puzzle->save();

        // I need the puzzle id to save the submission later, so lets save it to the session
        $puzzleId = $puzzle->id;
        session(['puzzleId' => $puzzleId]);

        return $puzzleString;
    }


    private function isValidEnglishWord(string $word): bool
    {
        $wordList = new WordListService();
        return $wordList->wordExists($word);
    }

}

