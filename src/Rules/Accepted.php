<?php

namespace Itwmw\Validate\Attributes\Rules;

/**
 * 待验证字段必须是 `"yes"` ，`"on"` ，`1` 或 `true`。这对于验证「服务条款」的接受或类似字段时很有用。
 *
 * @see https://v.neww7.com/4/BuiltRule.html#accepted
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
class Accepted implements RuleInterface
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
