<?php
namespace Moteam\Stream\Library\Terminals;

use Moteam\Stream\Stream;
use function Moteam\Stream\Library\use_parameters;

abstract class Terminal {
    public ?Stream $stream = null;
    public function __construct(Stream $stream) { $this->stream = $stream; }
    public static function of(Stream $stream): self { return new static($stream); }
    protected function useParameters($parameters, ...$specs): array {
        return use_parameters($parameters, ...$specs);
    }

    abstract public function __invoke(...$parameters);
}
