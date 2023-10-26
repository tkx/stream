<?php

namespace Moteam\Stream\Library\Terminals;
use Moteam\Stream\Library\TerminalInterface;

/**
 * Returns random value from source stream
 * @method mixed random()
 * 
 * @psalm-api
 */
class RandomTerminal extends Terminal implements TerminalInterface {
    public function __invoke(...$parameters) {
        $data = \iterator_to_array($this->stream->stream());
        \shuffle($data);
        return $data[0];
    }
}
