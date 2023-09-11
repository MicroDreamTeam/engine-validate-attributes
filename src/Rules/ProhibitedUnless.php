<?php

namespace Itwmw\Validate\Attributes\Rules;

/**
 * 验证中的字段必须为空或不存在，除非 *anotherfield* 字段等于 value 。
 *
 * @see https://v.neww7.com/4/BuiltRule.html#prohibited-unless-anotherfield-value
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
class ProhibitedUnless implements RuleInterface
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
