<?php

namespace Stream\Library\Mutators;

use Stream\Stream;

class MapStream extends Stream {
    public function stream(): \Iterator {
        if(!is_callable($this->mutator)) {
            throw new \InvalidArgumentException();
        }
        foreach($this->iterator as $key => $value) {
            yield $key => ($this->mutator)($value);
        }
    }
}
