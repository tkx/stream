<?php

namespace Stream\Library\Terminals;

class AnyMatchTerminal extends Terminal {
    public function __invoke(...$parameters) {
        if(count($parameters) < 1) {
            throw new \InvalidArgumentException();
        }
        if(!is_callable($parameters[0])) {
            throw new \InvalidArgumentException();
        }
        $fn = $parameters[0];
        $accumulator = false;
        foreach($this->stream->stream() as $key => $value) {
            $accumulator = $fn($value) || $accumulator;
        }
        return $accumulator;
    }
}
