<?php

namespace Itwmw\Validate\Attributes\Rules;

use Attribute;

/**
 * 被验证的字段必须为数值且长度不可大于*value*。
 *
 * @see https://v.neww7.com/4/BuiltRule.html#max-digits-value
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class MaxDigits implements RuleInterface
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
