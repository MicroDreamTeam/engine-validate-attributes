<?php

namespace Itwmw\Validate\Attributes\Rules;

/**
 * 被验证的字段必须是数字，并且必须包含指定的小数位数。
 *
 * @see https://v.neww7.com/4/BuiltRule.html#decimal-min-max
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
class Decimal implements RuleInterface
{
    protected array $args = [];

    public function __construct(int $min, int $max = null)
    {
        $this->args = func_get_args();
    }

    public function getArgs(): array
    {
        return $this->args;
    }
}
