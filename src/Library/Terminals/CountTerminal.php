<?php

namespace Moteam\Stream\Library\Terminals;
use Moteam\Stream\Library\TerminalInterface;

/**
 * Returns stream elements count
 * @method int count()
 * 
 * @psalm-api
 */
class CountTerminal extends Terminal implements TerminalInterface {
    public function __invoke(...$parameters) {
        return \count(\iterator_to_array($this->stream->stream()));
    }
}
