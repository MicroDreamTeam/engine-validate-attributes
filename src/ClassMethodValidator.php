<?php

namespace Itwmw\Validate\Attributes;

use ReflectionException;
use W7\Validate\Validate;

/**
 * @internal
 */
class ClassMethodValidator
{
    public function __construct(protected object|string $class, protected string $method)
    {
    }

    public function getValidator(): false|array
    {
        try {
            $controllerRef = new \ReflectionClass($this->class);
            $methods       = $controllerRef->getMethod($this->method);

            // 获取定义的全部验证器
            $validators    = $methods->getAttributes(Validator::class);
            $allValidators = [];
            if (!empty($validators)) {
                foreach ($validators as $validator) {
                    /** @var Validator $validateAttribute */
                    $validateAttribute = $validator->newInstance();

                    if (!empty($validateAttribute->validate) && class_exists($validateAttribute->validate)) {
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
                }
            }

            return array_merge($allValidators, $this->getPropertyValidator());
        } catch (ReflectionException) {
            return false;
        }
    }

    /**
     * @return array<Validate>
     *
     * @throws ReflectionException
     * @throws \W7\Validate\Exception\ValidateException
     *
     * @noinspection PhpFullyQualifiedNameUsageInspection
     */
    public function getPropertyValidator(): array
    {
        $method             = new \ReflectionMethod($this->class, $this->method);
        $propertyAttributes = $method->getAttributes(PropertyValidator::class);
        if (empty($propertyAttributes)) {
            return [];
        }

        $validators = [];
        foreach ($propertyAttributes as $propertyAttribute) {
            $propertyValidator = $propertyAttribute->newInstance();
            /** @var PropertyValidator $propertyValidator */
            $validator = $propertyValidator->getValidator();
            if (false !== $validator) {
                $validators[] = $validator;
            }
        }

        return $validators;
    }
}
