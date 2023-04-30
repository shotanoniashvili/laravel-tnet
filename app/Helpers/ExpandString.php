<?php

namespace App\Helpers;

// ამოცანა 4
use App\Http\Middleware\PreventRequestsDuringMaintenance;

class ExpandString
{
    public static function run(string $string): string
    {
        $expanded = '';
        $array = mb_str_split($string);

        $n = array_shift($array);

        if (count($array) > 0 && $array[0] === '(') {
            $array = array_slice($array, 1, count($array) - 1);
        }

        if (count($array) > 0 && $array[count($array) - 1] === ')') {
            $array = array_slice($array, 0, count($array) - 1);
        }

        if (is_numeric($n)) {
            for ($i = 0; $i < $n; $i++) {
                if (count($array) > 0) {
                    $expanded .= self::run(implode('', $array));
                }
            }
        } else {
            $expanded .= $n;
            if (count($array) > 0) {
                $expanded .= self::run(implode('', $array));
            }
        }

        return $expanded;
    }
}
