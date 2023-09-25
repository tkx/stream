<?php

namespace Moteam\Stream\Library;

use Moteam\Stream\Stream;

function is_streamable($of): bool {
    return \is_array($of)
        || $of instanceof \Moteam\Stream\Stream
        || $of instanceof \Traversable
        || $of instanceof \Iterator
        || $of instanceof \iterable
        || $of instanceof \Generator
        || $of instanceof \IteratorAggregate
        || $of instanceof \ArrayObject;
}

function S($of): Stream {
    return Stream::of($of);
}
