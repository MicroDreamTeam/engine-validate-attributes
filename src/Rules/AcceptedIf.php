<?php

namespace Itwmw\Validate\Attributes\Rules;

/**
 * 如果另一个正在验证的字段等于指定的值，则验证中的字段必须为 `"yes"` ，`"on"` ，`1` 或 `true`，这对于验证「服务条款」接受或类似字段很有用。
 *
 * @see https://v.neww7.com/4/BuiltRule.html#accepted-if-anotherfield-value
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
class AcceptedIf implements RuleInterface
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
