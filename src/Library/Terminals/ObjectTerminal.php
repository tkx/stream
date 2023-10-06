<?php

namespace Moteam\Stream\Library\Terminals;

/**
 * Returns stream as object, best for associative arrays
 * @method object(): \stdClass
 * 
 * @psalm-api
 */
class ObjectTerminal extends Terminal {
    public function __invoke(...$parameters) {
        $object = new \stdClass();
        foreach($this->stream->stream() as $key => $value) {
            $object->{$key} = $value;
        }
        return $object;
    }
}
