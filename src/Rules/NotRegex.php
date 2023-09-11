<?php

namespace Itwmw\Validate\Attributes\Rules;

/**
 * 验证字段必须与给定的正则表达式不匹配。
 *
 * 验证时，这个规则使用 PHP `preg_match` 函数。指定的模式应遵循 `preg_match` 所需的相同格式，也包括有效的分隔符。 例如： `'email' => 'not_regex:/^.+$/i'`。
 *
 * @see https://v.neww7.com/4/BuiltRule.html#not-regex-pattern
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
class NotRegex implements RuleInterface
{
    protected array $args = [];

    public function __construct(string $regex)
    {
        $this->args = func_get_args();
    }

    public function getArgs(): array
    {
        return $this->args;
    }
}
