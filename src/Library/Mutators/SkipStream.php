<?php

namespace Stream\Library\Mutators;

use Stream\Stream;

class SkipStream extends Stream {
    public function stream(): \Iterator {
        if(!is_int($this->parameters[0])) {
            throw new \InvalidArgumentException();
        }
        $i = 0;
        foreach($this->iterator as $key => $value) {
            if(++$i > $this->parameters[0]) {
                yield $key => $value;
            }
        }
    }
}
