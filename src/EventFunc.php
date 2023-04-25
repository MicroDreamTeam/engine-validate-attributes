<?php

namespace Itwmw\Validate\Attributes;

class EventFunc
{
    protected array $args = [];

    public function __construct(
        public readonly string $method,
        ...$args
    ) {
        $this->args = $args;
    }

    public function getArgs(): array
    {
        return $this->args;
    }
}
