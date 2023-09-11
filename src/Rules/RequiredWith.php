<?php

namespace Itwmw\Validate\Attributes\Rules;

/**
 * 在其他任一指定字段出现时，验证的字段才必须存在且不为空。
 *
 * @see https://v.neww7.com/4/BuiltRule.html#required-with-foo-bar
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
class RequiredWith implements RuleInterface
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
