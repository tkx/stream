<?php

namespace Stream\Library;

function is_streamable($of): bool {
    return \is_array($of)
        || $of instanceof \Stream\Stream
        || $of instanceof \Traversable
        || $of instanceof \Iterator
        || $of instanceof \iterable
        || $of instanceof \Generator
        || $of instanceof \IteratorAggregate
        || $of instanceof \ArrayObject;
}
