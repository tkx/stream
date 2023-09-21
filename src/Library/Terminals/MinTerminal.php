<?php

namespace Stream\Library\Terminals;

class MinTerminal extends Terminal {
    public function __invoke(...$parameters) {
        if(count($parameters) < 1) {
            throw new \InvalidArgumentException();
        }
        if(!is_callable($parameters[0])) {
            throw new \InvalidArgumentException();
        }
        $fn = $parameters[0];

        $data = iterator_to_array($this->stream->stream());
        uasort($data, $fn);

        return $data[0];
    }
}
