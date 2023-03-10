<?php

namespace Itwmw\Validate\Attributes\Rules;

use Attribute;

/**
 * 验证的字段不能以给定的任意值开始。
 *
 * @see https://v.neww7.com/4/BuiltRule.html#doesnt-start-with-foo-bar
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class DoesntStartWith implements RuleInterface
{
    protected array $args = [];

    public function __construct(string ...$args)
    {
        $this->args = func_get_args();
    }

    public function getArgs(): array
    {
        return $this->args;
    }
}
