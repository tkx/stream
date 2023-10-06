<?php

namespace Moteam\Stream\Library\Terminals;

/**
 * Returns true if at least one of source stream elements satisfy given function
 * @method anyMatch(callable $by = fn(mixed $x): bool => !!$x): bool
 * 
 * @psalm-api
 */
class AnyMatchTerminal extends Terminal {
    public function __invoke(...$parameters) {
        [$fn] = $this->useParameters($parameters, ["is_callable", null]);
        foreach($this->stream->stream() as $key => $value) {
            if(call_user_func($fn, $value))
                return true;
        }
        return false;
    }
}
