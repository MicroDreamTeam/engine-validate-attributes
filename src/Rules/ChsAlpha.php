<?php

namespace Itwmw\Validate\Attributes\Rules;

/**
 * 待验证字段只能由中文和字母组成。
 *
 * @see https://v.neww7.com/4/BuiltRule.html#chs-alpha
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
class ChsAlpha implements RuleInterface
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
