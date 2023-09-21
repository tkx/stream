<?php

namespace Stream\Library\Mutators;

use Stream\Stream;

class DistinctStream extends Stream {
    public array $hashMap = [];
    public function stream(): \Iterator {
        $limit = 1;
        if(!empty($this->parameters) && $this->parameters[0]) {
            if (!is_int($this->parameters[0])) {
                throw new \InvalidArgumentException();
            }
            $limit = $this->parameters[0];
        }
        // TODO
        $preserve_keys = false; //$this->parameters[1] || false;
        foreach($this->iterator as $key => $value) {
            @$this->hashMap[$value] = $this->hashMap[$value] ? $this->hashMap[$value] + 1 : 1;
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
