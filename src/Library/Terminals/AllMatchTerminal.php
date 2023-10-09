<?php

namespace Moteam\Stream\Library\Terminals;

/**
 * Returns true if all input source values satisfy given function
 * @method allMatch(callable $by = fn(mixed $x): bool => !!$x): bool
 *
 * @psalm-api
 */
class AllMatchTerminal extends Terminal {
    public function __invoke(...$parameters) {
        [$fn] = $this->useParameters($parameters, ["is_callable", null]);
        foreach($this->stream->stream() as $key => $value) {
            if(!\call_user_func($fn, $value, $key)) {
                return false;
            }
        }
        return true;
    }
}
