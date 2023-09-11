<?php

namespace Itwmw\Validate\Attributes\Rules;

/**
 * 验证字段必须为符合 PHP 函数 `timezone_identifiers_list` 所定义的有效时区标识。
 *
 * @see https://v.neww7.com/4/BuiltRule.html#timezone
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
class Timezone implements RuleInterface
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
