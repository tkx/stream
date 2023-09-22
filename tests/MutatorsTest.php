<?php declare(strict_types=1);

namespace Stream\Tests;

require_once "Data/DataProvider.php";

use PHPUnit\Framework\TestCase;
use Stream\Stream;
use Stream\Tests\Data\DataProvider;

class MutatorsTest extends TestCase {
    /**
     * @covers \Stream\Library\Mutators\ConcatStream
     * @dataProvider concatProvider
     */
    public function testConcat($expected, $input, $concat): void {
        $this->assertEquals($expected, Stream::of($input)->concat($concat)->collect());
    }

    /**
     * @covers \Stream\Library\Mutators\MapStream
     * @dataProvider mapProvider
     */
    public function testMap($expected, $input, $fn): void {
        $this->assertEquals($expected, Stream::of($input)->map($fn)->collect());
    }

    /**
     * @covers \Stream\Library\Mutators\DistinctStream
     * @dataProvider distinctProvider
     */
    public function testDistinct($expected, $input): void {
        $this->assertEquals($expected, Stream::of($input)->distinct()->collect());
    }

    /**
     * @covers \Stream\Library\Mutators\EnrichStream
     * @dataProvider enrichProvider
     */
    public function testEnrich($expected, $input, $fn): void {
        $this->assertEquals($expected, Stream::of($input)->enrich($fn)->collect());
    }

    /**
     * @covers \Stream\Library\Mutators\FilterStream
     * @dataProvider filterProvider
     */
    public function testFilter($expected, $input, $fn): void {
        $this->assertEquals($expected, Stream::of($input)->filter($fn)->collect());
    }

    /**
     * @covers \Stream\Library\Mutators\RejectStream
     * @dataProvider rejectProvider
     */
    public function testReject($expected, $input, $fn): void {
        $this->assertEquals($expected, Stream::of($input)->reject($fn)->collect());
    }

    /**
     * @covers \Stream\Library\Mutators\ForeachStream
     */
    public function testForeach(): void {
        $data = [];
        Stream::of([1, 2, 3, 4, 5])->foreach(function($x) use(&$data) {
            $data[] = $x;
        })();
        $this->assertEquals([1, 2, 3, 4, 5], $data);
    }

    /**
     * @covers \Stream\Library\Mutators\LimitStream
     * @dataProvider limitProvider
     */
    public function testLimit($expected, $input, $n): void {
        $this->assertEquals($expected, Stream::of($input)->limit($n)->collect());
    }

    /**
     * @covers \Stream\Library\Mutators\SkipStream
     * @dataProvider skipProvider
     */
    public function testSkip($expected, $input, $n): void {
        $this->assertEquals($expected, Stream::of($input)->skip($n)->collect());
    }

    /**
     * @covers \Stream\Library\Mutators\SortedStream
     * @dataProvider sortedProvider
     */
    public function testSorted($expected, $input, $fn): void {
        $this->assertEquals($expected, Stream::of($input)->sorted($fn)->collect());
    }

    /**
     * @covers \Stream\Library\Mutators\CountByStream
     * @dataProvider countByProvider
     */
    public function testCountBy($expected, $input, $fn): void {
        $this->assertEquals($expected, Stream::of($input)->countBy($fn)->collect());
    }

    /**
     * @covers \Stream\Library\Mutators\GroupByStream
     * @dataProvider groupByProvider
     */
    public function testGroupBy($expected, $input, $fn): void {
        $this->assertEquals($expected, Stream::of($input)->groupBy($fn)->collect());
    }

    /**
     * @covers \Stream\Library\Mutators\IndexByStream
     * @dataProvider indexByProvider
     */
    public function testIndexBy($expected, $input, $fn): void {
        $this->assertEquals($expected, Stream::of($input)->indexBy($fn)->collect());
    }

    /**
     * @covers \Stream\Library\Mutators\KeysStream
     * @dataProvider keysProvider
     */
    public function testKeys($expected, $input): void {
        $this->assertEquals($expected, Stream::of($input)->keys()->collect());
    }

    /**
     * @covers \Stream\Library\Mutators\PartitionStream
     * @dataProvider partitionProvider
     */
    public function testPartition($expected, $input, $fn): void {
        $this->assertEquals($expected, Stream::of($input)->partition($fn)->collect());
    }

    /**
     * @covers \Stream\Library\Mutators\RandomNStream
     */
    public function testRandom(): void {
        $in = [1, 2, 3, 4, 5];
        $got = Stream::of($in)->randomN(3)->collect();
        $this->assertSameSize($got, array_unique($got));
        foreach($got as $x) {
            $this->assertContains($x, $in);
        }
    }

    public function limitProvider(): array { return DataProvider::limit(); }
    public function filterProvider(): array { return DataProvider::filter(); }
    public function rejectProvider(): array { return DataProvider::reject(); }
    public function distinctProvider(): array { return DataProvider::distinct(); }
    public function mapProvider(): array { return DataProvider::map(); }
    public function concatProvider(): array { return DataProvider::concat(); }
    public function enrichProvider(): array { return DataProvider::enrich(); }
    public function skipProvider(): array { return DataProvider::skip(); }
    public function sortedProvider(): array { return DataProvider::sorted(); }
    public function countByProvider(): array { return DataProvider::countBy(); }
    public function groupByProvider(): array { return DataProvider::groupBy(); }
    public function indexByProvider(): array { return DataProvider::indexBy(); }
    public function keysProvider(): array { return DataProvider::keys(); }
    public function partitionProvider(): array { return DataProvider::partition(); }
}
