<?php

namespace Itwmw\Validate\Attributes\Rules;

use Attribute;

/**
 * 待验证字段可能包含中文、英文字母、数字、短破折号（-）和下划线（\_）。
 *
 * @see https://v.neww7.com/4/BuiltRule.html#chs-dash
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class ChsDash implements RuleInterface
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
