<?php

namespace Moteam\Stream\Library\Streams;

use Moteam\Stream\Library\StreamInterface;
use Moteam\Stream\Stream;

/**
 * Streams N random values from source stream
 * @method StreamInterface randomN(int $n = 1)
 * 
 * @psalm-api
 */
class RandomNStream extends Stream implements StreamInterface {
    public function stream(): \Iterator {
        $data = \iterator_to_array($this->iterator);
        [$n, $preserve_keys] = $this->useParameters(["is_int", 1], ["is_bool", false]);
        $keys = \array_keys($data);
        \shuffle($keys);
        $i = 0;
        for($i = 0; $i < $n; $i++) {
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
