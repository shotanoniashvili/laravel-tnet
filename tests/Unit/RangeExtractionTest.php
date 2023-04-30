<?php

namespace Tests\Unit;

use App\Helpers\RangeExtraction;
use PHPUnit\Framework\TestCase;

class RangeExtractionTest extends TestCase
{
    public function testRangeExtraction(): void
    {
        $result = RangeExtraction::run([-6,-3,-2,-1,0,1,3,4,5,7,8,9,10,11,14,15,17,18,19,20]);
        $this->assertEquals('-6,-3-1,3-5,7-11,14,15,17-20', $result);

        $result = RangeExtraction::run([-6,4,5,6,7,8,9,10,11,14,15,17,18,19,20,25,24]);
        $this->assertEquals('-6,4-11,14,15,17-20,25,24', $result);
    }
}
