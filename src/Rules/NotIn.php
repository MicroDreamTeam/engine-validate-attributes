<?php

namespace Itwmw\Validate\Attributes\Rules;

/**
 * 验证字段不能包含在给定的值的列表中。
 *
 * @see https://v.neww7.com/4/BuiltRule.html#not-in-foo-bar
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
class NotIn implements RuleInterface
{
    protected array $args = [];

    public function __construct(...$args)
    {
        $this->args = func_get_args();
    }

    public function getArgs(): array
    {
        return $this->args;
    }
}
