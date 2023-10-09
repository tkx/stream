<?php

namespace Moteam\Stream\Library\Mutators;

use Moteam\Stream\Stream;

/**
 * Applies given function to a dump of input stream; streams the source data without changes
 * Can not be used for mutating source stream
 * @method forall(callable $do = function(mixed[] $values): void {}): Stream
 * 
 * @psalm-api
 */
class ForallStream extends Stream {
    public function stream(): \Iterator {
        $mutator = $this->useMutator();
        $data = \iterator_to_array($this->iterator);
        \call_user_func($mutator, $data);

        foreach($data as $key => $value) {
            yield $key => $value;
        }
    }
}
