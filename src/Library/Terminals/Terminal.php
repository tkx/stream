<?php
namespace Moteam\Stream\Library\Terminals;

use Moteam\Stream\Stream;

abstract class Terminal {
    public ?Stream $stream = null;
    public function __construct(Stream $stream) { $this->stream = $stream; }
    public static function of(Stream $stream): self { return new static($stream); }
    protected function useParameters($parameters, ...$specs): array {
        $values = [];
        foreach($specs as $i => $spec) {
            [$validators, $default] = $spec;
            if(array_key_exists($i, $parameters)) {
                if(!is_array($validators)) {
                    $validators = [$validators];
                }
                foreach($validators as $validator) {
                    if (\is_callable($validator) && !call_user_func($validator, $parameters[$i])) {
                        throw new \InvalidArgumentException();
                    }
                    if (!\is_callable($validator)) {
                        throw new \InvalidArgumentException();
                    }
                }
                $values[] = $parameters[$i];
            } else {
                if($default === null) {
                    throw new \InvalidArgumentException();
                }
                $values[] = $default;
            }
        }
        return $values;
    }

    abstract public function __invoke(...$parameters);
}
