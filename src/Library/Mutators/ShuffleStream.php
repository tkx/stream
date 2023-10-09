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
