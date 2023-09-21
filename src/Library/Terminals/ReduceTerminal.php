<?php

namespace Stream\Library\Terminals;

class ReduceTerminal extends Terminal {
    public function __invoke(...$parameters) {
        if(count($parameters) < 2) {
            throw new \InvalidArgumentException();
        }
        if(!is_callable($parameters[0])) {
            throw new \InvalidArgumentException();
        }
        $fn = $parameters[0];
        $accumulator = $parameters[1];
        foreach($this->stream->stream() as $key => $value) {
            $accumulator = $fn($accumulator, $value);
        }

        return $accumulator;
    }
}
