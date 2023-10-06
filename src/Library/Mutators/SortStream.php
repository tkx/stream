<?php

namespace Moteam\Stream\Library\Mutators;

use Moteam\Stream\Stream;

/**
 * Streams source, sorted using given comparator function
 * @method sort(callable $fn = fn(mixed $a, mixed $b): -1|0|1, bool $preserve_keys = false): Stream
 * 
 * @psalm-api
 */
class SortStream extends Stream {
    public function stream(): \Iterator {
        $fn = $this->useMutator();
        $data = iterator_to_array($this->iterator);
        uasort($data, $fn);
        [$preserve_keys] = $this->useParameters(["is_bool", false]);
        foreach($data as $key => $value) {
            if($preserve_keys) {
                yield $key => $value;
            } else {
                yield $value;
            }
        }
    }
}
