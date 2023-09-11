<?php

namespace Itwmw\Validate\Attributes\Rules;

/**
 * 根据 PHP `strtotime` 函数，验证的字段必须是有效的日期。
 *
 * @see https://v.neww7.com/4/BuiltRule.html#date
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
class Date implements RuleInterface
{
    protected array $args = [];

    public function __construct()
    {
        $this->args = func_get_args();
    }

    public function getArgs(): array
    {
        return $this->args;
    }
}
