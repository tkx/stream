<?php

namespace Stream\Library\Terminals;

class FindFirstTerminal extends Terminal {
    public function __invoke(...$parameters) {
        [$fn] = $this->useParameters($parameters, ["is_callable", null]);
        foreach($this->stream->stream() as $key => $value) {
            if($fn($value)) {
                return $value;
            }
        }
        return null;
    }
}
