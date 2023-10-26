<?php

namespace Moteam\Stream\Library\Streams;

use Moteam\Stream\Library\StreamInterface;
use Moteam\Stream\Stream;

/**
 * Opposite to filter: streams data which is falsy by given function
 * @method StreamInterface reject(callable $by = fn(mixed $x, mixed $k): bool => !!$x, bool $preserve_keys = false)
 * 
 * @psalm-api
 */
class RejectStream extends Stream implements StreamInterface {
    public function stream(): \Iterator {
        $fn = $this->useMutator();
        foreach($this->iterator as $key => $value) {
            $temp = \call_user_func($fn, $value, $key);
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
