<?php

namespace Itwmw\Validate\Attributes\Rules;

use Attribute;

/**
 * 验证字段必须是有效的 RFC 4122（版本 1，3，4 或 5）通用唯一标识符（UUID）。
 *
 * @see https://v.neww7.com/4/BuiltRule.html#uuid
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class Uuid implements RuleInterface
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
