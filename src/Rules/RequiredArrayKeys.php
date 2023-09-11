<?php

namespace Itwmw\Validate\Attributes\Rules;

/**
 * 被验证的字段必须是一个数组，并且必须包含指定的键。
 *
 * @see https://v.neww7.com/4/BuiltRule.html#required-array-keys-foo-bar
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
class RequiredArrayKeys implements RuleInterface
{
    protected array $args = [];

    public function __construct(string ...$fields)
    {
        $this->args = func_get_args();
    }

    public function getArgs(): array
    {
        return $this->args;
    }
}
