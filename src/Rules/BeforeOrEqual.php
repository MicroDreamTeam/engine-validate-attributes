<?php

namespace Itwmw\Validate\Attributes\Rules;

/**
 * 验证字段必须是在给定日期之前或与之相同的日期。这个日期值将会被传递给 PHP 的 `strtotime` 函数来计算。
 *
 * 除此之外，像 {@see After} 规则一样，验证中另一个字段的名称可以作为值传递给 `date`。
 *
 * @see https://v.neww7.com/4/BuiltRule.html#before-or-equal-date
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
class BeforeOrEqual implements RuleInterface
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
