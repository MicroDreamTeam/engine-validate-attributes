<?php

namespace Itwmw\Validate\Attributes\Rules;

/**
 * 验证的文件必须是图片（jpg，jpeg，png，bmp，gif，svg，或 webp）。
 *
 * @see https://v.neww7.com/4/BuiltRule.html#image
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
class Image implements RuleInterface
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
