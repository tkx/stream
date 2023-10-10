<?php

namespace Moteam\Stream\Library\Terminals;

/**
 * Finds last value of stream which satisfies given function
 * @method findLast(callable $by = fn(mixed $x, mixed $k): bool => !!$x): mixed
 * 
 * @psalm-api
 */
class FindLastTerminal extends Terminal {
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
