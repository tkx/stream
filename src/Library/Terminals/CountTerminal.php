<?php

namespace Moteam\Stream\Library\Terminals;

/**
 * Returns stream elements count
 * @method count(): int
 * 
 * @psalm-api
 */
class CountTerminal extends Terminal {
    public function __invoke(...$parameters) {
        return count(iterator_to_array($this->stream->stream()));
    }
}
