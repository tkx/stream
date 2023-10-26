<?php

namespace Moteam\Stream\Library;

use Moteam\Stream\Stream;

/**
 * Consumes array of input values and validates it against provided scheme.
 * 
 * @param mixed[] $parameters Array of input values
 * @param ...$specs <p>Parameters description as [[callable, mixed|null], ...] - pairs of (validator, defaultValue)
 *                  validators - list of validator functions, e.g. is_int, is_string, ...; should all return non nullish to pass
 *                  defaultValue = mixed|null, optional default value if not provided input, if null - value is required
 *                  </p>
 * @return array <p>Array of expected values</p>
 * @throws \InvalidArgumentException
 */
function use_parameters($parameters, ...$specs): array {
    $values = [];
    foreach($specs as $i => $spec) {
        [$validators, $default] = $spec;
        if(\array_key_exists($i, $parameters)) {
            if(!\is_array($validators)) {
                $validators = [$validators];
            }
            foreach($validators as $validator) {
                if (\is_callable($validator) && !\call_user_func($validator, $parameters[$i])) {
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
/**
 * @param mixed $of
 */
function is_streamable($of): bool {
    return \is_array($of)
        || $of instanceof StreamInterface
        || $of instanceof \Traversable
        || $of instanceof \Iterator
        || is_iterable($of)
        || is_object($of)
        || $of instanceof \Generator
        || $of instanceof \IteratorAggregate
        || $of instanceof \ArrayObject;
}

/**
 * Shortcut to Stream::of
 * @param mixed $of
 */
function S($of): StreamInterface {
    return Stream::of($of);
}
