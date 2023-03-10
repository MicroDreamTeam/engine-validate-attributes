<?php

namespace Itwmw\Validate\Attributes\Rules;

use Attribute;

/**
 * 验证字段必须是 *value* 的倍数。
 *
 * @see https://v.neww7.com/4/BuiltRule.html#multiple-of-value
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class MultipleOf implements RuleInterface
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
