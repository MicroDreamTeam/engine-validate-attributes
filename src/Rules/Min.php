<?php

namespace Itwmw\Validate\Attributes\Rules;

use Attribute;

/**
 * 验证字段必须具有最小值 *value*。 字符串，数值，数组，文件大小的计算方式都与 {@see Size} 规则一致.
 *
 * @see https://v.neww7.com/4/BuiltRule.html#min-value
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class Min implements RuleInterface
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
