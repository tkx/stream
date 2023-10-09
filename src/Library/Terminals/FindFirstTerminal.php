<?php

namespace Moteam\Stream\Library\Terminals;

/**
 * Finds first value of stream which satisfies given function
 * @method findFirst(callable $by = fn(mixed $x): bool => !!$x): mixed
 * 
 * @psalm-api
 */
class FindFirstTerminal extends Terminal {
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
