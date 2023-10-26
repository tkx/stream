<?php

namespace Moteam\Stream\Library\Streams;

use Moteam\Stream\Library\StreamInterface;
use Moteam\Stream\Stream;

/**
 * Divides source stream into two groups by boolean return of given function, applied to each source stream value.
 * Same as groupBy, but grouper is of boolean return
 * @method StreamInterface partition(callable $by = fn(mixed $x, mixed $k): bool => !!$x)
 * 
 * @psalm-api
 */
class PartitionStream extends Stream implements StreamInterface {
    public function stream(): \Iterator {
        $groups = [[], []];
        $fn = $this->useMutator();
        [$preserve_keys] = $this->useParameters(["is_bool", false]);
        foreach($this->iterator as $key => $value) {
            $key0 = \call_user_func($fn, $value, $key);
            if($key0 === true) {
                if(!$preserve_keys) {
                    $groups[0][] = $value;
                } else {
                    $groups[0][$key] = $value;
                }
            } else {
                if(!$preserve_keys) {
                    $groups[1][] = $value;
                } else {
                    $groups[1][$key] = $value;
                }
            }
        }
        foreach($groups as $key0 => $value0) {
            yield $key0 => $value0;
        }
    }
}
