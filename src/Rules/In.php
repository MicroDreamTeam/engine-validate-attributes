<?php

namespace Itwmw\Validate\Attributes\Rules;

use Attribute;

/**
 * 验证字段必须包含在给定的值列表中。
 *
 * @see https://v.neww7.com/4/BuiltRule.html#in-foo-bar
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class In implements RuleInterface
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
