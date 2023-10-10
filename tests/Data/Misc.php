<?php

namespace Moteam\Stream\Tests\Data;

class Func {
    public static function f0($v, $k) {
        return $v * $k;
    }
    public function f1($v, $k) {
        return $v * $k;
    }
    public function f2($x, $k) {
        return $x != 0 && $k != 0;
    }
    public static function f3($x, $k) {
        return $x != 0 && $k != 0;
    }
}

function f2($v, $k) {
    return $v * $k;
}

function f3($x, $k) {
    return $x != 0 && $k != 0;
}