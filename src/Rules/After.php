<?php

namespace Itwmw\Validate\Attributes\Rules;

use Attribute;

/**
 * 待验证字段必须是给定的日期之后的值对应的日期。日期将被传递给 PHP 函数 `strtotime`，以便转换为有效的 `DateTime` 实例：
 *
 * ```php
 * 'start_date' => 'required|date|after:tomorrow'
 * ```
 *
 * 你亦可指定另一个要与日期比较的字段，而不是传递要由 `strtotime` 处理的日期字符串：
 *
 * ```php
 * 'finish_date' => 'required|date|after:start_date'
 * ```
 *
 * @see https://v.neww7.com/4/BuiltRule.html#after-date
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class After implements RuleInterface
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
