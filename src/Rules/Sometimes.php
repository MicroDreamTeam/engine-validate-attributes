<?php

namespace Itwmw\Validate\Attributes\Rules;

use Attribute;

/**
 * 存在时则验证
 *
 * 在某些情况下，你可能希望将要验证的字段存在于输入数组中时，才对该字段执行验证。可以在规则列表中增加 `sometimes` 来实现：
 *
 * @see https://v.neww7.com/4/BuiltRule.html#%E5%AD%98%E5%9C%A8%E6%97%B6%E5%88%99%E9%AA%8C%E8%AF%81
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class Sometimes implements RuleInterface
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
