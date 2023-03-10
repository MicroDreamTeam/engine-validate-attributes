<?php

namespace Itwmw\Validate\Attributes\Rules;

use Attribute;

/**
 * 验证时排除掉当前验证的字段。
 *
 * @see https://v.neww7.com/4/BuiltRule.html#exclude
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class Exclude implements RuleInterface
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
