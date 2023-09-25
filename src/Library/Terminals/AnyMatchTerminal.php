<?php

namespace Moteam\Stream\Library\Terminals;

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
