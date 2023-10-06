<?php

namespace Moteam\Stream\Library\Terminals;

/**
 * Collects stream as array. Also can be called by __invoking any stream object
 * @method collect(): array
 * 
 * @psalm-api
 */
class CollectTerminal extends Terminal {
    public function __invoke(...$parameters) {
        return iterator_to_array($this->stream->stream());
    }
}
