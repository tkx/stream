<?php

namespace Moteam\Stream\Library;

use Moteam\Stream\Stream;

/**
 * Consumes array of input values and validates it against provided scheme.
 */
function use_parameters($parameters, ...$specs): array {
    $values = [];
    foreach($specs as $i => $spec) {
        [$validators, $default] = $spec;
        if(array_key_exists($i, $parameters)) {
            if(!is_array($validators)) {
                $validators = [$validators];
            }
            foreach($validators as $validator) {
                if (\is_callable($validator) && !call_user_func($validator, $parameters[$i])) {
                    throw new \InvalidArgumentException();
                }
                if (!\is_callable($validator)) {
                    throw new \InvalidArgumentException();
                }
            }
            $values[] = $parameters[$i];
        } else {
            if($default === null) {
                throw new \InvalidArgumentException();
            }
            $values[] = $default;
        }
    }
    return $values;
}
function is_streamable($of): bool {
    return \is_array($of)
        || $of instanceof Stream
        || $of instanceof \Traversable
        || $of instanceof \Iterator
        || is_iterable($of)
        || $of instanceof \Generator
        || $of instanceof \IteratorAggregate
        || $of instanceof \ArrayObject;
}

function S($of): Stream {
    return Stream::of($of);
}
