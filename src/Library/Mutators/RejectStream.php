<?php

namespace Moteam\Stream\Library\Mutators;

use Moteam\Stream\Stream;

/**
 * Opposite to filter: streams data which is falsy by given function
 * @method reject(callable $by = fn(mixed $x): bool => !!$x, bool $preserve_keys = false): Stream
 * 
 * @psalm-api
 */
class RejectStream extends Stream {
    public function stream(): \Iterator {
        foreach($this->iterator as $key => $value) {
            $temp = call_user_func($this->useMutator(), $value);
            [$preserve_keys] = $this->useParameters(["is_bool", false]);
            if($temp === false) {
                if($preserve_keys) {
                    yield $key => $value;
                } else {
                    yield $value;
                }
            }
        }
    }
}
