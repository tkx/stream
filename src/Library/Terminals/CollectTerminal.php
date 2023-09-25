<?php

namespace Moteam\Stream\Library\Terminals;

class CollectTerminal extends Terminal {
    public function __invoke(...$parameters) {
        return iterator_to_array($this->stream->stream());
    }
}
