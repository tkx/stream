<?php declare(strict_types=1);

namespace Stream\Tests;

require_once "Data/DataProvider.php";

use PHPUnit\Framework\TestCase;
use Stream\Stream;
use Stream\Tests\Data\DataProvider;

class MapTest extends TestCase {
    /**
     * @dataProvider dataProvider
     */
    public function testMap($expected, $input, $fn): void {
        $this->assertEquals($expected, Stream::of($input)->map($fn)->collect());
    }

    public function dataProvider(): array {
        return DataProvider::map();
    }
}
