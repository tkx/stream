<?php

namespace Moteam\Stream\Library\Mutators;

use Moteam\Stream\Stream;

class FilterStream extends Stream {
    public function stream(): \Iterator {
        $mutator = $this->useMutator();
        [$preserve_keys] = $this->useParameters(["is_bool", false]);
        foreach($this->iterator as $key => $value) {
            if($this->mutator !== null) {
                $temp = call_user_func($mutator, $value);
            } else {
                $temp = !!$value;
            }
            if($temp !== false) {
                if(!$preserve_keys) {
                    yield $value;
                } else {
                    yield $key => $value;
                }
            }
        }
    }
}
