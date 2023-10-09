<?php

namespace Moteam\Stream\Library\Mutators;

use Moteam\Stream\Stream;

/**
 * Applies given function to each element of input stream; streams the source data without changes
 * Can not be used for mutating source stream
 * @method foreach(callable $do = function(mixed $x): void {}): Stream
 * 
 * @psalm-api
 */
class ForeachStream extends Stream {
    public function stream(): \Iterator {
        $mutator = $this->useMutator();
        foreach($this->iterator as $key => $value) {
            \call_user_func($mutator, $value, $key);
            yield $key => $value;
        }
    }
}
