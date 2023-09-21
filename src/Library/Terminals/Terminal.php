<?php
namespace Stream\Library\Terminals;

use Stream\Stream;

abstract class Terminal {
    public ?Stream $stream = null;
    public function __construct(Stream $stream) { $this->stream = $stream; }
    public static function of(Stream $stream): self { return new static($stream); }
    abstract public function __invoke(...$parameters);
}
