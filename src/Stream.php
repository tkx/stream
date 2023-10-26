<?php declare(strict_types=1);
namespace Moteam\Stream;

use Moteam\Stream\Library\TerminalInterface;
use Moteam\Stream\Library\Terminals\Terminal;
use Moteam\Stream\Library\StreamInterface;
use function Moteam\Stream\Library\use_parameters;

/**
 * Basic class that transforms anything traversable into generator-backed values stream.
 *
 * @method StreamInterface concat(mixed $source, bool $preserve_keys = false)
 * @method StreamInterface concatBefore(mixed $source, bool $preserve_keys = false)
 * @method StreamInterface countBy(callable $by = fn(mixed $x, mixed $k): mixed => !!$x)
 * @method StreamInterface distinct(int $limit, bool $preserve_keys = false)
 * @method StreamInterface enrich(callable $with = fn(array $data): Iterator => yield from $data)
 * @method StreamInterface filter(callable $by = fn(mixed $x, mixed $k): bool => !!$x, bool $preserve_keys = false)
 * @method StreamInterface foreach(callable $do = function(mixed $x, mixed $k): void {})
 * @method StreamInterface forall(callable $do = function(mixed[] $values): void {})
 * @method StreamInterface groupBy(callable $by = fn(mixed $x, mixed $k): mixed => !!$x, bool $preserve_keys = false)
 * @method StreamInterface mapAll(callable $by = fn(mixed $x, mixed $k, mixed $k0): mixed => $x)
 * @method StreamInterface indexBy(string|int $x)
 * @method StreamInterface keys()
 * @method StreamInterface values()
 * @method StreamInterface limit(int $n, bool $preserve_keys = false)
 * @method StreamInterface map(callable $by = fn(mixed $x, mixed $k): mixed => !!$x, bool $preserve_keys = false)
 * @method StreamInterface partition(callable $by = fn(mixed $x, mixed $k): bool => !!$x)
 * @method StreamInterface randomN(int $n = 1)
 * @method StreamInterface reject(callable $by = fn(mixed $x, mixed $k): bool => !!$x, bool $preserve_keys = false)
 * @method StreamInterface skip(int $n, bool $preserve_keys = false)
 * @method StreamInterface shuffle()
 * @method StreamInterface sort(callable $fn = fn(mixed $a, mixed $b): int, bool $preserve_keys = false)
 *
 * @method bool allMatch(callable $by = fn(mixed $x, mixed $k): bool => !!$x)
 * @method bool anyMatch(callable $by = fn(mixed $x, mixed $k): bool => !!$x)
 * @method array collect()
 * @method bool contains(mixed $v) 
 * @method bool hasKey(mixed $k)
 * @method bool hasKeys(array $keys)
 * @method int count()
 * @method mixed findFirst(callable $by = fn(mixed $x, mixed $k): bool => !!$x)
 * @method mixed findLast(callable $by = fn(mixed $x, mixed $k): bool => !!$x)
 * @method mixed max(callable $comp = fn(mixed $a, mixed $b): int => $a - $b)
 * @method mixed min(callable $comp = fn(mixed $a, mixed $b): int => $a - $b)
 * @method \stdClass object()
 * @method mixed random()
 * @method mixed[] shuffled()
 * @method mixed reduce(callable $by = fn(mixed $acc, mixed $value, mixed $key): mixed => $acc + $value)
 * 
 * @package Moteam\Stream
 */
class Stream implements StreamInterface {
    /**
     * Each input transformed into this
     * @var \Iterator|\ArrayIterator|\Generator|mixed|\Traversable|null
     */
    protected ?\Iterator $iterator = null;
    /**
     * Function that mutates input data in this current object
     * @var \Closure|callable|null
     */
    protected ?\Closure $mutator = null;
    /**
     * Parameters to use in mutation
     * @var array|mixed|null
     */
    protected ?array $parameters = null;

    /**
     * Factory method for actual streams creation
     * Stream::of([1,2,3,4,5])
     * ->filter(fn($x) => !!$x)
     * ->map(fn($x) => $x * 2)
     * ->foreach(function() use($logger) { $logger->info($x); })
     * () // ~ ->collect()
     * @param $of <p>array, iterable, ArrayObject, IteratorAggregate, Generator, Iterator, Traversable or another Stream</p>
     * @param callable|null $mutator <p>callable to mutate provided input</p>
     * @param ...$parameters
     * @return static
     * @throws \Exception
     */
    public static function of($of, callable $mutator = null, ...$parameters): self {
        return new static($of, $mutator, $parameters);
    }

