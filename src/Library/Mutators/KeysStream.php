<?php

namespace Moteam\Stream\Library\Mutators;

use Moteam\Stream\Stream;

class KeysStream extends Stream {
    public function stream(): \Iterator {
        foreach($this->iterator as $key => $value) {
            yield $key;
        }
    }
}
