<?php

namespace Moteam\Stream\Library\Terminals;
use Moteam\Stream\Library\TerminalInterface;

/**
 * Returns stream as object, best for associative arrays
 * @method \stdClass object()
 * 
 * @psalm-api
 */
class ObjectTerminal extends Terminal implements TerminalInterface {
    public function __invoke(...$parameters) {
        $object = new \stdClass();
        foreach($this->stream->stream() as $key => $value) {
            $object->{$key} = $value;
        }
        return $object;
    }
}
