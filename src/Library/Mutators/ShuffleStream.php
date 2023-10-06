<?php

namespace Moteam\Stream\Library\Mutators;

use Moteam\Stream\Stream;

/**
 * Shuffles source stream and streams the result
 * @method shuffle(): Stream
 * 
 * @psalm-api
 */
class ShuffleStream extends Stream {
    public function stream(): \Iterator {
        $data = iterator_to_array($this->iterator);
        shuffle($data);
        foreach($data as $datum) {
            yield $datum;
        }
    }
}
