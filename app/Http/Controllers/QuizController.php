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

        if (!$request->session()->has('student')) {
            $student = $loginController->validateLogin($request);

            if (!$student) {
                return redirect('/')
                    ->with('alert', 'Invalid Student ID. Please try again.');
            }

            $loginController->setStudentSession($student);
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

            // save to high scores
            $studentId = $request->session()->get('student.id');
            $highScore = new \App\Models\HighScore();
            $highScore->student_id = $studentId;
            $highScore->word = $word;
            $highScore->score = strlen($word);
            $highScore->save();
            
            // Save word + update used letters
            $usedIndexes = array_merge($usedIndexes, $wordIndexes);
            $words[] = ['word' => $word, 'score' => strlen($word)];
    
            $request->session()->put('usedIndexes', $usedIndexes);
            $request->session()->put('words', $words);
        } else {
            $found = false;
        }


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
        $words = $request->session()->get('words', []);
        $totalScore = array_sum(array_column($words, 'score'));

        return view('quiz-finish', [
            'totalScore' => $totalScore,
            'words' => $words
        ]);
    }

    private function generatePuzzleString(int $length = 14): string
    {
        $wordList = new WordListService();

        $words = $wordList->getRandomWords(4, $length);

        $letters = str_split(strtolower(implode('', $words)));
        shuffle($letters);
        
        return implode('', $letters);
    }


    private function isValidEnglishWord(string $word): bool
    {
        $wordList = new WordListService();
        return $wordList->wordExists($word);
    }

}

