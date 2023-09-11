<?php

namespace Itwmw\Validate\Attributes\Rules;

/**
 * 根据 `dns_get_record` PHP 函数，验证中的字段必须具有有效的 A 或 AAAA 记录。 提供的 URL 的主机名使用 `parse_url` PHP 函数提取，然后传递给 `dns_get_record`。
 *
 * @see https://v.neww7.com/4/BuiltRule.html#active-url
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
class ActiveUrl implements RuleInterface
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
