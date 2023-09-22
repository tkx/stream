<?php

namespace Stream\Library\Terminals;

class FindLastTerminal extends Terminal {
    public function __invoke(...$parameters) {
        [$fn] = $this->useParameters($parameters, ["is_callable", null]);
        $found = null;
        foreach($this->stream->stream() as $key => $value) {
            if($fn($value)) {
                $found = $value;
            }
        }
        return $found;
    }
}
