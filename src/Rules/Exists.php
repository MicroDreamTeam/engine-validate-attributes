<?php

namespace Itwmw\Validate\Attributes\Rules;

use Attribute;

/**
 * 验证的字段必须存在于给定的数据库表中。
 *
 * @see https://v.neww7.com/4/BuiltRule.html#exists-table-column
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class Exists implements RuleInterface
{
    protected array $args = [];

    /**
     * @param string $table             查询的表名，如果你需要指定查询的数据库。你可以通过使用「点」语法将数据库的名称添加到数据表前面来实现这个目的：`connection.staff`
     * @param string|null $column       查询的列名，如果未指定 column 选项，则将使用验证的字段名称
     * @param string|null $whereColumn  额外查询条件的列名，必须和`whereValue`成对出现
     * @param mixed|null $whereValue    额外查询条件的值
     * @param string|mixed ...$criteria 其他查询条件，必须成对出现
     */
    public function __construct(string $table, string $column = null, string $whereColumn = null, mixed $whereValue = null, ...$criteria)
    {
        $this->args = func_get_args();
    }

    public function getArgs(): array
    {
        return $this->args;
    }
}
