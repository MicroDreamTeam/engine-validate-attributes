<?php

namespace Itwmw\Validate\Attributes\Rules;

use Attribute;

/**
 * 如果其它字段 `_anotherfield_` 被{@see Accepted}时，则此验证字段必须存在且不为空。
 *
 * @see https://v.neww7.com/4/BuiltRule.html#required-if-accepted-anotherfield
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class RequiredIfAccepted implements RuleInterface
{
    protected array $args = [];

    public function __construct(string $another_field)
    {
        $this->args = func_get_args();
    }

    public function getArgs(): array
    {
        return $this->args;
    }
}
