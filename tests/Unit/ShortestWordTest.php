<?php

namespace Tests\Unit;

use App\Helpers\ShortestWord;
use PHPUnit\Framework\TestCase;

class ShortestWordTest extends TestCase
{
    public function testShortestWordLength(): void
    {
        $shortestWordLength = ShortestWord::run("bitcoin take over the world maybe who knows perhaps");
        $this->assertEquals(3, $shortestWordLength);

        $shortestWordLength = ShortestWord::run("turns out random test cases are easier than writing out basic ones");
        $this->assertEquals(3, $shortestWordLength);

        $shortestWordLength = ShortestWord::run("Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua");
        $this->assertEquals(2, $shortestWordLength);

        $shortestWordLength = ShortestWord::run("Excepteur sint occaecat cupidatat");
        $this->assertEquals(4, $shortestWordLength);
    }
}
