<?php

namespace Moteam\Stream\Library\Terminals;

class RandomTerminal extends Terminal {
    public function __invoke(...$parameters) {
        $data = iterator_to_array($this->stream->stream());
        shuffle($data);
        return $data[0];
    }
}
