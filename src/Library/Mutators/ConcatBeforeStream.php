<?php

namespace Moteam\Stream\Library\Mutators;

use Moteam\Stream\Stream;

/**
 * Streams given input before the source stream.
 * @method concatBefore(mixed $source, bool $preserve_keys = false): Stream
 * 
 * @psalm-api
 */
class ConcatBeforeStream extends Stream {
    public function stream(): \Iterator {
        [$input, $preserve_keys] = $this->useParameters(["\Moteam\Stream\Library\is_streamable", null], ["is_bool", false]);
        $concatable = Stream::of($input);
        foreach($concatable->stream() as $key => $value) {
            if(!$preserve_keys) {
                yield $value;
            } else {
                yield $key => $value;
            }
        }
        foreach($this->iterator as $key => $value) {
            if(!$preserve_keys) {
                yield $value;
            } else {
                yield $key => $value;
            }
        }
    }
}
