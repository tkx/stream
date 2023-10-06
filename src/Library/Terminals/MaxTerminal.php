<?php

namespace Moteam\Stream\Library\Terminals;

/**
 * Returns maximum value of stream, compared with given function
 * @method max(callable $comp = fn(mixed $a, mixed $b): int => $a - $b): mixed
 * 
 * @psalm-api
 */
class MaxTerminal extends Terminal {
    public function __invoke(...$parameters) {
        [$fn] = $this->useParameters($parameters, ["is_callable", null]);
        $data = iterator_to_array($this->stream->stream());
        uasort($data, $fn);
        $data = array_reverse($data, true);

        return array_values($data)[0];
    }
}
