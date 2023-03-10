<?php

namespace Itwmw\Validate\Attributes\Rules;

use Attribute;

/**
 * 如果验证中的字段存在，则 *anotherfield* 中不能存在任何字段，即使该字段为空。
 *
 * @see https://v.neww7.com/4/BuiltRule.html#prohibits-anotherfield
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class Prohibits implements RuleInterface
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
