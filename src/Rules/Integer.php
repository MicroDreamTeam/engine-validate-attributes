<?php

namespace Itwmw\Validate\Attributes\Rules;

/**
 * 验证的字段必须是整数。
 *
 * 注意：此种验证规则不是验证数据是 「integer」 类型，仅验证输入为 PHP 函数 `FILTER_VALIDATE_INT` 规则接受的类型。如果你需要验证输入为数字，请将此规则与 {@see Numeric} 结合使用。
 *
 * @see https://v.neww7.com/4/BuiltRule.html#integer
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
class Integer implements RuleInterface
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
