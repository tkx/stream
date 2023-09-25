<?php

namespace Moteam\Stream\Library\Terminals;

class ContainsTerminal extends Terminal {
    public function __invoke(...$parameters) {
        [$v] = $this->useParameters($parameters, [fn($x) => true, null]);
        foreach($this->stream->stream() as $key => $value) {
            if($value === $v) {
                return true;
            }
        }
        return false;
    }
}
