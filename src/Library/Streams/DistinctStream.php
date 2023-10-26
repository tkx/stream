<?php

namespace Moteam\Stream\Library\Streams;

use Moteam\Stream\Library\StreamInterface;
use Moteam\Stream\Stream;

/**
 * Streams unique values from source stream, limit can be set to stream more than 1 occurrence
 * @method StreamInterface distinct(int $limit, bool $preserve_keys = false)
 * 
 * @psalm-api
 */
class DistinctStream extends Stream implements StreamInterface {
    public array $hashMap = [];
    public function stream(): \Iterator {
        [$limit, $preserve_keys] = $this->useParameters(["is_int", 1], ["is_bool", false]);;
        foreach($this->iterator as $key => $value) {
            if(\array_key_exists($value, $this->hashMap)) {
                $this->hashMap[$value] += 1;
            } else {
                $this->hashMap[$value] = 1;
            }
            if($this->hashMap[$value] <= $limit) {
                if(!$preserve_keys) {
                    yield $value;
                } else {
                    yield $key => $value;
                }
            }
        }
    }
}
