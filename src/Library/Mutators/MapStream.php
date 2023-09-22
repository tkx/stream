<?php

namespace Stream\Library\Mutators;

use Stream\Stream;

class MapStream extends Stream {
    public function stream(): \Iterator {
        $mutator = $this->useMutator();
        [$preserve_keys] = $this->useParameters(["is_bool", false]);
        foreach($this->iterator as $key => $value) {
            if($preserve_keys) {
                yield $key => ($mutator)($value);
            } else {
                yield ($mutator)($value);
            }
        }
    }
}
