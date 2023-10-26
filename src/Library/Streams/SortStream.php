<?php

namespace Moteam\Stream\Library\Streams;

use Moteam\Stream\Library\StreamInterface;
use Moteam\Stream\Stream;

/**
 * Streams source, sorted using given comparator function
 * @method StreamInterface sort(callable $fn = fn(mixed $a, mixed $b): int, bool $by_keys = false, bool $preserve_keys = false)
 * 
 * @psalm-api
 */
class SortStream extends Stream implements StreamInterface {
    public function stream(): \Iterator {
        $fn = $this->useMutator();
        $data = \iterator_to_array($this->iterator);
        [$by_keys, $preserve_keys] = $this->useParameters(["is_bool", false], ["is_bool", false]);
        if($by_keys) {
            \uksort($data, $fn);
        } else {
            \uasort($data, $fn);
        }
        foreach($data as $key => $value) {
            if($preserve_keys) {
                yield $key => $value;
            } else {
                yield $value;
            }
        }
    }
}
