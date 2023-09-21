<?php

namespace Stream\Library\Mutators;

use Stream\Stream;

class ForeachStream extends Stream {
    public function stream(): \Iterator {
        if(!is_callable($this->mutator)) {
            throw new \InvalidArgumentException();
        }
        foreach($this->iterator as $key => $value) {
            ($this->mutator)($value, $key);
            yield $key => $value;
        }
    }
}
