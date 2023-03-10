<?php

namespace Itwmw\Validate\Attributes\Rules;

use Attribute;

/**
 * 如果 *anotherfield* 等于 *value* ，`check` 方法中会排除掉当前的字段。
 *
 * @see https://v.neww7.com/4/BuiltRule.html#exclude-if-anotherfield-value
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class ExcludeIf implements RuleInterface
{
    protected array $args = [];

    public function __construct(string $another_field, mixed $value)
    {
        $this->args = func_get_args();
    }

    public function getArgs(): array
    {
        return $this->args;
    }
}
