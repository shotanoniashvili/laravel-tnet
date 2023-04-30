<?php

namespace App\Helpers;

// ამოცანა 6
class SnailArray
{
    public static function run(array $array): array
    {
        $sorted = [];

        $n = count($array);
        $i = 0;
        $j = 0;

        while ($n > 1) {
            for ($k = 0; $k < $n - 1; $k++) {
                $sorted[] = $array[$i][$j];
                $j++;
            }

            for ($k = 0; $k < $n - 1; $k++) {
                $sorted[] = $array[$i][$j];
                $i++;
            }

            for ($k = 0; $k < $n - 1; $k++) {
                $sorted[] = $array[$i][$j];
                $j--;
            }

            for ($k = 0; $k < $n - 1; $k++) {
                $sorted[] = $array[$i][$j];
                $i--;
            }

            $n -= 2;
            $i++;
            $j++;
        }

        if ($n === 1) {
            $sorted[] = $array[$i][$j];
        }

        return $sorted;
    }
}
