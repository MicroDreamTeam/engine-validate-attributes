<?php

namespace Itwmw\Validate\Attributes\Rules;

use Attribute;

/**
 * 验证字段必须以给定值之一开头。
 *
 * @see https://v.neww7.com/4/BuiltRule.html#starts-with-foo-bar
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class StartsWith implements RuleInterface
{
    protected array $args = [];

    public function __construct(mixed ...$values)
    {
        $this->args = func_get_args();
    }

    public function getArgs(): array
    {
        return $this->args;
    }
}
