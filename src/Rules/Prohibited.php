<?php

namespace Itwmw\Validate\Attributes\Rules;

use Attribute;

/**
 * 被验证的字段必须为空或不存在。
 *
 * @see https://v.neww7.com/4/BuiltRule.html#prohibited
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class Prohibited implements RuleInterface
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
