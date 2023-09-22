<?php

namespace Stream\Library\Mutators;

use Stream\Stream;

class ForeachStream extends Stream {
    public function stream(): \Iterator {
        $mutator = $this->useMutator();
        foreach($this->iterator as $key => $value) {
            ($mutator)($value, $key);
            yield $key => $value;
        }
    }
}
