<?php

namespace Stream\Library\Terminals;

class FindFirstTerminal extends Terminal {
    public function __invoke(...$parameters) {
        if(count($parameters) < 1) {
            throw new \InvalidArgumentException();
        }
        if(!is_callable($parameters[0])) {
            throw new \InvalidArgumentException();
        }
        $fn = $parameters[0];
        foreach($this->stream->stream() as $key => $value) {
            if($fn($value)) {
                return $value;
            }
        }
        return null;
    }
}
