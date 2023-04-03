<?php

use Itwmw\Validate\Attributes\AttributesValidator;
use Itwmw\Validate\Attributes\Validator;
use W7\Validate\Validate;

if (!function_exists('validate_attribute')) {
    /**
     * 验证类属性
     *
     * @template T
     *
     * @param class-string<T>|T $class         带有验证注解的类或完整类名
     * @param array|null        $input         验证数据，如果为null则从类中获取
     * @param array|null        $fields        待验证的字段，如果为null则验证全部字段
     * @param bool              $only_validate 只验证输入数据，不对$class进行读取默认值和赋值行为
     *
     * @return T
     *
     * @throws ReflectionException
     * @throws \W7\Validate\Exception\ValidateException
     *
     * @noinspection PhpDocSignatureInspection
     * @noinspection PhpFullyQualifiedNameUsageInspection
     */
    function validate_attribute(string|object $class, ?array $input = null, ?array $fields = null, bool $only_validate = false)
    {
        $validator = new AttributesValidator($class);
        return $validator->check($input, $fields, $only_validate);
    }
}

if (!function_exists('get_class_method_validator')) {
    /**
     * 获取方法中的验证器
     *
     * @param object|string $class  类或者类名
     * @param string        $method 方法名
     * @return false|Validate[]
     */
    function get_class_method_validator(object|string $class, string $method): false|array
    {
        try {
            $controllerRef = new \ReflectionClass($class);
            $methods       = $controllerRef->getMethod($method);
            $validators    = $methods->getAttributes(Validator::class);
            if (empty($validators)) {
                return false;
            }

            $allValidators = [];

            foreach ($validators as $validator) {
                /** @var Validator $validateAttribute */
                $validateAttribute = $validator->newInstance();

                /** @var Validate $validator */
                $validator = new $validateAttribute->validate;

                if (!empty($validateAttribute->scene)) {
                    $validator->scene($validateAttribute->scene);
                } elseif (!empty($validateAttribute->fields)) {
                    $sceneName = md5(rand(1000000, 9999999) . time());
                    $validator->setScene([$sceneName => $validateAttribute->fields])->scene($sceneName);
                }

                $allValidators[] = $validator;
            }

            return $allValidators;
        } catch (ReflectionException) {
            return false;
        }
    }
}
