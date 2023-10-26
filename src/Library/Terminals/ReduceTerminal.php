<?php

namespace Moteam\Stream\Library\Terminals;
use Moteam\Stream\Library\TerminalInterface;

/**
 * Reduces source stream by given function
 * @method mixed reduce(callable $by = fn(mixed $acc, mixed $value, mixed $key): mixed => $acc + $value)
 * 
 * @psalm-api
 */
class ReduceTerminal extends Terminal implements TerminalInterface {
    public function __invoke(...$parameters) {
        [$fn, $accumulator] = $this->useParameters($parameters, ["is_callable", null], [fn($x) => true, null]);
        foreach($this->stream->stream() as $key => $value) {
            $accumulator = \call_user_func($fn, $accumulator, $value, $key);
        }

        return $accumulator;
    }
}
