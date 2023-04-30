<?php

namespace App\Helpers;

// ამოცანა 3
class ReplaceWithBrackets
{
    public static function run(string $string): string
    {
        $string = mb_strtolower($string);
        $array = mb_str_split($string);

        $replaced = '';
        foreach ($array as $value) {
            if (mb_substr_count($string, $value) > 1) {
                $replaced .= ')';
            } else {
                $replaced .= '(';
            }
        }

        return $replaced;
    }
}
