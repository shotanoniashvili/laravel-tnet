<?php

namespace App\Helpers;

// ამოცანა 2
class ArrayElementCount
{
    public static function run(array $array, int $count = 0): int
    {
        foreach ($array as $value) {
            if (is_array($value)) {
                $count = self::run($value, $count + 1);
            } else {
                $count++;
            }
        }

        return $count;
    }
}
