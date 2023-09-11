<?php

namespace Itwmw\Validate\Attributes\Rules;

/**
 * 被验证的字段必须是合法的中国居民身份证格式。
 *
 * @see https://v.neww7.com/4/BuiltRule.html#id-card
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
class IdCard implements RuleInterface
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
