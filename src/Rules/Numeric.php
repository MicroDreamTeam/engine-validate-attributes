<?php

namespace Itwmw\Validate\Attributes\Rules;

/**
 * 验证字段必须为 {@see https://www.php.net/manual/en/function.is-numeric.php 数值}。
 *
 * @see https://v.neww7.com/4/BuiltRule.html#numeric
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
class Numeric implements RuleInterface
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
