<?php

namespace Itwmw\Validate\Attributes\Rules;

/**
 * 如果 _anotherfield_ 字段等于 _value_，则正在验证的字段必须不存在。
 *
 * @see https://v.neww7.com/4/BuiltRule.html#missing-if-anotherfield-value
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
class MissingIf implements RuleInterface
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
