<?php

namespace Moteam\Stream\Library\Mutators;

use Moteam\Stream\Stream;

/**
 * Streams N random values from source stream
 * @method randomN(int $n = 1): Stream
 * 
 * @psalm-api
 */
class RandomNStream extends Stream {
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
