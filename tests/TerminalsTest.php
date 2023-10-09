<?php declare(strict_types=1);

namespace Moteam\Stream\Tests;

require_once "Data/DataProvider.php";

use PHPUnit\Framework\TestCase;
use function Moteam\Stream\Library\S as __S;

class TerminalsTest extends TestCase {
    /**
     * @covers \Moteam\Stream\Library\Terminals\AllMatchTerminal
     * @covers \Moteam\Stream::allMatch
     */
    public function testAllMatch() {
        $this->assertTrue(__S([2,4,6,8,10])->allMatch(fn($x) => $x%2 == 0));
        $this->assertTrue(__S(["a"=>2,"b"=>4,"c"=>6,"d"=>8,"e"=>10])->allMatch(fn($x, $k) => $x%2 == 0 && is_string($k)));
    }

    /**
     * @covers \Moteam\Stream\Library\Terminals\AnyMatchTerminal
     * @covers \Moteam\Stream::anyMatch
     */
    public function testAnyMatch() {
        $this->assertTrue(__S(["a"=>2,"b"=>4,"c"=>6,"d"=>8,"e"=>10])->anyMatch(fn($x, $k) => $x%2 == 0 && is_string($k)));
        $this->assertTrue(__S([2,4,6,8,10])->anyMatch(fn($x) => $x%2 == 0));
        $this->assertFalse(__S([1,1,3,3,5])->anyMatch(fn($x) => $x%2 == 0));
    }

    /**
     * @covers \Moteam\Stream\Library\Terminals\CollectTerminal
     * @covers \Moteam\Stream::collect
     */
    public function testCollect() {
        $this->assertEquals([2,4,6,8,10], __S([2,4,6,8,10])->collect());
        $this->assertEquals([2,4,6,8,10], __S([2,4,6,8,10])());
    }

    /**
     * @covers \Moteam\Stream\Library\Terminals\ContainsTerminal
     * @covers \Moteam\Stream::contains
     */
    public function testContains() {
        $this->assertTrue(__S([2,4,6,8,10])->contains(2));
        $this->assertTrue(__S(["a"=>2,"b"=>4,"c"=>6,"d"=>8,"e"=>10])->contains(2));
    }

    /**
     * @covers \Moteam\Stream\Library\Terminals\HasTerminal
     * @covers \Moteam\Stream::has
     */
    public function testHas() {
        $this->assertTrue(__S([2,4,6,8,10])->has(0));
        $this->assertTrue(__S(["a"=>2,"b"=>4,"c"=>6,"d"=>8,"e"=>10])->has("d"));
    }

    /**
     * @covers \Moteam\Stream\Library\Terminals\CountTerminal
     * @covers \Moteam\Stream::count
     */
    public function testCount() {
        $this->assertEquals(5, __S([2,4,6,8,10])->count());
        $this->assertEquals(5, __S(["a"=>2,"b"=>4,"c"=>6,"d"=>8,"e"=>10])->count());
    }

    /**
     * @covers \Moteam\Stream\Library\Terminals\FindFirstTerminal
     * @covers \Moteam\Stream::findFirst
     */
    public function testFindFirst() {
        $this->assertEquals(2, __S([1,2,3,4,5])->findFirst(fn($x) => $x % 2 == 0));
        $this->assertEquals(2, __S(["a"=>2,"b"=>4,"c"=>6,"d"=>8,"e"=>10])->findFirst(fn($x) => $x % 2 == 0));
    }

    /**
     * @covers \Moteam\Stream\Library\Terminals\FindLastTerminal
     * @covers \Moteam\Stream::findLast
     */
    public function testFindLast() {
        $this->assertEquals(4, __S([1,2,3,4,5])->findLast(fn($x) => $x % 2 == 0));
        $this->assertEquals(10, __S(["a"=>2,"b"=>4,"c"=>6,"d"=>8,"e"=>10])->findLast(fn($x) => $x % 2 == 0));
    }

    /**
     * @covers \Moteam\Stream\Library\Terminals\MaxTerminal
     * @covers \Moteam\Stream::max
     */
    public function testMax() {
        $this->assertEquals(5, __S([1,2,3,4,5])->max(fn($a, $b) => $a - $b));
        $this->assertEquals(10, __S(["a"=>2,"b"=>4,"c"=>6,"d"=>8,"e"=>10])->max(fn($a, $b) => $a - $b));
    }

    /**
     * @covers \Moteam\Stream\Library\Terminals\MinTerminal
     * @covers \Moteam\Stream::min
     */
    public function testMin() {
        $this->assertEquals(1, __S([1,2,3,4,5])->min(fn($a, $b) => $a - $b));
        $this->assertEquals(2, __S(["a"=>2,"b"=>4,"c"=>6,"d"=>8,"e"=>10])->min(fn($a, $b) => $a - $b));
    }

    /**
     * @covers \Moteam\Stream\Library\Terminals\ObjectTerminal
     * @covers \Moteam\Stream::object
     */
    public function testObject() {
        $obj = __S(["a" => 1, "b" => 2])->object();
        $this->assertEquals(1, $obj->a);
        $this->assertEquals(2, $obj->b);
    }

    /**
     * @covers \Moteam\Stream\Library\Terminals\RandomTerminal
     * @covers \Moteam\Stream::random
     */
    public function testRandom() {
        $this->assertTrue(in_array(__S([1, 2, 3, 4, 5])->random(), [1, 2, 3, 4, 5]));
    }

    /**
     * @covers \Moteam\Stream\Library\Terminals\ReduceTerminal
     * @covers \Moteam\Stream::reduce
     */
    public function testReduce() {
        $this->assertEquals(15, __S([1, 2, 3, 4, 5])->reduce(fn($a, $x) => $a + $x, 0));
        $this->assertEquals(25, __S(["0" => 1, "1" => 2, "2" => 3, "3" => 4, "4" => 5])->reduce(fn($a, $x, $key) => $a + $x + intval($key), 0));
    }

    /**
     * @covers \Moteam\Stream\Library\Terminals\ShuffledTerminal
     * @covers \Moteam\Stream::shuffled
     */
    public function testShuffled() {
        $in = [1, 2, 3, 4, 5];
        $out = __S($in)->shuffled();
        foreach($out as $v) {
            $this->assertContains($v, $in);
        }
    }
}
