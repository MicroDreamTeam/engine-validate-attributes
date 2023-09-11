<?php

namespace Itwmw\Validate\Attributes\Rules;

/**
 * 在其他指定任一字段不出现时，验证的字段才必须存在且不为空。
 *
 * @see https://v.neww7.com/4/BuiltRule.html#required-without-foo-bar
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
class RequiredWithout implements RuleInterface
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
