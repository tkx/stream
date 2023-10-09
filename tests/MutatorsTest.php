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
    public function testConcat($expected, $input, $concat, $preserve_keys): void {
        $this->assertEquals($expected, __S($input)->concat($concat, $preserve_keys)->collect());

        $this->assertSame(
            ["a" => 1, "b" => 2, "c" => 3],
            __S(json_decode(json_encode(["a" => 1, "b" => 2])))->concat(json_decode(json_encode(["c" => 3])), true)->collect()
        );
    }
    /**
     * @covers \Moteam\Stream\Library\Mutators\ConcatBeforeStream
     * @covers \Moteam\Stream::concatBefore
     * @dataProvider concatBeforeProvider
     */
    public function testConcatBefore($expected, $input, $concat, $preserve_keys): void {
        $this->assertEquals($expected, __S($input)->concatBefore($concat, $preserve_keys)->collect());
    }

    /**
     * @covers \Moteam\Stream\Library\Mutators\MapStream
     * @covers \Moteam\Stream::map
     * @dataProvider mapProvider
     */
    public function testMap($expected, $input, $fn, $preserve_keys): void {
        $this->assertEquals($expected, __S($input)->map($fn, $preserve_keys)->collect());
    }

    /**
     * @covers \Moteam\Stream\Library\Mutators\MapAllStream
     * @covers \Moteam\Stream::mapAll
     */
    public function testMapBy(): void {
        $expected = [1 => [1 * 2, 1.5 * 2], 2 => [2 * 2, 2.5 * 2], 3 => [3 * 2]];
        $this->assertEquals($expected, __S([1, 1.5, 2, 2.5, 3])
            ->groupBy(fn($x) => (int)floor($x))
            ->mapAll(fn($x) => $x * 2)
            ->collect()
        );

        $expected = [1 => ["a" => 1 * 2, "b" => 1.5 * 2], 2 => ["c" => 2 * 2, "d" => 2.5 * 2], 3 => ["e" => 3 * 2]];
        $this->assertEquals($expected, __S(["a" => 1, "b" => 1.5, "c" => 2, "d" => 2.5, "e" => 3])
            ->groupBy(fn($x) => (int)floor($x), true)
            ->mapAll(fn($x) => $x * 2, true)
            ->collect()
        );

        $expected = [1 => [1 * 0 * 1, 1.5 * 1 * 1], 2 => [2 * 0 * 2, 2.5 * 1 * 2], 3 => [3 * 0 * 3]];
        $this->assertEquals($expected, __S([1, 1.5, 2, 2.5, 3])
            ->groupBy(fn($x) => (int)floor($x))
            ->mapAll(fn($x, $k, $k0) => $x * $k * $k0)
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
        
        $this->assertEquals(["a" => 1, "b" => 2], __S(["a" => 1, "a1" => 1, "a2" => 1, "b" => 2, "b1" => 2, "b2" => 2])->distinct(1, true)->collect());
        $this->assertEquals(["a" => 1, "a1" => 1, "b" => 2, "b1" => 2], __S(["a" => 1, "a1" => 1, "a2" => 1, "b" => 2, "b1" => 2, "b2" => 2])->distinct(2, true)->collect());
    
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
    public function testFilter($expected, $input, $fn, $preserve_keys): void {
        $this->assertEquals($expected, __S($input)->filter($fn, $preserve_keys)->collect());
        $this->assertEmpty(__S([0, 0, 0])->filter()->collect());
    }

    /**
     * @covers \Moteam\Stream\Library\Mutators\RejectStream
     * @covers \Moteam\Stream::reject
     * @dataProvider rejectProvider
     */
    public function testReject($expected, $input, $fn, $preserve_keys): void {
        $this->assertEquals($expected, __S($input)->reject($fn, $preserve_keys)->collect());
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

        $data = [];
        __S([1, 2, 3, 4, 5])->foreach(function($x, $k) use(&$data) {
            $data[] = $k;
        })();
        $this->assertEquals([0, 1, 2, 3, 4], $data);
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
    public function testLimit($expected, $input, $n, $preserve_keys): void {
        $this->assertEquals($expected, __S($input)->limit($n, $preserve_keys)->collect());
    }

    /**
     * @covers \Moteam\Stream\Library\Mutators\SkipStream
     * @covers \Moteam\Stream::skip
     * @dataProvider skipProvider
     */
    public function testSkip($expected, $input, $n, $preserve_keys): void {
        $this->assertEquals($expected, __S($input)->skip($n, $preserve_keys)->collect());
    }

    /**
     * @covers \Moteam\Stream\Library\Mutators\SortedStream
     * @covers \Moteam\Stream::sorted
     * @dataProvider sortedProvider
     */
    public function testSorted($expected, $input, $fn, $by_keys, $preserve_keys): void {
        $this->assertEquals($expected, __S($input)->sort($fn, $by_keys, $preserve_keys)->collect());
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
    public function testGroupBy($expected, $input, $fn, $preserve_keys): void {
        $this->assertEquals($expected, __S($input)->groupBy($fn, $preserve_keys)->collect());
    }

    /**
     * @covers \Moteam\Stream\Library\Mutators\IndexByStream
     * @covers \Moteam\Stream::indexBy
     * @dataProvider indexByProvider
     */
    public function testIndexBy($expected, $input, $by): void {
        $this->assertEquals($expected, __S($input)->indexBy($by)->collect());
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
     * @covers \Moteam\Stream\Library\Mutators\ValuesStream
     * @covers \Moteam\Stream::values
     * @dataProvider valuesProvider
     */
    public function testValues($expected, $input): void {
        $this->assertEquals($expected, __S($input)->values()->collect());
    }

    /**
     * @covers \Moteam\Stream\Library\Mutators\PartitionStream
     * @covers \Moteam\Stream::partition
     * @dataProvider partitionProvider
     */
    public function testPartition($expected, $input, $fn, $preserve_keys): void {
        $this->assertEquals($expected, __S($input)->partition($fn, $preserve_keys)->collect());
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

        $in = ["a" => 1, "b" => 2, "c" => 3, "d" => 4, "e" => 5];
        $out = __S($in)->shuffle(true)->collect();
        foreach($out as $k => $v) {
            $this->assertEquals($v, $in[$k]);
        }
        $this->assertSameSize(array_values($out), array_values($in));
        $this->assertSameSize(array_keys($out), array_keys($in));
    }

    /**
     * @covers \Moteam\Stream\Library\Mutators\RandomNStream
     * @covers \Moteam\Stream::randomN
     */
    public function testRandomN(): void {
        $in = [1, 2, 3, 4, 5];
        $got = __S($in)->randomN(3)->collect();
        $this->assertSameSize($got, array_unique($got));
        foreach($got as $x) {
            $this->assertContains($x, $in);
        }
        $out2 = __S($in)->randomN()->collect();
        $this->assertSameSize([0], $out2);
        $this->assertContains($out2[0], $in);

        $in = ["a" => 1, "b" => 2, "c" => 3, "d" => 4, "e" => 5];
        $got = __S($in)->randomN(3, true)->collect();
        $this->assertSameSize([0,0,0], $got);
        foreach($got as $k => $x) {
            $this->assertArrayHasKey($k, $in);
            $this->assertEquals($in[$k], $x);
        }
    }

    public function limitProvider(): array { return DataProvider::limit(); }
    public function filterProvider(): array { return DataProvider::filter(); }
    public function rejectProvider(): array { return DataProvider::reject(); }
    public function distinctProvider(): array { return DataProvider::distinct(); }
    public function mapProvider(): array { return DataProvider::map(); }
    public function concatProvider(): array { return DataProvider::concat(); }
    public function concatBeforeProvider(): array { return DataProvider::concatBefore(); }
    public function enrichProvider(): array { return DataProvider::enrich(); }
    public function skipProvider(): array { return DataProvider::skip(); }
    public function sortedProvider(): array { return DataProvider::sorted(); }
    public function countByProvider(): array { return DataProvider::countBy(); }
    public function groupByProvider(): array { return DataProvider::groupBy(); }
    public function indexByProvider(): array { return DataProvider::indexBy(); }
    public function keysProvider(): array { return DataProvider::keys(); }
    public function valuesProvider(): array { return DataProvider::values(); }
    public function partitionProvider(): array { return DataProvider::partition(); }
}
