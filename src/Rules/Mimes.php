<?php

namespace Itwmw\Validate\Attributes\Rules;

use Attribute;

/**
 * 验证的文件必须具备与列出的其中一个扩展相匹配的 MIME 类型：
 *
 * **为了确定上传文件的 MIME，框架将会读取文件，然后自动推测文件 MIME 类型，这可能与客户端提供的 MIME 类型不一致。**
 *
 * @see https://v.neww7.com/4/BuiltRule.html#mimes-foo-bar
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class Mimes implements RuleInterface
{
    protected array $args = [];

    public function __construct(string ...$types)
    {
        $this->args = func_get_args();
    }

    public function getArgs(): array
    {
        return $this->args;
    }
}
