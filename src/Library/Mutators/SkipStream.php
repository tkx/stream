<?php

namespace Stream\Library\Mutators;

use Stream\Stream;

class SkipStream extends Stream {
    public function stream(): \Iterator {
        $i = 0;
        [$n, $preserve_keys] = $this->useParameters(["is_int", null], ["is_bool", false]);
        foreach($this->iterator as $key => $value) {
            if(++$i > $n) {
                if($preserve_keys) {
                    yield $key => $value;
                } else {
                    yield $value;
                }
            }
        }
    }
}
