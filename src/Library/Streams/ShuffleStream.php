<?php

namespace Moteam\Stream\Library\Streams;

use Moteam\Stream\Library\StreamInterface;
use Moteam\Stream\Stream;

/**
 * Shuffles source stream and streams the result
 * @method StreamInterface shuffle()
 * 
 * @psalm-api
 */
class ShuffleStream extends Stream implements StreamInterface {
    public function stream(): \Iterator {
        $data = \iterator_to_array($this->iterator);
        [$preserve_keys] = $this->useParameters(["is_bool", false]);
        $keys = \array_keys($data);
        \shuffle($keys);
        $i = 0;
        for($i = 0; $i < count($keys); $i++) {
            $key = $keys[$i];
            $value = $data[$key];
            if($preserve_keys) {
                yield $key => $value;
            } else {
                yield $value;
            }
        }
    }
}
