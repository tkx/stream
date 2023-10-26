<?php

namespace Moteam\Stream\Library\Terminals;
use Moteam\Stream\Library\TerminalInterface;

/**
 * Finds first value of stream which satisfies given function
 * @method mixed findFirst(callable $by = fn(mixed $x, mixed $k): bool => !!$x)
 * 
 * @psalm-api
 */
class FindFirstTerminal extends Terminal implements TerminalInterface {
    public function __invoke(...$parameters) {
        [$fn] = $this->useParameters($parameters, ["is_callable", null]);
        foreach($this->stream->stream() as $key => $value) {
            if(\call_user_func($fn, $value, $key)) {
                return $value;
            }
        }
        return null;
    }
}
