<?php

namespace App\Services;

class WordListService
{
    protected array $words;

    public function __construct(string $filePath = null)
    {
        $this->filePath = $filePath ?? storage_path('app/words.txt');

        if (!file_exists($this->filePath)) {
            throw new \Exception("Word list file not found: {$this->filePath}");
        }

        $this->words = file($this->filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    }

    public function getAllWords(): array
    {
        return $this->words;
    }

    public function getWords(int $length = 6): array
    {
        return array_filter($this->words, fn($word) => strlen($word) == $length);
    }

    public function getRandomWords(int $count = 4, int $length = 6): array
    {
        $filtered = $this->getWords($length);
        shuffle($filtered);
        return array_slice($filtered, 0, $count);
    }

    public function wordExists(string $word): bool
    {
        return in_array(strtolower($word), $this->words, true);
    }
}
