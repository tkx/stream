<?php

namespace Stream\Library\Terminals;

class ReduceTerminal extends Terminal {
    public function __invoke(...$parameters) {
        [$fn, $accumulator] = $this->useParameters($parameters, ["is_callable", null]);
        foreach($this->stream->stream() as $key => $value) {
            $accumulator = $fn($accumulator, $value);
        }

        return $accumulator;
    }
}
