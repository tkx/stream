<?php

namespace Moteam\Stream\Library\Terminals;
use Moteam\Stream\Library\TerminalInterface;

/**
 * Returns true if given key presents in this stream
 * @method bool hasKey(mixed $k)
 * 
 * @psalm-api
 */
class HasKeyTerminal extends Terminal implements TerminalInterface {
    public function __invoke(...$parameters) {
        [$v] = $this->useParameters($parameters, ["is_scalar", null]);
        foreach($this->stream->stream() as $key => $value) {
            if($key === $v) {
                return true;
            }
        }
        return false;
    }
}
