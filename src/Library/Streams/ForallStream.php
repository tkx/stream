<?php

namespace Moteam\Stream\Library\Streams;

use Moteam\Stream\Library\StreamInterface;
use Moteam\Stream\Stream;

/**
 * Applies given function to a dump of input stream; streams the source data without changes
 * Can not be used for mutating source stream
 * @method StreamInterface forall(callable $do = function(mixed[] $values): void {})
 * 
 * @psalm-api
 */
class ForallStream extends Stream implements StreamInterface {
    public function stream(): \Iterator {
        $mutator = $this->useMutator();
        $data = \iterator_to_array($this->iterator);
        \call_user_func($mutator, $data);

        foreach($data as $key => $value) {
            yield $key => $value;
        }
    }
}
