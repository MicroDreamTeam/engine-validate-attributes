<?php

namespace Itwmw\Validate\Attributes\Rules;

/**
 * 验证的字段必须是合法的中国手机号码。
 *
 * @see https://v.neww7.com/4/BuiltRule.html#mobile
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
class Mobile implements RuleInterface
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
