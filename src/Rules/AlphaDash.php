<?php

namespace Itwmw\Validate\Attributes\Rules;

use Attribute;
use JetBrains\PhpStorm\ExpectedValues;

/**
 * 待验证字段可能包含英文字母、数字、短破折号（-）和下划线（\_）。
 *
 * **如果想验证所有的Unicode字母字符，可添加`all`选项。**
 *
 * @see https://v.neww7.com/4/BuiltRule.html#alpha-dash
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class AlphaDash implements RuleInterface
{
    protected array $args = [];

    public function __construct(
        #[ExpectedValues(values: ['all'])]
        string $options = null
    ) {
        $this->args = func_get_args();
    }

    public function getArgs(): array
    {
        return $this->args;
    }
}
