<?php

namespace Tests\Unit;

use App\Helpers\SnailArray;
use PHPUnit\Framework\TestCase;

class SnailArrayTest extends TestCase
{
    public function testSnailArray(): void
    {
        $result = SnailArray::run([
            [1,2,3],
            [4,5,6],
            [7,8,9]
        ]);
        $this->assertEquals([1,2,3,6,9,8,7,4,5], $result);

        $result = SnailArray::run([
            [1,2,3,1],
            [4,7,7,9],
            [8,7,7,4],
            [5,6,8,9]
        ]);
        $this->assertEquals([1,2,3,1,9,4,9,8,6,5,8,4,7,7,7,7], $result);

        $result = SnailArray::run([
            [1,2,3,6,3],
            [8,9,4,8,5],
            [7,6,5,1,2],
            [4,2,7,5,0],
            [9,5,8,1,4]
        ]);
        $this->assertEquals([1,2,3,6,3,5,2,0,4,1,8,5,9,4,7,8,9,4,8,1,5,7,2,6,5], $result);
    }
}
