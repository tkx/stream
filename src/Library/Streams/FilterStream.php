<?php

namespace Moteam\Stream\Library\Streams;

use Moteam\Stream\Library\StreamInterface;
use Moteam\Stream\Stream;

/**
 * Streams data filtered by input function
 * @method StreamInterface filter(callable $by = fn(mixed $x, mixed $k): bool => !!$x, bool $preserve_keys = false)
 * 
 * @psalm-api
 */
class FilterStream extends Stream implements StreamInterface {
    public function stream(): \Iterator {
        $mutator = $this->useMutator(true);
        [$preserve_keys] = $this->useParameters(["is_bool", false]);
        foreach($this->iterator as $key => $value) {
            if($this->mutator !== null) {
                $temp = \call_user_func($mutator, $value, $key);
            } else {
                $temp = !!$value;
            }
            if($temp !== false) {
                if(!$preserve_keys) {
                    yield $value;
                } else {
                    yield $key => $value;
                }
            }
        }
    }
}
