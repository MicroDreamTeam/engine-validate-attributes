<?php

namespace Itwmw\Validate\Attributes;

use Attribute;
use JetBrains\PhpStorm\ExpectedValues;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class Rule
{
    public const TYPE_NORMAL = 0;

    public const TYPE_IMPLICIT = 1;

    public const TYPE_DEPENDENT = 2;

    public function __construct(
        public readonly string $name,
        public readonly string|array $args = [],
        #[ExpectedValues(valuesFromClass: self::class)]
        public readonly int $type = self::TYPE_NORMAL
    ) {
    }

    public function getRule(): string
    {
        if (!empty($this->args)) {
            if (!is_array($this->args)) {
                return $this->name . ':' . $this->args;
            }
            return $this->name . ':' . implode(',', $this->args);
        }
        return $this->name;
    }
}
