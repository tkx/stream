<?php
namespace Stream;

class Stream {
    public ?\Iterator $iterator = null;
    public ?\Closure $mutator = null;
    public ?array $parameters = null;

    public static function of($of, \Closure $mutator = null, ...$parameters): self {
        return new static($of, $mutator, $parameters);
    }
    public function stream(): \Iterator { yield from $this->iterator; }
    public function __construct($of, \Closure $mutator = null, $parameters = []) {
        if(\is_array($of)) {
            $this->iterator = (function() use($of) { yield from $of; })();
        } else if(\is_object($of)) {
            switch(true) {
                case $of instanceof Stream:
                    $this->iterator = $of->stream();
                    break;
                case $of instanceof \Traversable:
                case $of instanceof \Iterator:
                case $of instanceof \iterable:
                case $of instanceof \Generator:
                    $this->iterator = $of;
                    break;
                case $of instanceof \IteratorAggregate:
                case $of instanceof \ArrayObject:
                    $this->iterator = $of->getIterator();
                    break;
                default:
                    throw new \InvalidArgumentException();
            }
        }

        $this->mutator = $mutator;
        $this->parameters = $parameters;
    }

    public function __call(string $name, array $parameters) {
        if(array_key_exists($name, self::$terminals)) {
            $klass = self::$terminals[$name];
            return ($klass::of($this))(...$parameters);
        }

        if(!array_key_exists($name, self::$mutators)) {
            throw new \BadMethodCallException();
        }
        $klass = self::$mutators[$name];

        $new_iterator = (function() {
            yield from $this->stream();
        })();

        if(count($parameters) >= 2) {
            return $klass::of($new_iterator, $parameters);
        } else if(count($parameters) == 1 && !is_callable($parameters[0])) {
            return $klass::of($new_iterator, null, $parameters[0]);
        } else if(count($parameters) == 1 && is_callable($parameters[0])) {
            return $klass::of($new_iterator, $parameters[0]);
        } else {
            return $klass::of($new_iterator);
        }
    }

    public function __invoke() {
        return $this->collect();
    }

    public static array $terminals = [
        "collect" => \Stream\Library\Terminals\CollectTerminal::class,
        "reduce" => \Stream\Library\Terminals\ReduceTerminal::class,
        "anyMatch" => \Stream\Library\Terminals\AnyMatchTerminal::class,
        "allMatch" => \Stream\Library\Terminals\AllMatchTerminal::class,
        "count" => \Stream\Library\Terminals\CountTerminal::class,
        "findLast" => \Stream\Library\Terminals\FindLastTerminal::class,
        "findFirst" => \Stream\Library\Terminals\FindFirstTerminal::class,
        "object" => \Stream\Library\Terminals\ObjectTerminal::class,
        "min" => \Stream\Library\Terminals\MinTerminal::class,
        "max" => \Stream\Library\Terminals\MaxTerminal::class,
    ];

    public static array $mutators = [
        "map" => \Stream\Library\Mutators\MapStream::class,
        "filter" => \Stream\Library\Mutators\FilterStream::class,
        "skip" => \Stream\Library\Mutators\SkipStream::class,
        "limit" => \Stream\Library\Mutators\LimitStream::class,
        "distinct" => \Stream\Library\Mutators\DistinctStream::class,
        "concat" => \Stream\Library\Mutators\ConcatStream::class,
        "foreach" => \Stream\Library\Mutators\ForeachStream::class,
        "extend" => \Stream\Library\Mutators\ConcatStream::class,
        "enrich" => \Stream\Library\Mutators\EnrichStream::class,
        "sorted" => \Stream\Library\Mutators\SortedStream::class,
    ];
}
