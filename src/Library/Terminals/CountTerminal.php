<?php

namespace Moteam\Stream\Library\Terminals;

class CountTerminal extends Terminal {
    public function __invoke(...$parameters) {
        return count(iterator_to_array($this->stream->stream()));
    }
}
