<?php

namespace Itwmw\Validate\Attributes\Rules;

use Attribute;

/**
 * 验证的字段必须是 IPv6 地址。
 *
 * @see https://v.neww7.com/4/BuiltRule.html#ipv6
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class Ipv6 implements RuleInterface
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
