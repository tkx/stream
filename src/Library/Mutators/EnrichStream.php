<?php

namespace Moteam\Stream\Library\Mutators;

use Moteam\Stream\Stream;

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
