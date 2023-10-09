<?php

namespace Moteam\Stream\Library\Terminals;

/**
 * Returns random value from source stream
 * @method random(): mixed
 * 
 * @psalm-api
 */
class RandomTerminal extends Terminal {
    public function __invoke(...$parameters) {
        $data = \iterator_to_array($this->stream->stream());
        \shuffle($data);
        return $data[0];
    }
}
