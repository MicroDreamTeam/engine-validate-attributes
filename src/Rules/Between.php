<?php

namespace Itwmw\Validate\Attributes\Rules;

/**
 * 验证字段的大小必须在给定的 min 和 max 之间。字符串、数字、数组和文件的计算方式都使用 {@see Size} 方法。
 *
 * @see https://v.neww7.com/4/BuiltRule.html#between-min-max
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
class Between implements RuleInterface
{
    protected array $args = [];

    public function __construct(int $min, int $max)
    {
        $this->args = func_get_args();
    }

    public function getArgs(): array
    {
        return $this->args;
    }
}
