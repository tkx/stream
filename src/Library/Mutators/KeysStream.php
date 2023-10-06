<?php

namespace Moteam\Stream\Library\Mutators;

use Moteam\Stream\Stream;

/**
 * Streams keys of source stream
 * @method keys(): Stream
 * 
 * @psalm-api
 */
class KeysStream extends Stream {
    public function stream(): \Iterator {
        foreach($this->iterator as $key => $value) {
            yield $key;
        }
    }
}
