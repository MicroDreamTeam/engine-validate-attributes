<?php

namespace Itwmw\Validate\Attributes\Rules;

use Attribute;

/**
 * 验证字段的值必须与给定字段的值相同。
 *
 * @see https://v.neww7.com/4/BuiltRule.html#same-field
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class Same implements RuleInterface
{
    protected array $args = [];

    public function __construct(string $field)
    {
        $this->args = func_get_args();
    }

    public function getArgs(): array
    {
        return $this->args;
    }
}
