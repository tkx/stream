<?php

namespace Moteam\Stream\Library\Streams;

use Moteam\Stream\Library\StreamInterface;
use Moteam\Stream\Stream;

/**
 * Applies given function to each element of input stream; streams the source data without changes
 * Can not be used for mutating source stream
 * @method StreamInterface foreach(callable $do = function(mixed $x, mixed $k): void {})
 * 
 * @psalm-api
 */
class ForeachStream extends Stream implements StreamInterface {
    public function stream(): \Iterator {
        $mutator = $this->useMutator();
        foreach($this->iterator as $key => $value) {
            \call_user_func($mutator, $value, $key);
            yield $key => $value;
        }
    }
}
