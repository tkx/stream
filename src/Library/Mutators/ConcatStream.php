<?php

namespace Stream\Library\Mutators;

use Stream\Stream;

class ConcatStream extends Stream {
    public function stream(): \Iterator {
        foreach($this->iterator as $key => $value) {
            yield $value;
        }
        $concatable = Stream::of($this->parameters[0]);
        // TODO
        $preserve_keys = false; //$this->parameters[1] || false;
        foreach($concatable->stream() as $key => $value) {
            if(!$preserve_keys) {
                yield $value;
            } else {
                yield $key => $value;
            }
        }
    }
}
