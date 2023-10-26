<?php

namespace Moteam\Stream\Library\Streams;

use Moteam\Stream\Library\StreamInterface;
use Moteam\Stream\Stream;

/**
 * Counts and groups values returned by input function, applied to each stream element; then streams the result
 * @method StreamInterface countBy(callable $by = fn(mixed $x, mixed $k): mixed => !!$x)
 * 
 * @psalm-api
 */
class CountByStream extends Stream implements StreamInterface {
    public function stream(): \Iterator {
        $mutator = $this->useMutator();
        $groups = [];
        foreach($this->iterator as $key => $value) {
            $key0 = \call_user_func($mutator, $value, $key);
            if(!\is_int($key0) && !\is_string($key0)) {
                throw new \BadFunctionCallException();
            }
            if(!\array_key_exists($key0, $groups)) {
                $groups[$key0] = 1;
            } else {
                $groups[$key0]++;
            }
        }
        foreach($groups as $key0 => $value0) {
            yield $key0 => $value0;
        }
    }
}
