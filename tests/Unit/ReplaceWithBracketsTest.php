<?php

namespace Tests\Unit;

use App\Helpers\ReplaceWithBrackets;
use PHPUnit\Framework\TestCase;

class ReplaceWithBracketsTest extends TestCase
{
    public function testReplaceStringWithBrackets(): void
    {
        $string = ReplaceWithBrackets::run("din");
        $this->assertEquals($string, '(((');

        $string = ReplaceWithBrackets::run("recede");
        $this->assertEquals($string, '()()()');

        $string = ReplaceWithBrackets::run("Success");
        $this->assertEquals($string, ')())())');

        $string = ReplaceWithBrackets::run("(( @");
        $this->assertEquals($string, '))((');
    }
}
