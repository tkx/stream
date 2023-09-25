<?php

namespace Moteam\Stream\Library\Terminals;

class CustomReduceTerminal extends Terminal {
    public function __invoke(...$parameters) {
        [$fn, $accumulator] = $this->useParameters($parameters,
            ["is_callable", null],
            [[fn($x) => true, fn($x) => !$x], null]
        );
        foreach($this->stream->stream() as $key => $value) {
            $accumulator = call_user_func($fn, $accumulator, $value);
        }

        return $accumulator;
    }
}
