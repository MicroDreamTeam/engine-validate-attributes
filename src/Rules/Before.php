<?php

namespace Itwmw\Validate\Attributes\Rules;

/**
 * 待验证字段的值对应的日期必须在给定的日期之前。日期将会传递给 PHP 函数 `strtotime`。
 *
 * 此外，与 {@see After} 规则一致，可以将另外一个待验证的字段作为 `date` 的值。
 *
 * @see https://v.neww7.com/4/BuiltRule.html#before-date
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
class Before implements RuleInterface
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
