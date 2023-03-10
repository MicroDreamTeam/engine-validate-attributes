<?php

namespace Itwmw\Validate\Attributes\Rules;

use Attribute;

/**
 * 验证的文件必须是图片并且图片比例必须符合规则:
 *
 * ```php
 * 'avatar' => 'dimensions:min_width=100,min_height=200'
 * ```
 *
 * 可用的规则为: *min\_width*，*max\_width*，*min\_height*，*max\_height*，*width*，*height*，*ratio*。
 *
 * *ratio* 约束应该表示为宽度除以高度。 这可以通过像 `3/2` 这样的语句或像 `1.5` 这样的 `float` 来指定：
 *
 * ```php
 * 'avatar' => 'dimensions:ratio=3/2'
 * ```
 *
 * @see https://v.neww7.com/4/BuiltRule.html#dimensions
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class Dimensions implements RuleInterface
{
    protected array $args = [];

    public function __construct(
        int|float|string $min_width = null,
        int|float|string $max_width = null,
        int|float|string $min_height = null,
        int|float|string $max_height = null,
        int|float|string $width = null,
        int|float|string $height = null,
        int|float|string $ratio = null
    ) {
        $args       = array_filter(compact('min_width', 'max_width', 'min_height', 'max_height', 'width', 'height', 'ratio'), fn ($item) => !is_null($item));
        $this->args = array_map(fn ($key, $value) => "{$key}={$value}", array_keys($args), array_values($args));
    }

    public function getArgs(): array
    {
        return $this->args;
    }
}
