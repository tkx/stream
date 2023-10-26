<?php

namespace Moteam\Stream\Library\Streams;

use Moteam\Stream\Library\StreamInterface;
use Moteam\Stream\Stream;

/**
 * Applies given function to each source stream element, and stream the result
 * @method StreamInterface map(callable $by = fn(mixed $x, mixed $k): mixed => !!$x, bool $preserve_keys = false)
 * 
 * @psalm-api
 */
class MapStream extends Stream implements StreamInterface {
    public function stream(): \Iterator {
        $mutator = $this->useMutator();
        [$preserve_keys] = $this->useParameters(["is_bool", false]);
        foreach($this->iterator as $key => $value) {
            if($preserve_keys) {
                yield $key => \call_user_func($mutator, $value, $key);
            } else {
                yield \call_user_func($mutator, $value, $key);
            }
        }
    }
}
