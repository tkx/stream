<?php declare(strict_types=1);

namespace Moteam\Stream\Tests;

require_once "Data/DataProvider.php";

use PHPUnit\Framework\TestCase;
use Moteam\Stream\Tests\Data\DataProvider;
use function Moteam\Stream\Library\S as __S;

class MutatorsTest extends TestCase {
    /**
     * @covers \Moteam\Stream\Library\Mutators\ConcatStream
     * @dataProvider concatProvider
     */
    public function testConcat($expected, $input, $concat): void {
        $this->assertEquals($expected, __S($input)->concat($concat)->collect());
    }

    /**
     * @covers \Moteam\Stream\Library\Mutators\MapStream
     * @dataProvider mapProvider
     */
    public function testMap($expected, $input, $fn): void {
        $this->assertEquals($expected, __S($input)->map($fn)->collect());
    }

    /**
     * @covers \Moteam\Stream\Library\Mutators\MapByStream
     */
    public function testMapBy(): void {
        $expected = [1 => [1 * 2, 1.5 * 2], 2 => [2 * 2, 2.5 * 2], 3 => [3 * 2]];
        $this->assertEquals($expected, __S([1, 1.5, 2, 2.5, 3])
            ->groupBy(fn($x) => (int)floor($x))
            ->mapBy(fn($x) => $x * 2)
            ->collect()
        );
    }

    /**
     * @covers \Moteam\Stream\Library\Mutators\DistinctStream
     * @dataProvider distinctProvider
     */
    public function testDistinct($expected, $input): void {
        $this->assertEquals($expected, __S($input)->distinct()->collect());
    }

    /**
     * @covers \Moteam\Stream\Library\Mutators\EnrichStream
     * @dataProvider enrichProvider
     */
    public function testEnrich($expected, $input, $fn): void {
        $this->assertEquals($expected, __S($input)->enrich($fn)->collect());
    }

    /**
     * @covers \Moteam\Stream\Library\Mutators\FilterStream
     * @dataProvider filterProvider
     */
    public function testFilter($expected, $input, $fn): void {
        $this->assertEquals($expected, __S($input)->filter($fn)->collect());
    }

    /**
     * @covers \Moteam\Stream\Library\Mutators\RejectStream
     * @dataProvider rejectProvider
     */
    public function testReject($expected, $input, $fn): void {
        $this->assertEquals($expected, __S($input)->reject($fn)->collect());
    }

    /**
     * @covers \Moteam\Stream\Library\Mutators\ForeachStream
     */
    public function testForeach(): void {
        $data = [];
        __S([1, 2, 3, 4, 5])->foreach(function($x) use(&$data) {
            $data[] = $x;
        })();
        $this->assertEquals([1, 2, 3, 4, 5], $data);
    }

    /**
     * @covers \Moteam\Stream\Library\Mutators\LimitStream
     * @dataProvider limitProvider
     */
    public function testLimit($expected, $input, $n): void {
        $this->assertEquals($expected, __S($input)->limit($n)->collect());
    }

    /**
     * @covers \Moteam\Stream\Library\Mutators\SkipStream
     * @dataProvider skipProvider
     */
    public function testSkip($expected, $input, $n): void {
        $this->assertEquals($expected, __S($input)->skip($n)->collect());
    }

    /**
     * @covers \Moteam\Stream\Library\Mutators\SortedStream
     * @dataProvider sortedProvider
     */
    public function testSorted($expected, $input, $fn): void {
        $this->assertEquals($expected, __S($input)->sorted($fn)->collect());
    }

    /**
     * @covers \Moteam\Stream\Library\Mutators\CountByStream
     * @dataProvider countByProvider
     */
    public function testCountBy($expected, $input, $fn): void {
        $this->assertEquals($expected, __S($input)->countBy($fn)->collect());
    }

    /**
     * @covers \Moteam\Stream\Library\Mutators\GroupByStream
     * @dataProvider groupByProvider
     */
    public function testGroupBy($expected, $input, $fn): void {
        $this->assertEquals($expected, __S($input)->groupBy($fn)->collect());
    }

    /**
     * @covers \Moteam\Stream\Library\Mutators\IndexByStream
     * @dataProvider indexByProvider
     */
    public function testIndexBy($expected, $input, $fn): void {
        $this->assertEquals($expected, __S($input)->indexBy($fn)->collect());
    }

    /**
     * @covers \Moteam\Stream\Library\Mutators\KeysStream
     * @dataProvider keysProvider
     */
    public function testKeys($expected, $input): void {
        $this->assertEquals($expected, __S($input)->keys()->collect());
    }

    /**
     * @covers \Moteam\Stream\Library\Mutators\PartitionStream
     * @dataProvider partitionProvider
     */
    public function testPartition($expected, $input, $fn): void {
        $this->assertEquals($expected, __S($input)->partition($fn)->collect());
    }

    /**
     * @covers \Moteam\Stream\Library\Mutators\RandomNStream
     */
    public function testRandom(): void {
        $in = [1, 2, 3, 4, 5];
        $got = __S($in)->randomN(3)->collect();
        $this->assertSameSize($got, array_unique($got));
        foreach($got as $x) {
            $this->assertContains($x, $in);
        }
    }

    /**
     * @return array
     */
    public function limitProvider(): array { return DataProvider::limit(); }
    /**
     * @return array
     */
    public function filterProvider(): array { return DataProvider::filter(); }
    /**
     * @return array
     */
    public function rejectProvider(): array { return DataProvider::reject(); }
    /**
     * @return array
     */
    public function distinctProvider(): array { return DataProvider::distinct(); }
    /**
     * @return array
     */
    public function mapProvider(): array { return DataProvider::map(); }
    /**
     * @return array
     */
    public function concatProvider(): array { return DataProvider::concat(); }
    /**
     * @return array
     */
    public function enrichProvider(): array { return DataProvider::enrich(); }
    /**
     * @return array
     */
    public function skipProvider(): array { return DataProvider::skip(); }
    /**
     * @return array
     */
    public function sortedProvider(): array { return DataProvider::sorted(); }
    /**
     * @return array
     */
    public function countByProvider(): array { return DataProvider::countBy(); }
    /**
     * @return array
     */
    public function groupByProvider(): array { return DataProvider::groupBy(); }
    /**
     * @return array
     */
    public function indexByProvider(): array { return DataProvider::indexBy(); }
    /**
     * @return array
     */
    public function keysProvider(): array { return DataProvider::keys(); }
    /**
     * @return array
     */
    public function partitionProvider(): array { return DataProvider::partition(); }
}
