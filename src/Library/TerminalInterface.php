<?php declare(strict_types= 1);

namespace Moteam\Stream\Library;

interface TerminalInterface {
    static function of(StreamInterface $stream);
    function __invoke(...$parameters);
}