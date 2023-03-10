<?php

namespace Itwmw\Validate\Attributes\Rules;

use Attribute;

/**
 * 待验证字段可能包含中文、字母和数字。
 *
 * @see https://v.neww7.com/4/BuiltRule.html#chs-alpha-num
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class ChsAlphaNum implements RuleInterface
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
