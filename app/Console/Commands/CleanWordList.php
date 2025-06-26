<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CleanWordList extends Command
{
    protected $signature = 'clean:wordlist';
    protected $description = 'Cleans the word list and overwrites the file';

    public function handle()
    {
        $filePath = storage_path('app/words.txt');

        if (!file_exists($filePath)) {
            $this->error("File not found: $filePath");
            return 1;
        }

        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $cleaned = [];

        foreach ($lines as $line) {
            $word = strtolower(trim($line));
            if (
                preg_match('/^[a-z]+$/', $word) &&          // letters only
                strlen($word) > 1 &&                       // longer than 1 char
                !preg_match('/^([a-z])\1+$/', $word)       // not all same letter
            ) {
                $cleaned[] = $word;
            }
        }

        file_put_contents($filePath, implode(PHP_EOL, $cleaned) . PHP_EOL);

        $this->info('Word list cleaned and file overwritten.');

        return 0;
    }
}
