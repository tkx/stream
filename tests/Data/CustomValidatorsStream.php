<?php

namespace Moteam\Stream\Library\Mutators;

use Moteam\Stream\Stream;

class CustomValidatorsStream extends Stream {
    public function stream(): \Iterator {
        [$default, $preserve_keys] = $this->useParameters(
            [["is_int", fn($x) => $x != 0, fn($x) => $x % 2 == 0], null],
            ["is_bool", false]
        );
        foreach($this->iterator as $key => $value) {
            if(!$preserve_keys) {
                yield $default;
            } else {
                yield $key => $default;
            }
        }
    }
}
