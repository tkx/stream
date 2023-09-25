<?php

namespace Moteam\Stream\Library\Mutators;

use Moteam\Stream\Stream;

class ForeachStream extends Stream {
    public function stream(): \Iterator {
        $mutator = $this->useMutator();
        foreach($this->iterator as $key => $value) {
            call_user_func($mutator, $value, $key);
            yield $key => $value;
        }
    }
}
