<?php declare(strict_types=1);

namespace Moteam\Stream\Tests;

require_once "Data/DataProvider.php";

use PHPUnit\Framework\TestCase;
use Moteam\Stream\Tests\Data\DataProvider;
use function Moteam\Stream\Library\S as __S;

class MutatorsTest extends TestCase {
    /**
     * @covers \Moteam\Stream\Library\Mutators\ConcatStream
     * @covers \Moteam\Stream::concat
     * @dataProvider concatProvider
     */
    public function testConcat($expected, $input, $concat): void {
        $this->assertEquals($expected, __S($input)->concat($concat)->collect());
    }
    /**
     * @covers \Moteam\Stream\Library\Mutators\ConcatBeforeStream
     * @covers \Moteam\Stream::concatBefore
     * @dataProvider concatBeforeProvider
     */
    public function testConcatBefore($expected, $input, $concat): void {
        $this->assertEquals($expected, __S($input)->concatBefore($concat)->collect());
    }

    /**
     * @covers \Moteam\Stream\Library\Mutators\MapStream
     * @covers \Moteam\Stream::map
     * @dataProvider mapProvider
     */
    public function testMap($expected, $input, $fn): void {
        $this->assertEquals($expected, __S($input)->map($fn)->collect());
    }

    /**
     * @covers \Moteam\Stream\Library\Mutators\MapByStream
     * @covers \Moteam\Stream::mapBy
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
     * @covers \Moteam\Stream::distinct
     * @dataProvider distinctProvider
     */
    public function testDistinct($expected, $input): void {
        $this->assertEquals($expected, __S($input)->distinct()->collect());
        $this->assertEquals([1, 1, 2, 2], __S([1, 1, 1, 2, 2, 2])->distinct(2)->collect());
    }

    /**
     * @covers \Moteam\Stream\Library\Mutators\EnrichStream
     * @covers \Moteam\Stream::enrich
     * @dataProvider enrichProvider
     */
    public function testEnrich($expected, $input, $fn): void {
        $this->assertEquals($expected, __S($input)->enrich($fn)->collect());
    }

    /**
     * @covers \Moteam\Stream\Library\Mutators\FilterStream
     * @covers \Moteam\Stream::filter
     * @dataProvider filterProvider
     */
    public function testFilter($expected, $input, $fn): void {
        $this->assertEquals($expected, __S($input)->filter($fn)->collect());
        $this->assertEmpty(__S([0, 0, 0])->filter()->collect());
    }

    /**
     * @covers \Moteam\Stream\Library\Mutators\RejectStream
     * @covers \Moteam\Stream::reject
     * @dataProvider rejectProvider
     */
    public function testReject($expected, $input, $fn): void {
        $this->assertEquals($expected, __S($input)->reject($fn)->collect());
    }

    /**
     * @covers \Moteam\Stream\Library\Mutators\ForeachStream
     * @covers \Moteam\Stream::foreach
     */
    public function testForeach(): void {
        $data = [];
        __S([1, 2, 3, 4, 5])->foreach(function($x) use(&$data) {
            $data[] = $x;
        })();
        $this->assertEquals([1, 2, 3, 4, 5], $data);
    }

    /**
     * @covers \Moteam\Stream\Library\Mutators\ForallStream
     * @covers \Moteam\Stream::forall
     */
    public function testForall(): void {
        $data = [];
        __S([1, 2, 3, 4, 5])->forall(function($all) use(&$data) {
            $data = $all;
        })();
        $this->assertEquals([1, 2, 3, 4, 5], $data);
    }

    /**
     * @covers \Moteam\Stream\Library\Mutators\LimitStream
     * @covers \Moteam\Stream::limit
     * @dataProvider limitProvider
     */
    public function testLimit($expected, $input, $n): void {
        $this->assertEquals($expected, __S($input)->limit($n)->collect());
    }

    /**
     * @covers \Moteam\Stream\Library\Mutators\SkipStream
     * @covers \Moteam\Stream::skip
     * @dataProvider skipProvider
     */
    public function testSkip($expected, $input, $n): void {
        $this->assertEquals($expected, __S($input)->skip($n)->collect());
    }

    /**
     * @covers \Moteam\Stream\Library\Mutators\SortedStream
     * @covers \Moteam\Stream::sorted
     * @dataProvider sortedProvider
     */
    public function testSorted($expected, $input, $fn): void {
        $this->assertEquals($expected, __S($input)->sort($fn)->collect());
    }

    /**
     * @covers \Moteam\Stream\Library\Mutators\CountByStream
     * @covers \Moteam\Stream::countBy
     * @dataProvider countByProvider
     */
    public function testCountBy($expected, $input, $fn): void {
        $this->assertEquals($expected, __S($input)->countBy($fn)->collect());
    }

    /**
     * @covers \Moteam\Stream\Library\Mutators\GroupByStream
     * @covers \Moteam\Stream::groupBy
     * @dataProvider groupByProvider
     */
    public function testGroupBy($expected, $input, $fn): void {
        $this->assertEquals($expected, __S($input)->groupBy($fn)->collect());
    }

    /**
     * @covers \Moteam\Stream\Library\Mutators\IndexByStream
     * @covers \Moteam\Stream::indexBy
     * @dataProvider indexByProvider
     */
    public function testIndexBy($expected, $input, $fn): void {
        $this->assertEquals($expected, __S($input)->indexBy($fn)->collect());
    }

    /**
     * @covers \Moteam\Stream\Library\Mutators\KeysStream
     * @covers \Moteam\Stream::keys
     * @dataProvider keysProvider
     */
    public function testKeys($expected, $input): void {
        $this->assertEquals($expected, __S($input)->keys()->collect());
    }

    /**
     * @covers \Moteam\Stream\Library\Mutators\PartitionStream
     * @covers \Moteam\Stream::partition
     * @dataProvider partitionProvider
     */
    public function testPartition($expected, $input, $fn): void {
        $this->assertEquals($expected, __S($input)->partition($fn)->collect());
    }

    /**
     * @covers \Moteam\Stream\Library\Mutators\ShuffleStream
     * @covers \Moteam\Stream::shuffle
     */
    public function testShuffle(): void {
        $in = [1, 2, 3, 4, 5];
        $out = __S($in)->shuffle()->collect();
        foreach($out as $v) {
            $this->assertContains($v, $in);
        }
    }

    /**
     * @covers \Moteam\Stream\Library\Mutators\RandomNStream
     * @covers \Moteam\Stream::randomN
     */
    public function testRandom(): void {
        $in = [1, 2, 3, 4, 5];
        $got = __S($in)->randomN(3)->collect();
        $this->assertSameSize($got, array_unique($got));
        foreach($got as $x) {
            $this->assertContains($x, $in);
        }
        $out2 = __S($in)->randomN()->collect();
        $this->assertSameSize([0], $out2);
        $this->assertContains($out2[0], $in);
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
    public function concatBeforeProvider(): array { return DataProvider::concatBefore(); }
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
