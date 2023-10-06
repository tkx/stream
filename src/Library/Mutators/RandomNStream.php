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
        $data = iterator_to_array($this->iterator);
        shuffle($data);
        [$n] = $this->useParameters(["is_int", 1]);
        for($i = 0; $i < $n; $i++) {
            yield $data[$i];
        }
    }
}
