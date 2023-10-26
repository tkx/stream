<?php

namespace Moteam\Stream\Library\Terminals;
use Moteam\Stream\Library\TerminalInterface;

/**
 * Collects stream as array. Also can be called by __invoking any stream object
 * @method array collect()
 * 
 * @psalm-api
 */
class CollectTerminal extends Terminal implements TerminalInterface {
    public function __invoke(...$parameters) {
        return \iterator_to_array($this->stream->stream());
    }
}
