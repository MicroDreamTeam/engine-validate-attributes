<?php

namespace Itwmw\Validate\Attributes\Rules;

use Attribute;

/**
 * 验证字段是否为`email`格式
 *
 * @see https://v.neww7.com/4/BuiltRule.html#email
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class Email implements RuleInterface
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
