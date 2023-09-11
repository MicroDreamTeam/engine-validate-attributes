<?php

namespace Itwmw\Validate\Attributes\Rules;

/**
 * 验证的字段值必须与指定字段的值不同。
 *
 * @see https://v.neww7.com/4/BuiltRule.html#different-field
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
class Different implements RuleInterface
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
