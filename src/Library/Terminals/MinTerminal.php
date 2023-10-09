<?php

namespace Moteam\Stream\Library\Terminals;

/**
 * Returns minimum value of stream, compared with given function
 * @method min(callable $comp = fn(mixed $a, mixed $b): int => $a - $b): mixed
 * 
 * @psalm-api
 */
class MinTerminal extends Terminal {
    public function __invoke(...$parameters) {
        [$fn] = $this->useParameters($parameters, ["is_callable", null]);
        $data = \iterator_to_array($this->stream->stream());
        \uasort($data, $fn);

        return \array_values($data)[0];
    }
}
