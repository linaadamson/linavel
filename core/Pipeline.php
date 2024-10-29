<?php

namespace Core;

use Closure;

class Pipeline
{
    protected $passable;
    protected $pipes = [];
    protected $method = 'handle';

    public function __construct($passable)
    {
        $this->passable = $passable;
    }

    public function through($pipes)
    {
        $this->pipes = $pipes;
        return $this;
    }

    public function via($method)
    {
        $this->method = $method;
    }

    public function then(Closure $destination)
    {
        $pipeline = array_reduce(
            array_reverse($this->pipes),
            $this->carry(),
            $this->prepareDestination($destination)
        );

        return $pipeline($this->passable);
    }

    public function thenReturn()
    {
        return $this->then(function ($passable) {
            return $passable;
        });
    }

    protected function carry()
    {
        return function ($stack, $pipe) {
            return function ($passable) use ($stack, $pipe) {
                if (is_callable($pipe)) {
                    return $pipe($passable, $stack);
                } elseif (is_object($pipe)) {
                    return $pipe->{$this->method}($passable, $stack);
                } elseif (class_exists($pipe) && method_exists($pipe, $this->method)) {
                    $pipeInstance = new $pipe;
                    return $pipeInstance->{$this->method}($passable, $stack);
                } else {
                    throw new \Exception('Pipe type not supported', 500);
                }
            };
        };
    }

    protected function prepareDestination(Closure $destination)
    {
        return function ($passable) use ($destination) {
            try {
                return $destination($passable);
            } catch (\Exception $err) {
                throw new \Exception($err->getMessage(), 500);
            }
        };
    }
}