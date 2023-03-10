<?php

namespace Itwmw\Validate\Attributes\Rules;

use Attribute;

/**
 * 验证字段必须与给定的 *value* 大小一致。对于字符串，*value* 对应字符数。对于数字，*value* 对应给定的整数值（该属性必须有 `numeric` 或者 `integer` 规则）。
 *
 * 对于数组，*size* 对应数组的 `count` 值。对于文件，*size* 对应文件大小（单位 kB）。
 *
 * `size`规则同样也支持其他字段，需要对比判断时，直接写入其他字段名称即可
 *
 * @see https://v.neww7.com/4/BuiltRule.html#size-value
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class Size implements RuleInterface
{
    protected array $args = [];

    public function __construct(string|int $value)
    {
        $this->args = func_get_args();
    }

    public function getArgs(): array
    {
        return $this->args;
    }
}
