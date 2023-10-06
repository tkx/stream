<?php

namespace Moteam\Stream\Library\Mutators;

use Moteam\Stream\Stream;

/**
 * Streams data filtered by input function
 * @method filter(callable $by = fn(mixed $x): bool => !!$x, bool $preserve_keys = false): Stream
 * 
 * @psalm-api
 */
class FilterStream extends Stream {
    public function stream(): \Iterator {
        $mutator = $this->useMutator(true);
        [$preserve_keys] = $this->useParameters(["is_bool", false]);
        foreach($this->iterator as $key => $value) {
            if($this->mutator !== null) {
                $temp = call_user_func($mutator, $value);
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
