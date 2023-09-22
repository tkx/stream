<?php

namespace Stream\Library\Mutators;

use Stream\Stream;

class KeysStream extends Stream {
    public function stream(): \Iterator {
        foreach($this->iterator as $key => $value) {
            yield $key;
        }
    }
}
