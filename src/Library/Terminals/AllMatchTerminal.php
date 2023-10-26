<?php

namespace Moteam\Stream\Library\Terminals;
use Moteam\Stream\Library\TerminalInterface;

/**
 * Returns true if all input source values satisfy given function
 * @method bool allMatch(callable $by = fn(mixed $x, mixed $k): bool => !!$x)
 *
 * @psalm-api
 */
class AllMatchTerminal extends Terminal implements TerminalInterface {
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
