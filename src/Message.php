<?php

namespace Itwmw\Validate\Attributes;

use Attribute;
use Itwmw\Validate\Attributes\Rules\RuleInterface;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Message
{
    /**
     * @param string $name
     * @param array<RuleInterface,string> $messages
     */
    public function __construct(
        protected string $name = '',
        protected array $messages = [],
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getMessages(): array
    {
        return $this->messages;
    }
}
