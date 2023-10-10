<?php declare(strict_types=1);

namespace Moteam\Stream\Tests\Data;

require_once "MyIterator.php";
require_once "Misc.php";
use Moteam\Stream\Stream as S;
use Moteam\Stream\Tests\Data\Func;

class DataProvider {
    public static function create(): array {

        $objects = [new \stdClass(), new \stdClass(), new \stdClass()];
        $objects = array_map(function($o) {
            $o->prop = 1;
            return $o;
        }, $objects);

        $klass = new \stdClass;
        $klass->v1 = 1;
        $klass->v2 = 2;
        $klass->v3 = 3;
        $klass->v4 = 4;
        $klass->v5 = 5;

        return [
            [$objects, $objects],
            [[1, 2, 3, 4, 5], [1, 2, 3, 4, 5]],
            [[1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5], [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5]],
            [["1", "2", "3", "4", "5"], ["1", "2", "3", "4", "5"]],
            [["a" => "1", "b" => "2", "c" => "3", "d" => "4", "e" => "5"], ["a" => "1", "b" => "2", "c" => "3", "d" => "4", "e" => "5"]],
            [range(1, 5), range(1, 5)],
            [range(1, 5), (function() { yield from range(1, 5); })()],
            [range(1, 5), new \ArrayIterator(range(1, 5))],
            [range(1, 5), new MyIterator(range(1, 5))],
            [range(1, 5), S::of(range(1, 5))],
            [["v1" => 1, "v2" => 2, "v3" => 3, "v4" => 4, "v5" => 5], $klass],
        ];
    }

    public static function map(): array {
        return [
            [[2, 4, 6, 8, 10], [1 => "a", 2 => "b", 3 => "c", 4 => "d", 5 => "e"], fn($x, $k) => $k * 2, false],
            [[2, 4, 6, 8, 10], [1, 2, 3, 4, 5], fn($x) => $x * 2, false],
            [[1, 2, 3, 4, 5], [[1, 1], [2, 2], [3, 3], [4, 4], [5, 5]], fn(array $x) => $x[0], false],
            [
                range(1, 5),
                [
                    new class(1) { public int $v; public function __construct($v) { $this->v = $v; } },
                    new class(2) { public int $v; public function __construct($v) { $this->v = $v; } },
                    new class(3) { public int $v; public function __construct($v) { $this->v = $v; } },
                    new class(4) { public int $v; public function __construct($v) { $this->v = $v; } },
                    new class(5) { public int $v; public function __construct($v) { $this->v = $v; } },
                ],
                fn($x) => $x->v,
                false
            ],
            [
                ["a" => "2a", "b" => "4b", "c" => "6c", "d" => "8d", "e" => "10e"], 
                ["a" => 1, "b" => 2, "c" => 3, "d" => 4, "e" => 5], 
                fn($x, $k) => ("" . ($x * 2) . $k),
                true
            ],
            [
                [0, 2, 6, 12, 20],
                [1, 2, 3, 4, 5],
                [new Func(), "f1"],
                false,
            ],
            [
                [0, 2, 6, 12, 20],
                [1, 2, 3, 4, 5],
                [Func::class, "f0"],
                false,
            ],
            [
                [0, 2, 6, 12, 20],
                [1, 2, 3, 4, 5],
                "\\Moteam\\Stream\\Tests\\Data\\f2",
                false,
            ],
        ];
    }

    public static function distinct(): array {
        return [
            [[1, 2, 3, 4, 5], [1, 1, 2, 2, 3, 3, 4, 4, 5, 5]],
        ];
    }

    public static function enrich(): array {
        return [
            [
                ["a" => 1, "b" => 2, "c" => 3],
                ["a" => 1, "b" => 2],
                function($src) {
                    $src["c"] = $src["a"] + $src["b"];
                    return $src;
                }
            ]
        ];
    }

