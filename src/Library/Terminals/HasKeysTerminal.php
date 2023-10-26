<?php

namespace Moteam\Stream\Library\Terminals;
use Moteam\Stream\Library\TerminalInterface;

/**
 * Returns true if given key presents in this stream
 * @method bool hasKeys(array $keys)
 * 
 * @psalm-api
 */
class HasKeysTerminal extends Terminal implements TerminalInterface {
    public function __invoke(...$parameters) {
        [$ks] = $this->useParameters($parameters, ["is_array", null]);
        $keys = \array_keys(\iterator_to_array($this->stream->stream()));
        foreach($ks as $k) {
            if(!\in_array($k, $keys)) {
                print_r([$k]);
                return false;
            }
        }
        return true;
    }
}
