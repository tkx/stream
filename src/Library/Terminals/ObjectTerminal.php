<?php

namespace Moteam\Stream\Library\Terminals;

class ObjectTerminal extends Terminal {
    public function __invoke(...$parameters) {
        $object = new \stdClass();
        foreach($this->stream->stream() as $key => $value) {
            $object->{$key} = $value;
        }
        return $object;
    }
}
