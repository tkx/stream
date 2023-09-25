<?php

namespace Moteam\Stream\Library\Mutators;

use Moteam\Stream\Stream;

class SortedStream extends Stream {
    public function stream(): \Iterator {
        $fn = $this->useMutator();
        $data = iterator_to_array($this->iterator);
        uasort($data, $fn);
        [$preserve_keys] = $this->useParameters(["is_bool", false]);
        foreach($data as $key => $value) {
            if($preserve_keys) {
                yield $key => $value;
            } else {
                yield $value;
            }
        }
    }
}
