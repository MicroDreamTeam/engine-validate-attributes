<?php

namespace Itwmw\Validate\Attributes\Rules;

/**
 * 除非 *anotherfield* 等于 *value* ，否则 `check` 方法中会排除掉当前的字段。
 *
 * @see https://v.neww7.com/4/BuiltRule.html#exclude-unless-anotherfield-value
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
class ExcludeUnless implements RuleInterface
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
