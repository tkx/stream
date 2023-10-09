<?php

namespace Moteam\Stream\Library\Terminals;

/**
 * Returns shuffled source stream as array (shortcut to $stream->shuffle()->collect() - if you do not care about keys)
 * @method shuffled(): mixed
 * 
 * @psalm-api
 */
class ShuffledTerminal extends Terminal {
    public function __invoke(...$parameters) {
        $data = \iterator_to_array($this->stream->stream());
        \shuffle($data);
        return $data;
    }
}
