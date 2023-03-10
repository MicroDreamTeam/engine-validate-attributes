<?php

namespace Itwmw\Validate\Attributes\Rules;

use Attribute;
use JetBrains\PhpStorm\ExpectedValues;

/**
 * 验证数组时，指定的字段不能有任何重复值。
 *
 * 你可以在验证规则的参数中添加 `ignore_case`，以使规则忽略大小写差异：
 *
 * 默认情况下，Distinct 使用松散的变量比较。要使用严格比较，您可以在验证规则定义中添加 strict 参数
 *
 * @see https://v.neww7.com/4/BuiltRule.html#distinct
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class Distinct implements RuleInterface
{
    protected array $args = [];

    public function __construct(
        #[ExpectedValues(values: ['ignore_case', 'strict'])]
        string $options = null
    ) {
        $this->args = func_get_args();
    }

    public function getArgs(): array
    {
        return $this->args;
    }
}
