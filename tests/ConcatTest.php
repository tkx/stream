<?php declare(strict_types=1);

namespace Stream\Tests;

require_once "Data/DataProvider.php";

use PHPUnit\Framework\TestCase;
use Stream\Stream;
use Stream\Tests\Data\DataProvider;

class ConcatTest extends TestCase {
    /**
     * @dataProvider dataProvider
     */
    public function testConcat($expected, $input, $concat): void {
        $this->assertEquals($expected, Stream::of($input)->concat($concat)->collect());
    }

    public function dataProvider(): array {
        return DataProvider::concat();
    }
}
