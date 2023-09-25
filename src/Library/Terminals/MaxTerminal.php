<?php

namespace Moteam\Stream\Library\Terminals;

class MaxTerminal extends Terminal {
    public function __invoke(...$parameters) {
        [$fn] = $this->useParameters($parameters, ["is_callable", null]);
        $data = iterator_to_array($this->stream->stream());
        uasort($data, $fn);
        $data = array_reverse($data, true);

        return array_values($data)[0];
    }
}
