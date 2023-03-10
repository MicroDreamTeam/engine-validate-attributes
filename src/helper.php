<?php

use Itwmw\Validate\Attributes\AttributesValidator;

if (!function_exists('attValidate')) {
    /**
     * @template T
     *
     * @param class-string<T>|T $class 带有验证注解的类或完整类名
     * @param array|null $input 验证数据，如果为null则从类中获取
     *
     * @return T
     *
     * @throws ReflectionException
     * @throws \W7\Validate\Exception\ValidateException
     *
     * @noinspection PhpDocSignatureInspection
     * @noinspection PhpFullyQualifiedNameUsageInspection
     */
    function attValidate(string|object $class, ?array $input = null)
    {
        $validator = new AttributesValidator($class);
        return $validator->check($input);
    }
}
