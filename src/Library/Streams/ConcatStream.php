<?php

namespace Moteam\Stream\Library\Streams;

use Moteam\Stream\Library\StreamInterface;
use Moteam\Stream\Stream;

/**
 * Streams given input after the source stream.
 * @method StreamInterface concat(mixed $source, bool $preserve_keys = false)
 * 
 * @psalm-api
 */
class ConcatStream extends Stream implements StreamInterface {
    public function stream(): \Iterator {
        [$input, $preserve_keys] = $this->useParameters(["\Moteam\Stream\Library\is_streamable", null], ["is_bool", false]);
        foreach($this->iterator as $key => $value) {
            if(!$preserve_keys) {
                yield $value;
            } else {
                yield $key => $value;
            }
        }
        $concatable = Stream::of($input);
        foreach($concatable->stream() as $key => $value) {
            if(!$preserve_keys) {
                yield $value;
            } else {
                yield $key => $value;
            }
        }
    }
}
