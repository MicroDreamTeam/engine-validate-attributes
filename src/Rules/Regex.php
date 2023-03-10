<?php

namespace Itwmw\Validate\Attributes\Rules;

use Attribute;

/**
 * 验证字段必须与给定的正则表达式匹配。
 *
 * 验证时，这个规则使用 PHP 的 `preg_match` 函数。 指定的模式应遵循 `preg_match` 所需的相同格式，也包括有效的分隔符。 例如： `'email' => 'not_regex:/^.+$/i'` 。
 *
 * @see https://v.neww7.com/4/BuiltRule.html#regex-pattern
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class Regex implements RuleInterface
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
