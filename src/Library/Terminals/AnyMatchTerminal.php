<?php

namespace Stream\Library\Terminals;

class AnyMatchTerminal extends Terminal {
    public function __invoke(...$parameters) {
        [$fn] = $this->useParameters($parameters, ["is_callable", null]);
        $accumulator = false;
        foreach($this->stream->stream() as $key => $value) {
            $accumulator = $fn($value) || $accumulator;
        }
        return $accumulator;
    }
}