    public static function filter(): array {
        return [
            [
                ["a" => 1, "b" => 2, "c" => 3, "d" => 4, "e" => 5],
                ["a" => 1, "a0" => 0, "b" => 2, "b0" => 0, "c" => 3, "c0" => 0, "d" => 4, "d0" => 0, "e" => 5, "e0" => 0],
                fn($x) => $x != 0,
                true,
            ],
            [
                [1, 2, 3, 4, 5],
                ["a" => 1, "a0" => 0, "b" => 2, "b0" => 0, "c" => 3, "c0" => 0, "d" => 4, "d0" => 0, "e" => 5, "e0" => 0],
                fn($x) => $x != 0,
                false,
            ],
            [
                [1, 2, 3, 4, 5],
                [1, 0, 2, 0, 3, 0, 4, 0, 5, 0],
                fn($x) => $x != 0,
                false,
            ],
            [
                [2, 3, 4, 5],
                [1, 0, 2, 0, 3, 0, 4, 0, 5, 0],
                fn($x, $k) => $x != 0 && $k != 0,
                false,
            ],
            [
                [2, 3, 4, 5],
                [1, 0, 2, 0, 3, 0, 4, 0, 5, 0],
                "\\Moteam\\Stream\\Tests\\Data\\f3",
                false,
            ],
            [
                [2, 3, 4, 5],
                [1, 0, 2, 0, 3, 0, 4, 0, 5, 0],
                [Func::class, "f3"],
                false,
            ],
            [
                [2, 3, 4, 5],
                [1, 0, 2, 0, 3, 0, 4, 0, 5, 0],
                [new Func(), "f2"],
                false,
            ],
        ];
    }

    public static function reject(): array {
        return [
            [
                ["a0" => 0, "b0" => 0, "c0" => 0, "d0" => 0, "e0" => 0],
                ["a" => 1, "a0" => 0, "b" => 2, "b0" => 0, "c" => 3, "c0" => 0, "d" => 4, "d0" => 0, "e" => 5, "e0" => 0],
                fn($x) => $x != 0,
                true,
            ],
            [
                [0, 0, 0, 0, 0],
                ["a" => 1, "a0" => 0, "b" => 2, "b0" => 0, "c" => 3, "c0" => 0, "d" => 4, "d0" => 0, "e" => 5, "e0" => 0],
                fn($x) => $x != 0,
                false,
            ],
            [
                [0, 0, 0, 0, 0],
                [1, 0, 2, 0, 3, 0, 4, 0, 5, 0],
                fn($x) => $x != 0,
                false,
            ],
            [
                [1, 0, 0, 0, 0, 0],
                [1, 0, 2, 0, 3, 0, 4, 0, 5, 0],
                fn($x, $k) => $x != 0 && $k != 0,
                false,
            ],
        ];
    }

    public static function concat(): array {
        return [
            [[1, 2, 3, 4, 5], [1, 2, 3], [4, 5], false],
            [["1", "2", "3", "4", "5"], ["1", "2", "3"], ["4", "5"], false],
            [["a" => 1, "b" => 2, "c" => 3, "d" => 4, "e" => 5], ["a" => 1, "b" => 2, "c" => 3], ["d" => 4, "e" => 5], true],
        ];
    }
    
    public static function concatBefore(): array {
        return [
            [[1, 2, 3, 4, 5], [4, 5], [1, 2, 3], false],
            [["1", "2", "3", "4", "5"], ["4", "5"], ["1", "2", "3"], false],
            [["a" => 1, "b" => 2, "c" => 3, "d" => 4, "e" => 5], ["d" => 4, "e" => 5], ["a" => 1, "b" => 2, "c" => 3], true],
        ];
    }

    public static function limit(): array {
        return [
            [[1, 2, 3], [1, 2, 3, 4, 5], 3, false],
            [["a" => 1, "b" => 2, "c" => 3], ["a" => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5], 3, true],
            [[1, 2, 3], ["a" => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5], 3, false],
        ];
    }

    public static function skip(): array {
        return [
            [[4, 5], [1, 2, 3, 4, 5], 3, false],
            [["d" => 4, 'e' => 5], ["a" => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5], 3, true],
            [[4, 5], ["a" => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5], 3, false],
        ];
    }

