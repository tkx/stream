<?php

namespace Moteam\Stream\Library\Streams;

use Moteam\Stream\Library\StreamInterface;
use Moteam\Stream\Stream;

/**
 * Streams values of source stream
 * @method StreamInterface values()
 * 
 * @psalm-api
 */
class ValuesStream extends Stream implements StreamInterface {
    public function stream(): \Iterator {
        foreach($this->iterator as $key => $value) {
            yield $value;
        }
    }
}
