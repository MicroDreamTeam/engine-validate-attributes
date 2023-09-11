<?php

namespace Itwmw\Validate\Attributes\Rules;

/**
 * 验证字段必须等于给定日期。日期将传递到 PHP `strtotime` 函数中，以便转换为有效的 `DateTime` 实例。
 *
 * @see https://v.neww7.com/4/BuiltRule.html#date-equals-date
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
class DateEquals implements RuleInterface
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
