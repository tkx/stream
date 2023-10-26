<?php
namespace Moteam\Stream\Library\Terminals;

use Moteam\Stream\Library\StreamInterface;
use Moteam\Stream\Library\TerminalInterface;
use Moteam\Stream\Stream;
use function Moteam\Stream\Library\use_parameters;

/**
 * Base class for terminal methods (i.e. returns a terminal value, which can not be further streamed)
 */
abstract class Terminal implements TerminalInterface {
    public ?StreamInterface $stream = null;
    final public function __construct(StreamInterface $stream) { $this->stream = $stream; }
    public static function of(StreamInterface $stream): self { return new static($stream); }
    protected function useParameters($parameters, ...$specs): array {
        return use_parameters($parameters, ...$specs);
    }

    abstract public function __invoke(...$parameters);
}
