<?php

namespace Stream\Library\Mutators;

use Stream\Stream;

class DistinctStream extends Stream {
    public array $hashMap = [];
    public function stream(): \Iterator {
        [$limit, $preserve_keys] = $this->useParameters(["is_int", 1], ["is_bool", false]);;
        foreach($this->iterator as $key => $value) {
            if(array_key_exists($value, $this->hashMap)) {
                $this->hashMap[$value] += 1;
            } else {
                $this->hashMap[$value] = 1;
            }
            if($this->hashMap[$value] <= $limit) {
                if(!$preserve_keys) {
                    yield $value;
                } else {
                    yield $key => $value;
                }
            }
        }
    }
}
