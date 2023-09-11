<?php

namespace Itwmw\Validate\Attributes\Rules;

use JetBrains\PhpStorm\ExpectedValues;

/**
 * 待验证字段只能由英文字母和数字组成。
 *
 * **如果想验证所有的Unicode字母字符，可添加`all`选项。**
 *
 * @see https://v.neww7.com/4/BuiltRule.html#alpha-num
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
class AlphaNum implements RuleInterface
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
