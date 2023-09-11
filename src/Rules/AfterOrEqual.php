<?php

namespace Itwmw\Validate\Attributes\Rules;

/**
 * 待验证字段的值对应的日期必须在给定日期之后或与给定的日期相同。可参阅 {@see After} 规则获取更多信息。
 *
 * @see https://v.neww7.com/4/BuiltRule.html#after-or-equal-date
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
class AfterOrEqual implements RuleInterface
{
    protected array $args = [];

    public function __construct(string $date)
    {
        $this->args = func_get_args();
    }

    public function getArgs(): array
    {
        return $this->args;
    }
}
