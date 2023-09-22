<?php declare(strict_types=1);

namespace Stream\Tests;

require_once "Data/DataProvider.php";

use PHPUnit\Framework\TestCase;
use Stream\Stream;
use Stream\Tests\Data\DataProvider;

class CreateTest extends TestCase {
    /**
     * @covers \Stream\Stream
     * @dataProvider dataProvider
     */
    public function testArray($expected, $input): void {
        $this->assertEquals($expected, Stream::of($input)->collect());
    }

    public function dataProvider(): array {
        return DataProvider::create();
    }
}
