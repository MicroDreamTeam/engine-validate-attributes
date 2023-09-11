<?php

namespace Itwmw\Validate\Attributes\Rules;

/**
 * 被验证的字段必须为数值且长度不能小于*value*。
 *
 * @see https://v.neww7.com/4/BuiltRule.html#min-digits-value
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
class MinDigits implements RuleInterface
{
    protected array $args = [];

    public function __construct(int $value)
    {
        $this->args = func_get_args();
    }

    public function getArgs(): array
    {
        return $this->args;
    }
}
