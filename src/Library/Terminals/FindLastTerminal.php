<?php

namespace Moteam\Stream\Library\Terminals;
use Moteam\Stream\Library\TerminalInterface;

/**
 * Finds last value of stream which satisfies given function
 * @method mixed findLast(callable $by = fn(mixed $x, mixed $k): bool => !!$x)
 * 
 * @psalm-api
 */
class FindLastTerminal extends Terminal implements TerminalInterface {
    public function __invoke(...$parameters) {
        [$fn] = $this->useParameters($parameters, ["is_callable", null]);
        $found = null;
        foreach($this->stream->stream() as $key => $value) {
            if(\call_user_func($fn, $value, $key)) {
                $found = $value;
            }
        }
        return $found;
    }
}
