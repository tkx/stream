<?php

namespace Moteam\Stream\Library\Streams;

use Moteam\Stream\Library\StreamInterface;
use Moteam\Stream\Stream;

/**
 * Given a function wich takes source as an argument, add the result to source stream, then streams all
 * @method StreamInterface enrich(callable $with = fn(array $data): Iterator => yield from $data)
 * 
 * @psalm-api
 */
class EnrichStream extends Stream implements StreamInterface {
    public function stream(): \Iterator {
        $mutator = $this->useMutator();
        $data = [];
        foreach($this->iterator as $key => $value) {
            $data[$key] = $value;
            yield $key => $value;
        }

        foreach(\call_user_func($mutator, $data) as $key => $value) {
            yield $key => $value;
        }
    }
}
