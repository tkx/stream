<?php declare(strict_types= 1);

namespace Moteam\Stream\Library;

interface StreamInterface {
    static function of($of, callable $mutator = null, ...$parameters);
    function stream();
    function __call(string $method, array $parameters);
    function __invoke();
}