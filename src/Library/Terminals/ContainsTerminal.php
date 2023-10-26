<?php

namespace Moteam\Stream\Library\Terminals;
use Moteam\Stream\Library\TerminalInterface;

/**
 * Returns true if given value presents in this stream
 * @method bool contains(mixed $v)
 * 
 * @psalm-api
 */
class ContainsTerminal extends Terminal implements TerminalInterface {
    public function __invoke(...$parameters) {
        [$v] = $this->useParameters($parameters, [fn($x) => true, null]);
        foreach($this->stream->stream() as $key => $value) {
            if($value === $v) {
                return true;
            }
        }
        return false;
    }
}
