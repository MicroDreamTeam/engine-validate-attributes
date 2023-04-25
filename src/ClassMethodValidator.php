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

                    if (!empty($validateAttribute->dataClass)) {
                        if ($validateAttribute->dataClass instanceof PropertyValidator) {
                            if (($validator = $validateAttribute->dataClass->getValidator()) !== false) {
                                $allValidators[] = $validator;
                            }
                            continue;
                        }
                        if (class_exists($validateAttribute->dataClass)) {
                            $attributesValidator = new AttributesValidator($validateAttribute->dataClass);
                            $validator           = $attributesValidator->validate(fields:$validateAttribute->fields ?: null, validate: false);
                            $allValidators[]     = $validator;
                        }
                    }
                }
            }

            return $allValidators;
        } catch (ReflectionException) {
            return false;
        }
    }
}
