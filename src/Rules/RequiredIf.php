<?php

namespace Itwmw\Validate\Attributes\Rules;

/**
 * 如果其它字段 `_anotherfield_` 为任意 *value*，则此验证字段必须存在且不为空。
 *
 * @see https://v.neww7.com/4/BuiltRule.html#required-if-anotherfield-value
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
class RequiredIf implements RuleInterface
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
