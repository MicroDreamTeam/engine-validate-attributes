<?php

namespace Itwmw\Validate\Attributes\Rules;

use Attribute;

/**
 * 如果 *anotherfield* 字段等于任何值，则验证中的字段必须为空或不存在。
 *
 * @see https://v.neww7.com/4/BuiltRule.html#prohibited-if-anotherfield-value
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class ProhibitedIf implements RuleInterface
{
    protected array $args = [];

    public function __construct(string $another_field, mixed $value)
    {
        $this->args = func_get_args();
    }

    public function getArgs(): array
    {
        return $this->args;
    }
}
