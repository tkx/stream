<?php declare(strict_types=1);

namespace Moteam\Stream\Tests\Data;

require_once "MyIterator.php";
use Moteam\Stream\Stream as S;

class DataProvider {
    /**
     * @return array [expected, input]
     */
    public static function create(): array {
        return [
            "array" => [[1, 2, 3, 4, 5], [1, 2, 3, 4, 5]],
            "array of strings" => [["1", "2", "3", "4", "5"], ["1", "2", "3", "4", "5"]],
            "range" => [range(1, 5), range(1, 5)],
            "generator" => [range(1, 5), (function() { yield from range(1, 5); })()],
            "ArrayIterator" => [range(1, 5), new \ArrayIterator(range(1, 5))],
            "Iterator" => [range(1, 5), new MyIterator(range(1, 5))],
            "Stream" => [range(1, 5), S::of(range(1, 5))],
        ];
    }

    /**
     * @return array [expected, input, function]
     */
    public static function map(): array {
        return [
            "array" => [[2, 4, 6, 8, 10], [1, 2, 3, 4, 5], fn($x) => $x * 2],
            "nested array" => [[1, 2, 3, 4, 5], [[1, 1], [2, 2], [3, 3], [4, 4], [5, 5]], fn(array $x) => $x[0]],
            "object" => [
                range(1, 5),
                [
                    new class(1) { public int $v; public function __construct($v) { $this->v = $v; } },
                    new class(2) { public int $v; public function __construct($v) { $this->v = $v; } },
                    new class(3) { public int $v; public function __construct($v) { $this->v = $v; } },
                    new class(4) { public int $v; public function __construct($v) { $this->v = $v; } },
                    new class(5) { public int $v; public function __construct($v) { $this->v = $v; } },
                ],
                fn($x) => $x->v
            ]
        ];
    }

    /**
     * @return \int[][][] [expected, input]
     */
    public static function distinct(): array {
        return [
            [[1, 2, 3, 4, 5], [1, 1, 2, 2, 3, 3, 4, 4, 5, 5]],
        ];
    }

    /**
     * @return array [expected, input, function]
     */
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

    /**
     * @return array [expected, input, function]
     */
    public static function filter(): array {
        return [
            [
                [1, 2, 3, 4, 5],
                [1, 0, 2, 0, 3, 0, 4, 0, 5, 0],
                fn($x) => $x != 0
            ]
        ];
    }

    /**
     * @return array [expected, input, function]
     */
    public static function reject(): array {
        return [
            [
                [0, 0, 0, 0, 0],
                [1, 0, 2, 0, 3, 0, 4, 0, 5, 0],
                fn($x) => $x != 0
            ]
        ];
    }

    /**
     * @return array
     */
    public static function concat(): array {
        return [
            [[1, 2, 3, 4, 5], [1, 2, 3], [4, 5]],
            [["1", "2", "3", "4", "5"], ["1", "2", "3"], ["4", "5"]],
        ];
    }

    /**
     * @return array
     */
    public static function limit(): array {
        return [
            [[1, 2, 3], [1, 2, 3, 4, 5], 3],
        ];
    }

    /**
     * @return array
     */
    public static function skip(): array {
        return [
            [[4, 5], [1, 2, 3, 4, 5], 3],
        ];
    }

    /**
     * @return array
     */
    public static function sorted(): array {
        return [
            [[1, 2, 3, 4, 5], [5, 4, 3, 2, 1], fn($a, $b) => $a - $b],
        ];
    }

    /**
     * @return array
     */
    public static function countBy(): array {
        return [
            [["odd" => 3, "even" => 2], [1, 2, 3, 4, 5], fn($x) => $x % 2 == 0 ? "even" : "odd"],
        ];
    }

    /**
     * @return array
     */
    public static function groupBy(): array {
        return [
            [[1 => [1, 1.5], 2 => [2, 2.5], 3 => [3]], [1, 1.5, 2, 2.5, 3], fn($x) => (int)floor($x)],
        ];
    }

    /**
     * @return array
     */
    public static function keys(): array {
        return [
            [["a", "b", "c"], ["a" => 0, "b" => 0, "c" => 0]],
            [[0, 1, 2, 3, 4], [1, 2, 3, 4, 5]],
        ];
    }

    /**
     * @return array
     */
    public static function partition(): array {
        return [
            [[[1, 3, 5], [2, 4]], [1, 2, 3, 4, 5], fn($x) => $x % 2 != 0],
        ];
    }

    /**
     * @return array
     */
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
