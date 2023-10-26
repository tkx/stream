<?php

namespace Moteam\Stream\Library\Streams;

use Moteam\Stream\Library\StreamInterface;
use Moteam\Stream\Stream;

/**
 * Skips N first source elements, and streams the rest
 * @method StreamInterface skip(int $n, bool $preserve_keys = false)
 * 
 * @psalm-api
 */
class SkipStream extends Stream implements StreamInterface {
    public function stream(): \Iterator {
        $i = 0;
        [$n, $preserve_keys] = $this->useParameters(["is_int", null], ["is_bool", false]);
        foreach($this->iterator as $key => $value) {
            if(++$i > $n) {
                if($preserve_keys) {
                    yield $key => $value;
                } else {
                    yield $value;
                }
            }
        }
    }
}
