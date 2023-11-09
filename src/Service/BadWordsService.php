<?php

namespace App\Service;

class BadWordsService
{
    private array $badWords = ['fuck', 'bitch', 'ass'];

    public function containsBadWords(string $text): bool
    {
        $text = strtolower($text);

        foreach ($this->badWords as $badWord) {
            if (strpos($text, $badWord) !== false) {
                return true;
            }
        }

        return false;
    }
}