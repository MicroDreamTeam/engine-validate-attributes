<?php

namespace Itwmw\Validate\Attributes\Rules;

/**
 * 验证的字段必须以给定的值之一结尾。
 *
 * @see https://v.neww7.com/4/BuiltRule.html#ends-with-foo-bar
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
class EndsWith implements RuleInterface
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
