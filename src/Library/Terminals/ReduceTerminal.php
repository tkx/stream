<?php

namespace Moteam\Stream\Library\Terminals;

/**
 * Reduces source stream by given function
 * @method reduce(callable $by = fn(mixed $acc, mixed $value): mixed => $acc + $value): mixed
 * 
 * @psalm-api
 */
class ReduceTerminal extends Terminal {
    public function __invoke(...$parameters) {
        [$fn, $accumulator] = $this->useParameters($parameters, ["is_callable", null], [fn($x) => true, null]);
        foreach($this->stream->stream() as $key => $value) {
            $accumulator = call_user_func($fn, $accumulator, $value);
        }

        return $accumulator;
    }
}
