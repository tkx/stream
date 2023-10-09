<?php

namespace Moteam\Stream\Library\Mutators;

use Moteam\Stream\Stream;

/**
 * Groups source stream by values returned by input function and streams the result
 * @method groupBy(callable $by = fn(mixed $x): mixed => !!$x, bool $preserve_keys = false): Stream
 * 
 * @psalm-api
 */
class GroupByStream extends Stream {
    public function stream(): \Iterator {
        $mutator = $this->useMutator();
        $groups = [];
        [$preserve_keys] = $this->useParameters(["is_bool", false]);
        foreach($this->iterator as $key => $value) {
            $key0 = \call_user_func($mutator, $value, $key);
            if(!\is_int($key0) && !\is_string($key0)) {
                throw new \BadFunctionCallException();
            }
            if(!\array_key_exists($key0, $groups)) {
                if(!$preserve_keys) {
                    $groups[$key0] = [$value];
                } else {
                    $groups[$key0] = [$key => $value];
                }
            } else {
                if(!$preserve_keys) {
                    $groups[$key0][] = $value;
                } else {
                    $groups[$key0][$key] = $value;
                }
            }
        }
        foreach($groups as $key0 => $value0) {
            yield $key0 => $value0;
        }
    }
}
