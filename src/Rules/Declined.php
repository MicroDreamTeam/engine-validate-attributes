<?php

namespace Itwmw\Validate\Attributes\Rules;

use Attribute;

/**
 * 被验证的字段必须是 `"no"`, `"off"`, `0`, 或 `false`.
 *
 * @see https://v.neww7.com/4/BuiltRule.html#declined
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class Declined implements RuleInterface
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
