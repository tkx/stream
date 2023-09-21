<?php

namespace Stream\Library\Mutators;

use Stream\Stream;

class FilterStream extends Stream {
    public function stream(): \Iterator {
        if(!is_callable($this->mutator)) {
            throw new \InvalidArgumentException();
        }
        foreach($this->iterator as $key => $value) {
            if($this->mutator !== null) {
                $temp = ($this->mutator)($value);
            } else {
                $temp = !!$value;
            }
            if($temp !== false) {
                yield $key => $value;
            }
        }
    }
}
