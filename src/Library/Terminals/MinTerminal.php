<?php

namespace Moteam\Stream\Library\Terminals;

class MinTerminal extends Terminal {
    public function __invoke(...$parameters) {
        [$fn] = $this->useParameters($parameters, ["is_callable", null]);
        $data = iterator_to_array($this->stream->stream());
        uasort($data, $fn);

        return array_values($data)[0];
    }
}
