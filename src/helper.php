<?php

use Itwmw\Validate\Attributes\AttributesValidator;
use Itwmw\Validate\Attributes\ClassMethodValidator;
use W7\Validate\Validate;

if (!function_exists('validate_attribute')) {
    /**
     * 验证类属性
     *
     * @template T
     *
     * @param class-string<T>|T $class    带有验证注解的类或完整类名
     * @param array|null        $input    验证数据，如果为null则从类中获取
     * @param array|null        $fields   待验证的字段，如果为null则验证全部字段
     * @param bool              $validate 是否需要对类进行验证，如果为true则进行验证，否则返回验证器
     *
     * @return T|Validate
     *
     * @throws ReflectionException
     * @throws \W7\Validate\Exception\ValidateException
     *
     * @noinspection PhpDocSignatureInspection
     * @noinspection PhpFullyQualifiedNameUsageInspection
     */
    function validate_attribute(string|object $class, array $input = null, array $fields = null, bool $validate = true)
    {
        $validator = new AttributesValidator($class);
        return $validator->validate($input, $fields, $validate);
    }
}

if (!function_exists('get_class_method_validator')) {
    /**
     * 获取方法中的验证器
     *
     * @param object|string $class  类或者类名
     * @param string        $method 方法名
     *
     * @return false|Validate
     */
    function get_class_method_validator(object|string $class, string $method): false|array
    {
        $validator = new ClassMethodValidator($class, $method);
        return $validator->getValidator();
    }
}
