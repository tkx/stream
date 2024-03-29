<?php

namespace Moteam\Stream\Library\Streams;

use Moteam\Stream\Library\StreamInterface;
use Moteam\Stream\Stream;

/**
 * Given result of application of groupBy method to source stream, applies function to each stream group
 * @method StreamInterface mapAll(callable $by = fn(mixed $x, mixed $k, mixed $k0): mixed => $x)
 * 
 * @psalm-api
 */
class MapAllStream extends Stream implements StreamInterface {
    public function stream(): \Iterator {
        $mutator = $this->useMutator();
        [$preserve_keys] = $this->useParameters(["is_bool", false]);
        foreach($this->iterator as $key0 => $streamable) {
            $data = [];
            foreach($streamable as $key => $value) {
                if($preserve_keys) {
                    $data[$key] = \call_user_func($mutator, $value, $key, $key0);
                } else {
                    $data[] = \call_user_func($mutator, $value, $key, $key0);
                }
            }
            yield $key0 => $data;
        }
    }
}
