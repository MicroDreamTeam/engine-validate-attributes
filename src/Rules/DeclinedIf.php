<?php

namespace Itwmw\Validate\Attributes\Rules;

use Attribute;

/**
 * 如果指定的字段等于一个指定的值，那么当前字段必须是`"no"`, `"off"`, `0`, 或 `false`.
 *
 * @see https://v.neww7.com/4/BuiltRule.html#declined-if-anotherfield-value
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class DeclinedIf implements RuleInterface
{
    protected array $args = [];

    public function __construct(string $field, mixed $value)
    {
        $this->args = func_get_args();
    }

    public function getArgs(): array
    {
        return $this->args;
    }
}
