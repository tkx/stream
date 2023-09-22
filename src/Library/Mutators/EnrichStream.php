<?php

namespace Stream\Library\Mutators;

use Stream\Stream;

class EnrichStream extends Stream {
    public function stream(): \Iterator {
        $mutator = $this->useMutator();
        $data = [];
        foreach($this->iterator as $key => $value) {
            $data[$key] = $value;
            yield $key => $value;
        }

        foreach(($mutator)($data) as $key => $value) {
            yield $key => $value;
        }
    }
}
