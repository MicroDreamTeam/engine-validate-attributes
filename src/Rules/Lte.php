<?php

namespace Itwmw\Validate\Attributes\Rules;

use Attribute;

/**
 * 验证中的字段必须小于或等于给定的 字段 。这两个字段必须是相同的类型。字符串、数值、数组和文件大小的计算方式与 {@see Size} 方法进行评估。
 *
 * @see https://v.neww7.com/4/BuiltRule.html#lte-field
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class Lte implements RuleInterface
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