    public static function sorted(): array {
        return [
            [[1, 2, 3, 4, 5], [5, 4, 3, 2, 1], fn($a, $b) => $a - $b, false, false],
            [[5, 4, 3, 2, 1], [5, 4, 3, 2, 1], fn($a, $b) => $a - $b, true, false],
            [[1, 2, 3, 4, 5], ["e" => 5, "d" => 4, "c" => 3, "b" => 2, "a" => 1], fn($a, $b) => ord($a) - ord($b), true, false],
            [[0 => 1, 1 => 2, 2 => 3, 3 => 4, 4 => 5], [4 => 5, 3 => 4, 2 => 3, 1 => 2, 0 => 1], fn($a, $b) => $a - $b, false, false],

            [["a" => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5], ["e" => 5, "d" => 4, "c" => 3, "b" => 2, "a" => 1], fn($a, $b) => $a - $b, false, true],
            [[5, 4, 3, 2, 1], [5, 4, 3, 2, 1], fn($a, $b) => $a - $b, true, true],
            [["a" => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5], ["e" => 5, "d" => 4, "c" => 3, "b" => 2, "a" => 1], fn($a, $b) => ord($a) - ord($b), true, true],
            [[0 => 1, 1 => 2, 2 => 3, 3 => 4, 4 => 5], [4 => 5, 3 => 4, 2 => 3, 1 => 2, 0 => 1], fn($a, $b) => $a - $b, false, true],
        ];
    }

    public static function countBy(): array {
        return [
            [["odd" => 3, "even" => 2], [1, 2, 3, 4, 5], fn($x) => $x % 2 == 0 ? "even" : "odd"],
            [["odd" => 3, "even" => 2], [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5], fn($x) => $x % 2 == 0 ? "even" : "odd"],
            [["abc" => 3, "de" => 2], ["a" => 1, "b" => 2, "c" => 3, "d" => 4, "e" => 5], fn($v, $k) => in_array($k, ["a", "b", "c"]) ? "abc" : "de"],
        ];
    }

    public static function groupBy(): array {
        return [
            [[1 => [1, 1.5], 2 => [2, 2.5], 3 => [3]], [1, 1.5, 2, 2.5, 3], fn($x) => (int)floor($x), false],
            [[0 => [1], 1 => [1.5], 4 => [2], 6 => [2.5], 12 => [3]], [1, 1.5, 2, 2.5, 3], fn($x, $k) => (int)floor($x) * $k, false],
            
            [[1 => ["a0" => 1, "a1" => 1.5], 2 => ["b0" => 2, "b1" => 2.5], 3 => ["c0" => 3]], ["a0" => 1, "a1" => 1.5, "b0" => 2, "b1" => 2.5, "c0" => 3], fn($x) => (int)floor($x), true],
        ];
    }

    public static function keys(): array {
        return [
            [["a", "b", "c"], ["a" => 0, "b" => 0, "c" => 0]],
            [[0, 1, 2, 3, 4], [1, 2, 3, 4, 5]],
        ];
    }

    public static function values(): array {
        return [
            [[0, 0, 0], ["a" => 0, "b" => 0, "c" => 0]],
            [[1, 2, 3, 4, 5], [1, 2, 3, 4, 5]],
        ];
    }

    public static function partition(): array {
        return [
            [[["a" => 1, "c" => 3, "e" => 5], ["b" => 2, "d" => 4]], ["a" => 1, "b" => 2, "c" => 3, "d" => 4, "e" => 5], fn($x) => $x % 2 != 0, true],
            [[[1, 3, 5], [2, 4]], [1, 2, 3, 4, 5], fn($x) => $x % 2 != 0, false],
            [[[1, 3, 5], [2, 4]], [1, 2, 3, 4, 5], fn($x, $k) => $k % 2 == 0, false],
        ];
    }

    public static function indexBy(): array {
        return [
            [
                [1 => ["x" => 1], 2 => ["x" => 2], 3 => ["x" => 3]],
                [["x" => 1], ["x" => 2], ["x" => 3]],
                "x"
            ],
//            [
//                [
//                    1 => new class(1) { public int $v; public function __construct($v) { $this->v = $v; } },
//                    2 => new class(2) { public int $v; public function __construct($v) { $this->v = $v; } },
//                    3 => new class(3) { public int $v; public function __construct($v) { $this->v = $v; } }
//                ],
//                [
//                    new class(1) { public int $v; public function __construct($v) { $this->v = $v; } },
//                    new class(2) { public int $v; public function __construct($v) { $this->v = $v; } },
//                    new class(3) { public int $v; public function __construct($v) { $this->v = $v; } },
//                ],
//                "v"
//            ],
        ];
    }
}
