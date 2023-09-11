<?php

namespace Itwmw\Validate\Attributes\Rules;

/**
 * 验证的字段必须为 *numeric*，并且必须具有确切长度。
 *
 * @see https://v.neww7.com/4/BuiltRule.html#digits-value
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
class Digits implements RuleInterface
{
    protected array $args = [];

    public function __construct(int $length)
    {
        $this->args = func_get_args();
    }

    public function getArgs(): array
    {
        return $this->args;
    }
}
