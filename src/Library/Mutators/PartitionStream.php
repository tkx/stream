<?php

namespace Stream\Library\Mutators;

use Stream\Stream;

class PartitionStream extends Stream {
    public function stream(): \Iterator {
        $groups = [[], []];
        foreach($this->iterator as $key => $value) {
            $key0 = ($this->useMutator())($value);
            if($key0 === true) {
                $groups[0][] = $value;
            } else {
                $groups[1][] = $value;
            }
        }
        foreach($groups as $key0 => $value0) {
            yield $key0 => $value0;
        }
    }
}
