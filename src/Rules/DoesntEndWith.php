<?php

namespace Itwmw\Validate\Attributes\Rules;

/**
 * 验证的字段不能以给定的任意值结束。
 *
 * @see https://v.neww7.com/4/BuiltRule.html#doesnt-end-with-foo-bar
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
class DoesntEndWith implements RuleInterface
{
    protected array $args = [];

    public function __construct(string ...$args)
    {
        $this->args = func_get_args();
    }

    public function getArgs(): array
    {
        return $this->args;
    }
}
