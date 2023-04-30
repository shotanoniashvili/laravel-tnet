<?php

namespace App\Helpers;

// ამოცანა 1
class ShortestWord
{
    public static function run(string $sentence): int
    {
        $exploded = explode(' ', $sentence);
        $shortestWord = $exploded[0];

        for ($i = 1, $iMax = count($exploded); $i < $iMax; $i++) {
            if (mb_strlen($shortestWord) > mb_strlen($exploded[$i])) {
                $shortestWord = $exploded[$i];
            }
        }

        return mb_strlen($shortestWord);
    }
}
