<?php

namespace Itwmw\Validate\Attributes;

use JetBrains\PhpStorm\Pure;
use ReflectionException;
use W7\Validate\Support\Concerns\ValidateFactoryInterface;
use W7\Validate\Support\Storage\ValidateFactory;
use W7\Validate\Validate;

class ValidateAttributesFactory extends ValidateFactory implements ValidateFactoryInterface
{
    #[Pure]
    public static function make(): ValidateAttributesFactory
    {
        return new static();
    }

    public function getValidate(string $controller, string $scene = ''): bool | Validate
    {
        try {
            $controllerRef = new \ReflectionClass($controller);
            $methods       = $controllerRef->getMethod($scene);
            $validate      = $methods->getAttributes(Validator::class);
            if (empty($validate)) {
                return parent::getValidate($controller, $scene);
            }
            $validate = reset($validate);

            /** @var Validator $validateAttribute */
            $validateAttribute = $validate->newInstance();

            /** @var Validate $validator */
            $validator = new $validateAttribute->validate;

            if (!empty($validateAttribute->scene)) {
                $validator->scene($validateAttribute->scene);
            } elseif (!empty($validateAttribute->fields)) {
                $sceneName = md5(rand(1000000, 9999999) . time());
                $validator->setScene([$sceneName => $validateAttribute->fields])->scene($sceneName);
            }

            return $validator;
        } catch (ReflectionException) {
            return false;
        }
    }
}
