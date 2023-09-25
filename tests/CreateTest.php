<?php declare(strict_types=1);

namespace Moteam\Stream\Tests;

require_once "Data/DataProvider.php";

use PHPUnit\Framework\TestCase;
use Moteam\Stream\Stream;
use Moteam\Stream\Tests\Data\DataProvider;

class CreateTest extends TestCase {
    /**
     * @covers \Moteam\Stream\Stream
     * @dataProvider dataProvider
     */
    public function testArray($expected, $input): void {
        $this->assertEquals($expected, Stream::of($input)->collect());
    }

    /**
     * @covers \Moteam\Stream\Stream
     */
    public function testContributing(): void {
        require_once "Data/NullStream.php";
        require_once "Data/ZeroTerminal.php";

        $this->assertEquals([0, 0, 0, 0, 0], Stream::of([1,2,3,4,5])->null()->zero());
    }

    /**
     * @covers \Moteam\Stream\Stream
     */
    public function testCustomParametersValidators(): void {
        require_once "Data/CustomValidatorsStream.php";
        require_once "Data/CustomReduceTerminal.php";

        $this->assertEquals(0, Stream::of([1,2,3,4,5])->customValidators(10)->customReduce(fn($a, $x) => 0, 0));
    }

    /**
     * @return array
     */
    public function dataProvider(): array { return DataProvider::create(); }
}
