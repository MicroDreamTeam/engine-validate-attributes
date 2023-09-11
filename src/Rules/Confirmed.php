<?php

namespace Itwmw\Validate\Attributes\Rules;

/**
 * 验证字段必须具有匹配字段 `{field}_confirmation` 。例如，验证字段为 `password` ，输入中必须存在与之匹配的 `password_confirmation` 字段。
 *
 * @see https://v.neww7.com/4/BuiltRule.html#confirmed
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
class Confirmed implements RuleInterface
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
