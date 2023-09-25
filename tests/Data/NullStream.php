<?php

namespace Moteam\Stream\Library\Mutators;

use Moteam\Stream\Stream;

class NullStream extends Stream {
    public function stream(): \Iterator {
        [$preserve_keys] = $this->useParameters(["is_bool", false]);
        foreach($this->iterator as $key => $value) {
            if(!$preserve_keys) {
                yield null;
            } else {
                yield $key => null;
            }
        }
    }
}
