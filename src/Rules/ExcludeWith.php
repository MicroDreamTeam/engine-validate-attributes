<?php

namespace Itwmw\Validate\Attributes\Rules;

use Attribute;

/**
 * 如果表单中存在 *anotherfield*，那么`check`方法中将会排除掉当前验证的字段。
 *
 * @see https://v.neww7.com/4/BuiltRule.html#exclude-with-anotherfield
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class ExcludeWith implements RuleInterface
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
