<?php

namespace Moteam\Stream\Library\Terminals;

class FindLastTerminal extends Terminal {
    public function __invoke(...$parameters) {
        [$fn] = $this->useParameters($parameters, ["is_callable", null]);
        $found = null;
        foreach($this->stream->stream() as $key => $value) {
            if(call_user_func($fn, $value)) {
                $found = $value;
            }
        }
        return $found;
    }
}
