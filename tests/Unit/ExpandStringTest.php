<?php

namespace Tests\Unit;

use App\Helpers\ExpandString;
use PHPUnit\Framework\TestCase;

class ExpandStringTest extends TestCase
{
    public function testExpandString(): void
    {
        $result = ExpandString::run("3(ab)");
        $this->assertEquals("ababab", $result);

        $result = ExpandString::run("2(a3(b))");
        $this->assertEquals("abbbabbb", $result);

        $result = ExpandString::run("1(ab3(c1(d))");
        $this->assertEquals("abcdcdcd", $result);
    }
}
