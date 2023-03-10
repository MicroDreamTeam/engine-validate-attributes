<?php

namespace Itwmw\Validate\Attributes\Rules;

use Attribute;

/**
 * 验证的字段必须是有效的 JSON 字符串。
 *
 * @see https://v.neww7.com/4/BuiltRule.html#json
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class Json implements RuleInterface
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
