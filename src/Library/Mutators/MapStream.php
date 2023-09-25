<?php

namespace Moteam\Stream\Library\Mutators;

use Moteam\Stream\Stream;

class MapStream extends Stream {
    public function stream(): \Iterator {
        $mutator = $this->useMutator();
        [$preserve_keys] = $this->useParameters(["is_bool", false]);
        foreach($this->iterator as $key => $value) {
            if($preserve_keys) {
                yield $key => call_user_func($mutator, $value);
            } else {
                yield call_user_func($mutator, $value);
            }
        }
    }
}
