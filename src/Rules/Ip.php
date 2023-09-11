<?php

namespace Itwmw\Validate\Attributes\Rules;

/**
 * 验证的字段必须是 IP 地址。
 *
 * @see https://v.neww7.com/4/BuiltRule.html#ip
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
class Ip implements RuleInterface
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
