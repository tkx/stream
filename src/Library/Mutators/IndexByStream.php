<?php

namespace Moteam\Stream\Library\Mutators;

use Moteam\Stream\Stream;

/**
 * Indexes source stream by given key, then streams the result
 * @method indexBy(string|int $x): Stream
 * 
 * @psalm-api
 */
class IndexByStream extends Stream {
    public function stream(): \Iterator {
        $groups = [];
        [$name] = $this->useParameters(["is_scalar", null]);
        foreach($this->iterator as $key => $value) {
            if(\is_array($value)) {
                $key0 = $value[$name];
            } else if(\is_object($value)) {
                $key0 = $value->{$name};
            } else if(\is_scalar($value)) {
                $key0 = $value;
            } else {
                $key0 = null;
            }
            if($key0 === null) {
                continue;
            }
            if(!array_key_exists($key0, $groups)) {
                $groups[$key0] = $value;
            }
//            else {
//                $groups[$key0][] = $value;
//            }
        }
        foreach($groups as $key0 => $value0) {
            yield $key0 => $value0;
        }
    }
}
