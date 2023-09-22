<?php

namespace Stream\Library\Terminals;

class AllMatchTerminal extends Terminal {
    public function __invoke(...$parameters) {
        [$fn] = $this->useParameters($parameters, ["is_callable", null]);
        foreach($this->stream->stream() as $key => $value) {
            if(!$fn($value)) {
                return false;
            }
        }
        return true;
    }
}
