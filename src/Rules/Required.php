<?php

namespace Itwmw\Validate\Attributes\Rules;

use Attribute;

/**
 * 验证的字段必须存在于输入数据中，而不是空。如果满足以下条件之一，则字段被视为「空」：
 *
 * - 值为 `null`。
 * - 值为空字符串。
 * - 值为空数组或空 `Countable` 对象。
 * - 值为无路径的上传文件。
 *
 * @see https://v.neww7.com/4/BuiltRule.html#required
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class Required implements RuleInterface
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
