<?php

namespace Stream\Library\Terminals;

class MinTerminal extends Terminal {
    public function __invoke(...$parameters) {
        [$fn] = $this->useParameters($parameters, ["is_callable", null]);
        $data = iterator_to_array($this->stream->stream());
        uasort($data, $fn);

        return $data[0];
    }
}
