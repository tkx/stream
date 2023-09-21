<?php declare(strict_types=1);

namespace Stream\Tests\Data;

require_once "MyIterator.php";
use Stream\Stream as S;

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

    public static function distinct(): array {
        return [
            [[1, 2, 3, 4, 5], [1, 1, 2, 2, 3, 3, 4, 4, 5, 5]],
        ];
    }

    public static function concat(): array {
        return [
            [[1, 2, 3, 4, 5], [1, 2, 3], [4, 5]],
            [["1", "2", "3", "4", "5"], ["1", "2", "3"], ["4", "5"]],
        ];
    }
}
