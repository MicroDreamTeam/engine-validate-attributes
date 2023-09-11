<?php

/** @noinspection PhpUndefinedNamespaceInspection */
/** @noinspection PhpUndefinedClassInspection */
$finder = PhpCsFixer\Finder::create()
    ->files()
    ->name('*.php')
    ->exclude('vendor')
    ->in(__DIR__)
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

$fixers = [
    '@PSR12'                                     => true,
    '@PHP82Migration'                            => true,
    '@Symfony'                                   => true,
    'single_quote'                               => true, // 简单字符串应该使用单引号代替双引号；
    'no_unused_imports'                          => true, // 删除没用到的use
    'no_singleline_whitespace_before_semicolons' => true, // 禁止只有单行空格和分号的写法；
    'no_empty_statement'                         => true, // 多余的分号
    'no_extra_blank_lines'                       => true, // 多余空白行
    'no_blank_lines_after_phpdoc'                => true, // 注释和代码中间不能有空行
    'no_empty_phpdoc'                            => true, // 禁止空注释
    'phpdoc_indent'                              => true, // 注释和代码的缩进相同
    'no_blank_lines_after_class_opening'         => true, // 类开始标签后不应该有空白行；
    'include'                                    => true, // include 和文件路径之间需要有一个空格，文件路径不需要用括号括起来；
    'no_trailing_comma_in_list_call'             => true, // 删除 list 语句中多余的逗号；
    'no_leading_namespace_whitespace'            => true, // 命名空间前面不应该有空格；
    'standardize_not_equals'                     => true, // 使用 <> 代替 !=；
    'blank_line_after_opening_tag'               => true, // PHP开始标记后换行
    'indentation_type'                           => true,
    'concat_space'                               => [
        'spacing' => 'one',
    ],
    'space_after_semicolon'                      => [
        'remove_in_empty_for_expressions' => true,
    ],
    'header_comment'                             => [
        'comment_type' => 'PHPDoc',
        'header'       => '',
    ],
    'binary_operator_spaces'                     => [
        'operators' => [
            '=>' => 'align_single_space_minimal_by_scope',
            '='  => 'align_single_space_minimal',
        ]
    ], // 等号对齐、数字箭头符号对齐
    'whitespace_after_comma_in_array'            => true,
    'array_syntax'                               => ['syntax' => 'short'],
    'ternary_operator_spaces'                    => true,
    'yoda_style'                                 => true,
    'normalize_index_brace'                      => true,
    'short_scalar_cast'                          => true,
    'function_typehint_space'                    => true,
    'function_declaration'                       => true,
    'return_type_declaration'                    => true,
    'phpdoc_no_alias_tag'                        => false, // 禁止使用别名类型，这里禁用此规则以启用 phpStorm 的标签。此规则在 Symfony 中包含
    'single_trait_insert_per_statement'          => false, // 每个 trait 的 use 都必须作为一行语句来完成。此规则在 Symfony 中包含
    'blank_line_before_statement'                => false, // 任何已配置的语句之前必须有一个空换行符。此规则在 Symfony 中包含
    'trailing_comma_in_multiline'                => false, // 多行数组、参数列表、参数列表和匹配表达式必须以逗号结尾。此规则在 Symfony 中包含
    'new_with_braces'                            => false, // 所有用new关键字创建的实例必须后跟大括号。此规则在 Symfony 中包含
    'no_superfluous_phpdoc_tags'                 => false, // 删除@param、@return和@var不提供任何有用信息的标签。此规则在 Symfony 中包含
    'phpdoc_summary'                             => false, // PHPDOC因以句号，感叹号，问号结尾。此规则在 Symfony 中包含
    'phpdoc_to_comment'                          => false, // DocBlocks 只能用于结构元素。此规则在 Symfony 中包含
    'class_attributes_separation'                => [
        'elements' => [
            'const'        => 'one',
            'method'       => 'one',
            'property'     => 'one',
            'trait_import' => 'none',
            'case'         => 'none',
        ],
    ],
];
$config = new \PhpCsFixer\Config();

return $config
    ->setRules($fixers)
    ->setFinder($finder)
    ->setUsingCache(false);
