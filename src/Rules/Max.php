<?php

namespace Itwmw\Validate\Attributes\Rules;

use Attribute;

/**
 * 验证中的字段必须小于或等于 *value*。字符串、数字、数组或是文件大小的计算方式都用 {@see Size} 规则。
 *
 * https://v.neww7.com/4/BuiltRule.html#max-value
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class Max implements RuleInterface
{
    protected array $args = [];

    public function __construct(int $value)
    {
        $this->args = func_get_args();
    }

    public function getArgs(): array
    {
        return $this->args;
    }
}
