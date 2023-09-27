<?php

namespace Moteam\Stream\Library\Mutators;

use Moteam\Stream\Stream;

class MapByStream extends Stream {
    public function stream(): \Iterator {
        $mutator = $this->useMutator();
        [$preserve_keys] = $this->useParameters(["is_bool", false]);
        foreach($this->iterator as $key0 => $streamable) {
            $data = [];
            foreach($streamable as $key => $value) {
                if($preserve_keys) {
                    $data[$key] = call_user_func($mutator, $value);
                } else {
                    $data[] = call_user_func($mutator, $value);
                }
            }
            yield $key0 => $data;
        }
    }
}
