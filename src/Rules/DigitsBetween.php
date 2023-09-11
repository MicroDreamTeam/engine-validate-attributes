<?php

namespace Itwmw\Validate\Attributes\Rules;

/**
 * 验证的字段必须为 *numeric*，并且长度必须在给定的 *min* 和 *max* 之间。
 *
 * @see https://v.neww7.com/4/BuiltRule.html#digits-between-min-max
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
class DigitsBetween implements RuleInterface
{
    protected array $args = [];

    public function __construct(int $min, int $max)
    {
        $this->args = func_get_args();
    }

    public function getArgs(): array
    {
        return $this->args;
    }
}
