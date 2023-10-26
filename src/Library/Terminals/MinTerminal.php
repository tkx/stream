<?php

namespace Moteam\Stream\Library\Terminals;
use Moteam\Stream\Library\TerminalInterface;

/**
 * Returns minimum value of stream, compared with given function
 * @method mixed min(callable $comp = fn(mixed $a, mixed $b): int => $a - $b)
 * 
 * @psalm-api
 */
class MinTerminal extends Terminal implements TerminalInterface {
    public function __invoke(...$parameters) {
        [$fn] = $this->useParameters($parameters, ["is_callable", null]);
        $data = \iterator_to_array($this->stream->stream());
        \uasort($data, $fn);

        return \array_values($data)[0];
    }
}
