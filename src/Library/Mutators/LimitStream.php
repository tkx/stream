<?php

namespace Moteam\Stream\Library\Mutators;

use Moteam\Stream\Stream;

class LimitStream extends Stream {
    public function stream(): \Iterator {
        $i = 0;
        [$limit, $preserve_keys] = $this->useParameters(["is_int", null], ["is_bool", false]);
        foreach($this->iterator as $key => $value) {
            if(++$i <= $limit) {
                if($preserve_keys) {
                    yield $key => $value;
                } else {
                    yield $value;
                }
            } else {
                break;
            }
        }
    }
}
