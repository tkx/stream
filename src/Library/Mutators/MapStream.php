<?php

namespace Moteam\Stream\Library\Mutators;

use Moteam\Stream\Stream;

/**
 * Applies given function to each source stream element, and stream the result
 * @method map(callable $by = fn(mixed $x, mixed $k): mixed => !!$x, bool $preserve_keys = false): Stream
 * 
 * @psalm-api
 */
class MapStream extends Stream {
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