    /**
     * Actually stream data
     * @return \Iterator
     */
    public function stream(): \Iterator { yield from $this->iterator; }

    /**
     * Protected, call Stream::of to do stuff
     * @param $of
     * @param callable|null $mutator
     * @param array $parameters
     * @throws \InvalidArgumentException|\Exception
     */
    final protected function __construct($of, callable $mutator = null, array $parameters = []) {
        if(\is_array($of)) {
            $this->iterator = (function() use($of) { yield from $of; })();
        } else if(\is_object($of)) {
            switch(true) {
                case $of instanceof StreamInterface:
                    $this->iterator = $of->stream();
                    break;
                case $of instanceof \Traversable:
                case $of instanceof \Iterator:
                case \is_iterable($of):
                case $of instanceof \Generator:
                    $this->iterator = $of;
                    break;
                case $of instanceof \IteratorAggregate:
                case $of instanceof \ArrayObject:
                    $this->iterator = $of->getIterator();
                    break;
                default:
                    $this->iterator = (function() use($of) { 
                        foreach((array)$of as $k => $v) {
                            yield $k => $v;
                        } 
                    })();
                    break;
            }
        }

        if($mutator instanceof \Closure) {
            $this->mutator = $mutator;
        } else if($mutator && \is_callable($mutator)) {
            $this->mutator = \Closure::fromCallable($mutator);
        }
        $this->parameters = $parameters;
    }

    /**
     * Hook to obtain validated mutator function in prebuilt and extension Stream classes
     * @return callable | null
     * @throws \InvalidArgumentException
     */
    protected function useMutator(bool $optional = false) {
        if($optional && !$this->mutator) {
            return null;
        }
        if(!$this->mutator || !\is_callable($this->mutator)) {
            throw new \InvalidArgumentException();
        }
        return $this->mutator;
    }

    /**
     * Hook to obtain validated parameters for mutator in prebuilt and extension Stream classes.
     * Validates and returns clean data gotten from user input, Exception is thrown if data is invalid
     * @param ...$specs <p>Parameters description as [[callable, mixed|null], ...] - pairs of (validator, defaultValue)
     *                  validators - list of validator functions, e.g. is_int, is_string, ...; should all return non nullish to pass
     *                  defaultValue = mixed|null, optional default value if not provided input, if null - value is required
     *                  </p>
     * @return array <p>Array of expected values</p>
     * @throws \InvalidArgumentException
     */
    protected function useParameters(...$specs): array {
        return use_parameters($this->parameters, ...$specs);
    }

    /**
     * Processes actual chaining usage, returning next immutable Stream in chain of actions
     * May be a mutation or a terminal
     * @param string $name
     * @param array $parameters
     * @return StreamInterface|TerminalInterface
     * @throws \Exception
     */
    public function __call(string $name, array $parameters) {
        if(\class_exists("\\Moteam\\Stream\\Library\\Terminals\\{$name}Terminal", true)) {
            /** @var TerminalInterface $klass */
            $klass = "\\Moteam\\Stream\\Library\\Terminals\\{$name}Terminal";
            return ($klass::of($this))(...$parameters);
        }

        if(!\class_exists("\\Moteam\\Stream\\Library\\Streams\\{$name}Stream")) {
            throw new \BadMethodCallException();
        }
        /** @var StreamInterface $klass */
        $klass = "\\Moteam\\Stream\\Library\\Streams\\{$name}Stream";

        $new_iterator = (function() {
            yield from $this->stream();
        })();

        if(count($parameters) > 0) {
            if(!\is_callable($parameters[0])) {
                return $klass::of($new_iterator, null, ...$parameters);
            } else {
                return $klass::of($new_iterator, ...$parameters);
            }
        } else {
            return $klass::of($new_iterator);
        }
    }

    /**
     * This is a shortcut to collect() terminal call
     * @return mixed[]
     */
    public function __invoke() {
        return $this->collect();
    }
}
