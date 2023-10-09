<?php

require "../vendor/autoload.php";
require "../tests/Data/NullStream.php";
require "../tests/Data/ZeroTerminal.php";

use Moteam\Stream\Stream;
use function Moteam\Stream\Library\S;

// chaining
$test = Stream::of([1, 2, 2.1, 3, 3.1, 4, 5])
    ->filter(fn($x) => $x != 0)
    ->map(fn($x) => $x * 2)
    ->groupBy(fn($x) => (int)floor($x))
    ->collect();
print_r(["chaining", $test]);

// extending - see ../tests/Data
$test = Stream::of([1,2,3,4,5])->null()->zero();
print_r(["extending", $test]);

// complex
$range = range(0, 10);
$dupes = (function() {
   foreach(range(0, 10) as $i) {
       yield $i;
       yield $i;
   }
})();
$test = Stream::of($dupes)
    ->filter(fn($x) => $x != 0)
    ->distinct()
    ->map(fn($x) => pow($x, 2))
    ->skip(5)
    ->concat([12, 14, 16])
    ->distinct()
    // collect intermediate results
    ->foreach(function($value) use(&$data) {
        $data[] = $value;
    })
    ->map(fn($x) => $x * 2)
    // collect intermediate results
    ->forall(function($values) use(&$data2) {
        $data2 = $values;
    })
    ->reduce(fn($a, $v) => $a + $v, 0);
print_r(["complex", $data, $data2, $test]);

// objects
$test = S([
   new class(0) { public int $v; public function __construct($v) { $this->v = $v; } },
   new class(1) { public int $v; public function __construct($v) { $this->v = $v; } },
   new class(2) { public int $v; public function __construct($v) { $this->v = $v; } },
   new class(3) { public int $v; public function __construct($v) { $this->v = $v; } },
   new class(4) { public int $v; public function __construct($v) { $this->v = $v; } },
   new class(5) { public int $v; public function __construct($v) { $this->v = $v; } },
])
->map(fn($x) => $x->v)
->filter(fn($v) => !!$v)
();
print_r(["objects", $test]);

// objects 2
class Klass {
    public ?stdClass $x = null;
    public function __construct($v) {
        if($v !== null) {
            $this->x = new stdClass;
            $this->x->v = $v;
        }
    }
}
$test = S([new Klass(0), new Klass(1), new Klass(2), new Klass(3), new Klass(null)])
    ->filter(fn($o) => !!$o->x)
    ->map(fn($o) => $o->x)
    ->filter(fn($x) => $x->v != 0)
    ->map(fn($x) => $x->v)
    ()
;
print_r(["objects2", $test]);

// object3
$klass = new stdClass;
$klass->x1 = 1;
$klass->x2 = 2;
$klass->x3 = "test";
$test = S($klass);
print_r(["object3", $test->keys()()]);