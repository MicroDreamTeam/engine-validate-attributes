<?php

namespace Itwmw\Validate\Attributes\Rules;

use Attribute;

/**
 * 验证字段必须匹配给定的 *format*（日期格式）。当验证某个字段的时候，你应该只使用 `date` 或者 `date_format`，而不是同时使用。
 *
 * 此验证规则支持 PHP 所有的 {@see https://www.php.net/manual/en/class.datetime.php DateTime} 类。
 *
 * @see https://v.neww7.com/4/BuiltRule.html#date-format-format
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class DateFormat implements RuleInterface
{
    protected array $args = [];

    public function __construct(string $format)
    {
        $this->args = func_get_args();
    }

    public function getArgs(): array
    {
        return $this->args;
    }
}
