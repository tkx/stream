<?php

namespace Moteam\Stream\Library\Terminals;

/**
 * Returns true if given key presents in this stream
 * @method has(mixed $k): bool
 * 
 * @psalm-api
 */
class HasTerminal extends Terminal {
    public function __invoke(...$parameters) {
        [$v] = $this->useParameters($parameters, ["is_scalar", null]);
        foreach($this->stream->stream() as $key => $value) {
            if($key === $v) {
                return true;
            }
        }
        return false;
    }
}
