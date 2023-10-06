<?php

namespace Moteam\Stream\Library\Mutators;

use Moteam\Stream\Stream;

/**
 * Divides source stream into two groups by boolean return of given function, applied to each source stream value.
 * Same as groupBy, but grouper is of boolean return
 * @method partition(callable $by = fn(mixed $x): bool => !!$x): Stream
 * 
 * @psalm-api
 */
class PartitionStream extends Stream {
    public function stream(): \Iterator {
        $groups = [[], []];
        foreach($this->iterator as $key => $value) {
            $key0 = call_user_func($this->useMutator(), $value);
            if($key0 === true) {
                $groups[0][] = $value;
            } else {
                $groups[1][] = $value;
            }
        }
        foreach($groups as $key0 => $value0) {
            yield $key0 => $value0;
        }
    }
}
