<?php
namespace Stream;

require_once "Library/functions.php";

// todo - change $mutator(...) -> call_user_func($mutator, ...)
// todo - Stream::useParameters, Terminal::useParameters -> useParameters($parameters, ...$specs)
// todo - useParameters - add multiple validators

class Stream {
    protected ?\Iterator $iterator = null;
    protected ?\Closure $mutator = null;
    protected ?array $parameters = null;

    public static function of($of, callable $mutator = null, ...$parameters): self {
        return new static($of, $mutator, $parameters);
    }
    public function stream(): \Iterator { yield from $this->iterator; }
    protected function __construct($of, callable $mutator = null, $parameters = []) {
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

    protected function useMutator(): callable {
        if(!$this->mutator || !is_callable($this->mutator)) {
            throw new \InvalidArgumentException();
        }
        return $this->mutator;
    }
    protected function useParameters(...$specs): array {
        $values = [];
        foreach($specs as $i => $spec) {
            [$validator, $default] = $spec;
            if(array_key_exists($i, $this->parameters)) {
                if(\is_callable($validator) && !call_user_func($validator, $this->parameters[$i])) {
                    throw new \InvalidArgumentException();
                }
                if(!\is_callable($validator)) {
                    throw new \InvalidArgumentException();
                }
                $values[] = $this->parameters[$i];
            } else {
                if($default === null) {
                    throw new \InvalidArgumentException();
                }
                $values[] = $default;
            }
        }
        return $values;
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

    private static array $mutators = [
        "map" => \Stream\Library\Mutators\MapStream::class,
        "keys" => \Stream\Library\Mutators\KeysStream::class,
        "pluck" => \Stream\Library\Mutators\KeysStream::class,
        "filter" => \Stream\Library\Mutators\FilterStream::class,
        "reject" => \Stream\Library\Mutators\RejectStream::class,
        "skip" => \Stream\Library\Mutators\SkipStream::class,
        "limit" => \Stream\Library\Mutators\LimitStream::class,
        "distinct" => \Stream\Library\Mutators\DistinctStream::class,
        "concat" => \Stream\Library\Mutators\ConcatStream::class,
        "foreach" => \Stream\Library\Mutators\ForeachStream::class,
        "extend" => \Stream\Library\Mutators\ConcatStream::class,
        "enrich" => \Stream\Library\Mutators\EnrichStream::class,
        "sorted" => \Stream\Library\Mutators\SortedStream::class,
        "groupBy" => \Stream\Library\Mutators\GroupByStream::class,
        "countBy" => \Stream\Library\Mutators\CountByStream::class,
        "indexBy" => \Stream\Library\Mutators\IndexByStream::class,
        "randomN" => \Stream\Library\Mutators\RandomNStream::class,
        "sampleN" => \Stream\Library\Mutators\RandomNStream::class,
        "partition" => \Stream\Library\Mutators\PartitionStream::class,
    ];

    private static array $terminals = [
        "collect" => \Stream\Library\Terminals\CollectTerminal::class,
        "reduce" => \Stream\Library\Terminals\ReduceTerminal::class,
        "anyMatch" => \Stream\Library\Terminals\AnyMatchTerminal::class,
        "every" => \Stream\Library\Terminals\AllMatchTerminal::class,
        "allMatch" => \Stream\Library\Terminals\AllMatchTerminal::class,
        "some" => \Stream\Library\Terminals\AnyMatchTerminal::class,
        "count" => \Stream\Library\Terminals\CountTerminal::class,
        "size" => \Stream\Library\Terminals\CountTerminal::class,
        "findLast" => \Stream\Library\Terminals\FindLastTerminal::class,
        "findFirst" => \Stream\Library\Terminals\FindFirstTerminal::class,
        "contains" => \Stream\Library\Terminals\ContainsTerminal::class,
        "object" => \Stream\Library\Terminals\ObjectTerminal::class,
        "min" => \Stream\Library\Terminals\MinTerminal::class,
        "max" => \Stream\Library\Terminals\MaxTerminal::class,
        "random" => \Stream\Library\Terminals\RandomTerminal::class,
        "sample" => \Stream\Library\Terminals\RandomTerminal::class,
    ];
}
