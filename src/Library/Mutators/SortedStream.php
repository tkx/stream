<?php

namespace Stream\Library\Mutators;

use Stream\Stream;

class SortedStream extends Stream {
    public function stream(): \Iterator {
        if(!is_callable($this->parameters[0])) {
            throw new \InvalidArgumentException();
        }
        $fn = $this->parameters[0];

        $data = iterator_to_array($this->iterator);
        uasort($data, $fn);

        foreach($data as $key => $value) {
            yield $key => $value;
        }
    }
}
