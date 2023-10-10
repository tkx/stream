<?php declare(strict_types=1);
namespace Moteam\Stream;

use ArrayIterator;
use ArrayObject;
use BadMethodCallException;
use Closure;
use Exception;
use Generator;
use InvalidArgumentException;
use Iterator;
use IteratorAggregate;
use Moteam\Stream\Library\Terminals\Terminal;
use Traversable;
use function is_array;
use function is_object;
use function \Moteam\Stream\Library\use_parameters;

/**
 * Basic class that transforms anything traversable into generator-backed values stream.
 *
 * @method concat(mixed $source, bool $preserve_keys = false): Stream
 * @method concatBefore(mixed $source, bool $preserve_keys = false): Stream
 * @method countBy(callable $by = fn(mixed $x, mixed $k): mixed => !!$x): Stream
 * @method distinct(int $limit, bool $preserve_keys = false): Stream
 * @method enrich(callable $with = fn(array $data): Iterator => yield from $data): Stream
 * @method filter(callable $by = fn(mixed $x, mixed $k): bool => !!$x, bool $preserve_keys = false): Stream
 * @method foreach(callable $do = function(mixed $x, mixed $k): void {}): Stream
 * @method forall(callable $do = function(mixed[] $values): void {}): Stream
 * @method groupBy(callable $by = fn(mixed $x, mixed $k): mixed => !!$x, bool $preserve_keys = false): Stream
 * @method mapAll(callable $by = fn(mixed $x, mixed $k, mixed $k0): mixed => $x): Stream
 * @method indexBy(string|int $x): Stream
 * @method keys(): Stream
 * @method values(): Stream
 * @method limit(int $n, bool $preserve_keys = false): Stream
 * @method map(callable $by = fn(mixed $x, mixed $k): mixed => !!$x, bool $preserve_keys = false): Stream
 * @method partition(callable $by = fn(mixed $x, mixed $k): bool => !!$x): Stream
 * @method randomN(int $n = 1): Stream
 * @method reject(callable $by = fn(mixed $x, mixed $k): bool => !!$x, bool $preserve_keys = false): Stream
 * @method skip(int $n, bool $preserve_keys = false): Stream
 * @method shuffle(): Stream
 * @method sort(callable $fn = fn(mixed $a, mixed $b): int, bool $preserve_keys = false): Stream
 *
 * @method allMatch(callable $by = fn(mixed $x, mixed $k): bool => !!$x): bool
 * @method anyMatch(callable $by = fn(mixed $x, mixed $k): bool => !!$x): bool
 * @method collect(): array
 * @method contains(mixed $v): bool
 * @method has(mixed $k): bool
 * @method count(): int
 * @method findFirst(callable $by = fn(mixed $x, mixed $k): bool => !!$x): mixed
 * @method findLast(callable $by = fn(mixed $x, mixed $k): bool => !!$x): mixed
 * @method max(callable $comp = fn(mixed $a, mixed $b): int => $a - $b): mixed
 * @method min(callable $comp = fn(mixed $a, mixed $b): int => $a - $b): mixed
 * @method object(): \stdClass
 * @method random(): mixed
 * @method shuffled(): mixed[]
 * @method reduce(callable $by = fn(mixed $acc, mixed $value, mixed $key): mixed => $acc + $value): mixed
 * 
 * @package Moteam\Stream
 */
class Stream {
    /**
     * Each input transformed into this
     * @var Iterator|ArrayIterator|Generator|mixed|Traversable|null
     */
    protected ?Iterator $iterator = null;
    /**
     * Function that mutates input data in this current object
     * @var Closure|callable|null
     */
    protected ?Closure $mutator = null;
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
     * @throws Exception
     */
    public static function of($of, callable $mutator = null, ...$parameters): self {
        return new static($of, $mutator, $parameters);
    }

    /**
     * Actually stream data
     * @return Iterator
     */
    public function stream(): Iterator { yield from $this->iterator; }

    /**
     * Protected, call Stream::of to do stuff
     * @param $of
     * @param callable|null $mutator
     * @param array $parameters
     * @throws InvalidArgumentException|Exception
     */
    final protected function __construct($of, callable $mutator = null, array $parameters = []) {
        if(is_array($of)) {
            $this->iterator = (function() use($of) { yield from $of; })();
        } else if(is_object($of)) {
            switch(true) {
                case $of instanceof Stream:
                    $this->iterator = $of->stream();
                    break;
                case $of instanceof Traversable:
                case $of instanceof Iterator:
                case is_iterable($of):
                case $of instanceof Generator:
                    $this->iterator = $of;
                    break;
                case $of instanceof IteratorAggregate:
                case $of instanceof ArrayObject:
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

        if($mutator instanceof Closure) {
            $this->mutator = $mutator;
        } else if($mutator && is_callable($mutator)) {
            $this->mutator = Closure::fromCallable($mutator);
        }
        $this->parameters = $parameters;
    }

    /**
     * Hook to obtain validated mutator function in prebuilt and extension Stream classes
     * @return callable | null
     */
    protected function useMutator(bool $optional = false) {
        if($optional && !$this->mutator) {
            return null;
        }
        if(!$this->mutator || !is_callable($this->mutator)) {
            throw new InvalidArgumentException();
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
     * @throws InvalidArgumentException
     */
    protected function useParameters(...$specs): array {
        return use_parameters($this->parameters, ...$specs);
    }

    /**
     * Processes actual chaining usage, returning next immutable Stream in chain of actions
     * May be a mutation or a terminal
     * @param string $name
     * @param array $parameters
     * @return Stream|Terminal
     * @throws Exception
     */
    public function __call(string $name, array $parameters) {
        if(class_exists("\\Moteam\\Stream\\Library\\Terminals\\{$name}Terminal", true)) {
            /** @var Terminal $klass */
            $klass = "\\Moteam\\Stream\\Library\\Terminals\\${name}Terminal";
            return ($klass::of($this))(...$parameters);
        }

        if(!class_exists("\\Moteam\\Stream\\Library\\Mutators\\{$name}Stream")) {
            throw new BadMethodCallException();
        }
        /** @var Stream $klass */
        $klass = "\\Moteam\\Stream\\Library\\Mutators\\{$name}Stream";

        $new_iterator = (function() {
            yield from $this->stream();
        })();

        if(count($parameters) > 0) {
            if(!is_callable($parameters[0])) {
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
