<?php

namespace Itwmw\Validate\Attributes\Rules;

use Attribute;
use JetBrains\PhpStorm\ExpectedValues;

/**
 * 待验证字段必须是有效的 PHP `数组`。
 *
 * @see https://v.neww7.com/4/BuiltRule.html#array
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class ArrayRule implements RuleInterface
{
    protected array $args = [];

    public function __construct(
        #[ExpectedValues(values: ['@keyInt'])]
        string $options = null,
        ...$args
    ) {
        $this->args = func_get_args();
    }

    public function getArgs(): array
    {
        return $this->args;
    }
}
