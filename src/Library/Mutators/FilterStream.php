<?php

namespace Stream\Library\Mutators;

use Stream\Stream;

class FilterStream extends Stream {
    public function stream(): \Iterator {
        $mutator = $this->useMutator();
        [$preserve_keys] = $this->useParameters(["is_bool", false]);
        foreach($this->iterator as $key => $value) {
            if($this->mutator !== null) {
                $temp = ($mutator)($value);
            } else {
                $temp = !!$value;
            }
            if($temp !== false) {
                if(!$preserve_keys) {
                    yield $value;
                } else {
                    yield $key => $value;
                }
            }
        }
    }
}
