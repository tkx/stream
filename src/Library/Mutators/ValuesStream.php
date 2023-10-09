<?php

namespace Moteam\Stream\Library\Mutators;

use Moteam\Stream\Stream;

/**
 * Streams values of source stream
 * @method values(): Stream
 * 
 * @psalm-api
 */
class ValuesStream extends Stream {
    public function stream(): \Iterator {
        foreach($this->iterator as $key => $value) {
            yield $value;
        }
    }
}
