<?php declare(strict_types=1);

namespace Moteam\Stream\Tests;

require_once "Data/DataProvider.php";

use PHPUnit\Framework\TestCase;
use function Moteam\Stream\Library\S as __S;

class TerminalsTest extends TestCase {
    /**
     * @covers \Moteam\Stream\Library\Terminals\AllMatchTerminal
     */
    public function testAllMatch() {
        $this->assertTrue(__S([2,4,6,8,10])->allMatch(fn($x) => $x%2 == 0));
    }

    /**
     * @covers \Moteam\Stream\Library\Terminals\AnyMatchTerminal
     */
    public function testAnyMatch() {
        $this->assertTrue(__S([2,4,6,8,10])->anyMatch(fn($x) => $x%2 == 0));
        $this->assertTrue(__S([1,2,3,4,5])->anyMatch(fn($x) => $x%2 == 0));
    }

    /**
     * @covers \Moteam\Stream\Library\Terminals\CollectTerminal
     */
    public function testCollect() {
        $this->assertEquals([2,4,6,8,10], __S([2,4,6,8,10])->collect());
        $this->assertEquals([2,4,6,8,10], __S([2,4,6,8,10])());
    }

    /**
     * @covers \Moteam\Stream\Library\Terminals\ContainsTerminal
     */
    public function testContains() {
        $this->assertTrue(__S([2,4,6,8,10])->contains(2));
    }

    /**
     * @covers \Moteam\Stream\Library\Terminals\CountTerminal
     */
    public function testCount() {
        $this->assertEquals(5, __S([2,4,6,8,10])->count());
    }

    /**
     * @covers \Moteam\Stream\Library\Terminals\FindFirstTerminal
     */
    public function testFindFirst() {
        $this->assertEquals(2, __S([1,2,3,4,5])->findFirst(fn($x) => $x % 2 == 0));
    }

    /**
     * @covers \Moteam\Stream\Library\Terminals\FindLastTerminal
     */
    public function testFindLast() {
        $this->assertEquals(4, __S([1,2,3,4,5])->findLast(fn($x) => $x % 2 == 0));
    }

    /**
     * @covers \Moteam\Stream\Library\Terminals\MaxTerminal
     */
    public function testMax() {
        $this->assertEquals(5, __S([1,2,3,4,5])->max(fn($a, $b) => $a - $b));
    }

    /**
     * @covers \Moteam\Stream\Library\Terminals\MinTerminal
     */
    public function testMin() {
        $this->assertEquals(1, __S([1,2,3,4,5])->min(fn($a, $b) => $a - $b));
    }

    /**
     * @covers \Moteam\Stream\Library\Terminals\ObjectTerminal
     */
    public function testObject() {
        $obj = __S(["a" => 1, "b" => 2])->object();
        $this->assertEquals(1, $obj->a);
        $this->assertEquals(2, $obj->b);
    }

    /**
     * @covers \Moteam\Stream\Library\Terminals\RandomTerminal
     */
    public function testRandom() {
        $this->assertTrue(in_array(__S([1, 2, 3, 4, 5])->random(), [1, 2, 3, 4, 5]));
    }

    /**
     * @covers \Moteam\Stream\Library\Terminals\ReduceTerminal
     */
    public function testReduce() {
        $this->assertEquals(15, __S([1, 2, 3, 4, 5])->reduce(fn($a, $x) => $a + $x, 0));
    }
}
