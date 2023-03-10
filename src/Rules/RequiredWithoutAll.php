<?php

namespace Itwmw\Validate\Attributes\Rules;

use Attribute;

/**
 * 只有在其他指定字段全部不出现时，验证的字段才必须存在且不为空。
 *
 * @see https://v.neww7.com/4/BuiltRule.html#required-without-all-foo-bar
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class RequiredWithoutAll implements RuleInterface
{
    protected array $args = [];

    public function __construct(string ...$fields)
    {
        $this->args = func_get_args();
    }

    public function getArgs(): array
    {
        return $this->args;
    }
}
