<?php

namespace Itwmw\Validate\Attributes\Rules;

use Attribute;

/**
 * 验证的字段必须存在于 *anotherfield* 的值中。
 *
 * @see https://v.neww7.com/4/BuiltRule.html#in-array-anotherfield
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class InArray implements RuleInterface
{
    protected array $args = [];

    public function __construct(string $another_field)
    {
        $this->args = func_get_args();
    }

    public function getArgs(): array
    {
        return $this->args;
    }
}
