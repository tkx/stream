<?php

namespace Moteam\Stream\Library\Mutators;

use Moteam\Stream\Stream;

/**
 * Skips N first source elements, and streams the rest
 * @method skip(int $n, bool $preserve_keys = false): Stream
 * 
 * @psalm-api
 */
class SkipStream extends Stream {
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
