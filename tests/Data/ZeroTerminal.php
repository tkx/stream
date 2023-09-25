<?php

namespace Moteam\Stream\Library\Terminals;

class ZeroTerminal extends Terminal {
    public function __invoke(...$parameters) {
        $data = [];
        foreach($this->stream->stream() as $key => $value) {
            if($value === null) {
                $data[] = 0;
            } else {
                $data[] = $value;
            }
        }
        return $data;
    }
}
