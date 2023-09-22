<?php

namespace Stream\Library\Mutators;

use Stream\Stream;

class RandomNStream extends Stream {
    public function stream(): \Iterator {
        $data = iterator_to_array($this->iterator);
        shuffle($data);
        [$n] = $this->useParameters(["is_int", 1]);
        for($i = 0; $i < $n; $i++) {
            yield $data[$i];
        }
    }
}
