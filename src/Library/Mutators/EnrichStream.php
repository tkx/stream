<?php

namespace Moteam\Stream\Library\Mutators;

use Moteam\Stream\Stream;

/**
 * Given a function wich takes source as an argument, add the result to source stream, then streams all
 * @method enrich(callable $with = fn(array $data): Iterator => yield from $data): Stream
 * 
 * @psalm-api
 */
class EnrichStream extends Stream {
    public function stream(): \Iterator {
        $mutator = $this->useMutator();
        $data = [];
        foreach($this->iterator as $key => $value) {
            $data[$key] = $value;
            yield $key => $value;
        }

        foreach(call_user_func($mutator, $data) as $key => $value) {
            yield $key => $value;
        }
    }
}
