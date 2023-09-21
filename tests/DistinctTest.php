<?php declare(strict_types=1);

namespace Stream\Tests;

require_once "Data/DataProvider.php";

use PHPUnit\Framework\TestCase;
use Stream\Stream;
use Stream\Tests\Data\DataProvider;

class DistinctTest extends TestCase {
    /**
     * @dataProvider dataProvider
     */
    public function testDistinct($expected, $input): void {
        $this->assertEquals($expected, Stream::of($input)->distinct()->collect());
    }

    public function dataProvider(): array {
        return DataProvider::distinct();
    }
}