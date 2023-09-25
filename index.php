<?php
require "vendor/autoload.php";
require "tests/Data/NullStream.php";
require "tests/Data/ZeroTerminal.php";

use Moteam\Stream\Stream;
use function Moteam\Stream\Library\S;
use Moteam\Stream\Library\Mutators\NullStream;
use Moteam\Stream\Library\Terminals\ZeroTerminal;

//print_r([is_callable("empty")]);

//    ->filter(fn($x) => $x != 0)
//    ->map(fn($x) => $x * 2)
//    ->limit(5)
//    ->skip(5)
//->groupBy(fn($x) => (int)floor($x))
//();
//print_r($test);

$test = Stream::of([1,2,3,4,5])->null()->zero();
print_r($test);



//foreach($test->stream() as $v) {
//    print "{$v}..";
//}
//
//$range = range(0, 10);
//$dupes = (function() {
//    foreach(range(0, 10) as $i) {
//        yield $i;
//        yield $i;
//    }
//})();
//
//$data = [];
//
//print_r(
//    Stream::of($dupes)
//        ->filter(fn($x) => $x != 0)
//        ->distinct()
//        ->map(fn($x) => $x * 2)
//        ->map(fn($x) => pow($x, 2))
//        ->skip(5)
//        ->concat($test())
//        ->concat([12, 14, 16])
//        ->distinct()
//        ->foreach(function($value) use(&$data) {
//            $data[] = $value;
//        })
//    ->reduce(fn($a, $v) => $a + $v, 0)
////    ()
//);
//
//print_r(["fe", $data]);
//
//$test = Stream::of([
//    new class(1) { public int $v; public function __construct($v) { $this->v = $v; } },
//    new class(2) { public int $v; public function __construct($v) { $this->v = $v; } },
//    new class(3) { public int $v; public function __construct($v) { $this->v = $v; } },
//    new class(4) { public int $v; public function __construct($v) { $this->v = $v; } },
//    new class(5) { public int $v; public function __construct($v) { $this->v = $v; } },
//])->map(fn($x) => $x->v)->collect();
//print_r($test);

//print_r(
//    Stream::of([1,2,3])->concat([4,5])()
//);
//
//[$a, $b, $c] = null;
//
//print_r([$a, $b, $c]);
//print_r(array_key_exists("a", [1,2,3]));
