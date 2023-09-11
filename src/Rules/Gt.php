<?php

namespace Itwmw\Validate\Attributes\Rules;

/**
 * 验证字段必须大于给定的 *field*。两个字段必须是相同的类型。字符串、数字、数组和文件都使用 {@see Size} 进行相同的评估。
 *
 * https://v.neww7.com/4/BuiltRule.html#gt-field
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
class Gt implements RuleInterface
{
    protected array $args = [];

    public function __construct(string $field)
    {
        $this->args = func_get_args();
    }

    public function getArgs(): array
    {
        return $this->args;
    }
}
