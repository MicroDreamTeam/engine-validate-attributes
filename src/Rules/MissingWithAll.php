<?php

namespace Itwmw\Validate\Attributes\Rules;

use Attribute;

/**
 * 只有当所有其他指定的字段都存在时，验证中的字段才必须不存在。
 *
 * @see https://v.neww7.com/4/BuiltRule.html#missing-with-all-foo-bar
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class MissingWithAll implements RuleInterface
{
    protected array $args = [];

    public function __construct(string ...$another_field)
    {
        $this->args = func_get_args();
    }

    public function getArgs(): array
    {
        return $this->args;
    }
}
