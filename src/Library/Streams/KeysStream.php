<?php

namespace Moteam\Stream\Library\Streams;

use Moteam\Stream\Library\StreamInterface;
use Moteam\Stream\Stream;

/**
 * Streams keys of source stream
 * @method StreamInterface keys()
 * 
 * @psalm-api
 */
class KeysStream extends Stream implements StreamInterface {
    public function stream(): \Iterator {
        foreach($this->iterator as $key => $value) {
            yield $key;
        }
    }
}
