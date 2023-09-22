<?php

namespace Stream\Library\Mutators;

use Stream\Stream;

class RejectStream extends Stream {
    public function stream(): \Iterator {
        foreach($this->iterator as $key => $value) {
            $temp = ($this->useMutator())($value);
            [$preserve_keys] = $this->useParameters(["is_bool", false]);
            if($temp === false) {
                if($preserve_keys) {
                    yield $key => $value;
                } else {
                    yield $value;
                }
            }
        }
    }
}
