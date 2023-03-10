<?php

namespace Itwmw\Validate\Attributes\Rules;

use Attribute;

/**
 * 验证字段必须是一个字符串。如果允许这个字段为 `null`，需要给这个字段分配 {@see Nullable} 规则。
 *
 * @see https://v.neww7.com/4/BuiltRule.html#string
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class StringRule implements RuleInterface
{
    protected array $args = [];

    public function __construct()
    {
        $this->args = func_get_args();
    }

    public function getArgs(): array
    {
        return $this->args;
    }
}
