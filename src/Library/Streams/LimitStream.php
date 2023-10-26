<?php

namespace Moteam\Stream\Library\Streams;

use Moteam\Stream\Library\StreamInterface;
use Moteam\Stream\Stream;

/**
 * Streams first N values of source stream
 * @method StreamInterface limit(int $n, bool $preserve_keys = false)
 * 
 * @psalm-api
 */
class LimitStream extends Stream implements StreamInterface {
    public function stream(): \Iterator {
        $i = 0;
        [$limit, $preserve_keys] = $this->useParameters(["is_int", null], ["is_bool", false]);
        foreach($this->iterator as $key => $value) {
            if(++$i <= $limit) {
                if($preserve_keys) {
                    yield $key => $value;
                } else {
                    yield $value;
                }
            } else {
                break;
            }
        }
    }
}
