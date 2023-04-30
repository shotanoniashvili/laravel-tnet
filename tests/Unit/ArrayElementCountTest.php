<?php

namespace Tests\Unit;

use App\Helpers\ArrayElementCount;
use PHPUnit\Framework\TestCase;

class ArrayElementCountTest extends TestCase
{
    public function testArrayElementsCount(): void
    {
        $elementsCount = ArrayElementCount::run([1, 2, 3]);
        $this->assertEquals(3, $elementsCount);

        $elementsCount = ArrayElementCount::run(["x", "y", ["z"]]);
        $this->assertEquals(4, $elementsCount);

        $elementsCount = ArrayElementCount::run([1, 2, [3, 4, [5]]]);
        $this->assertEquals(7, $elementsCount);

        $elementsCount = ArrayElementCount::run([]);
        $this->assertEquals(0, $elementsCount);

        $elementsCount = ArrayElementCount::run([[], [1, 33, [32, 22]], [22, 32]]);
        $this->assertEquals(10, $elementsCount);
    }
}
