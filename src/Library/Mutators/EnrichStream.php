<?php

namespace Stream\Library\Mutators;

use Stream\Stream;

class EnrichStream extends Stream {
    public function stream(): \Iterator {
        $data = [];
        foreach($this->iterator as $key => $value) {
            $data[$key] = $value;
            yield $key => $value;
        }

        $fn = $this->parameters[0];
        foreach($fn($data) as $key => $value) {
            yield $key => $value;
        }
    }
}
