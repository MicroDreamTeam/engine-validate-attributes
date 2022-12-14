<?php

namespace Itwmw\Validate\Attributes;

use Itwmw\Validate\Middleware\ValidateFactory;
use Itwmw\Validate\Middleware\ValidateFactoryInterface;
use JetBrains\PhpStorm\Pure;
use ReflectionException;
use W7\Validate\Validate;

class ValidateAttributesFactory extends ValidateFactory implements ValidateFactoryInterface
{
    #[Pure]
    public static function make(): ValidateAttributesFactory
    {
        return new static();
    }

    public function getValidate(string $controller, string $scene = '')
    {
        try {
            $controllerRef = new \ReflectionClass($controller);
            $methods       = $controllerRef->getMethod($scene);
            $validators    = $methods->getAttributes(Validator::class);
            if (empty($validators)) {
                return parent::getValidate($controller, $scene);
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
