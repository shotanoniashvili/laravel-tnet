<?php

namespace App\Helpers;

// ამოცანა 5
class RangeExtraction
{
    public static function run(array $array): string
    {
        $k = 1;
        $ranges = [];
        for ($i = 0, $iMax = count($array); $i < $iMax; $i++) {
            if ($i > 0 && $array[$i] - $array[$i - 1] === 1) {
                $k++;
            } else {
                $k = 1;
            }

            if ($k >= 3 && count($array) > $i + 1 && $array[$i] - $array[$i + 1] !== -1) {
                $ranges[] = [
                    'from' => $i - $k + 1,
                    'to' => $i + 1,
                ];
            } else if ($k >= 3 && count($array) === $i + 1) {
                $ranges[] = [
                    'from' => $i - $k + 1,
                    'to' => $i + 1,
                ];
            }
        }

        $elements = [];
        for ($i = 0, $iMax = count($array); $i < $iMax; $i++) {
            $rangeEl = array_values(array_filter($ranges, fn ($item) => $item['from'] === $i));

            if (count($rangeEl) > 0) {
                $range = $rangeEl[0];
                $elements[] = $array[$range['from']] . '-' . $array[$range['to'] - 1];

                $i = $range['to'] - 1;
            } else {
                $elements[] = $array[$i];
            }
        }

        return implode(',', $elements);
    }
}
