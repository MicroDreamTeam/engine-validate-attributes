<?php

namespace Itwmw\Validate\Attributes\Rules;

/**
 * 如果 *anotherfield* 不在表单数据中，`check` 方法中会排除掉当前的字段。
 *
 * @see https://v.neww7.com/4/BuiltRule.html#exclude-without-anotherfield
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
class ExcludeWithout implements RuleInterface
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
