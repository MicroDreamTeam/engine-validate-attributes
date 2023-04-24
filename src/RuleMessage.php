<?php

namespace Itwmw\Validate\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class RuleMessage
{
    public function __construct(
        protected string $message = '',
    ) {
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
