<?php

namespace Itwmw\Validate\Attributes\Rules;

use Attribute;

/**
 * 验证的字段必须可以转换为 Boolean 类型。 可接受的输入为 `true` ， `false` ， `1` ， `0` ， `"1"` 和 `"0"` 。
 *
 * @see https://v.neww7.com/4/BuiltRule.html#boolean
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class Boolean implements RuleInterface
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
