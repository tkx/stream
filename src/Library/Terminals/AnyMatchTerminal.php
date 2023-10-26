<?php

namespace Moteam\Stream\Library\Terminals;
use Moteam\Stream\Library\TerminalInterface;

/**
 * Returns true if at least one of source stream elements satisfy given function
 * @method bool anyMatch(callable $by = fn(mixed $x, mixed $k): bool => !!$x)
 * 
 * @psalm-api
 */
class AnyMatchTerminal extends Terminal implements TerminalInterface {
    public function __invoke(...$parameters) {
        [$fn] = $this->useParameters($parameters, ["is_callable", null]);
        foreach($this->stream->stream() as $key => $value) {
            if(\call_user_func($fn, $value, $key))
                return true;
        }
        return false;
    }
}
