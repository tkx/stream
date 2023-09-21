<?php

namespace Stream\Library\Terminals;

class FindLastTerminal extends Terminal {
    public function __invoke(...$parameters) {
        if(count($parameters) < 1) {
            throw new \InvalidArgumentException();
        }
        if(!is_callable($parameters[0])) {
            throw new \InvalidArgumentException();
        }
        $fn = $parameters[0];
        $found = null;
        foreach($this->stream->stream() as $key => $value) {
            if($fn($value)) {
                $found = $value;
            }
        }
        return $found;
    }
}
