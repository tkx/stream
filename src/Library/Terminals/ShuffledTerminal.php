<?php

namespace Moteam\Stream\Library\Terminals;
use Moteam\Stream\Library\TerminalInterface;

/**
 * Returns shuffled source stream as array (shortcut to $stream->shuffle()->collect() - if you do not care about keys)
 * @method mixed shuffled()
 * 
 * @psalm-api
 */
class ShuffledTerminal extends Terminal implements TerminalInterface {
    public function __invoke(...$parameters) {
        $data = \iterator_to_array($this->stream->stream());
        \shuffle($data);
        return $data;
    }
}
