<?php

namespace Itwmw\Validate\Attributes;

use ReflectionException;
use W7\Validate\Support\Concerns\ValidateFactoryInterface;
use W7\Validate\Support\Storage\ValidateFactory;
use W7\Validate\Validate;

class ValidateAttributesFactory extends ValidateFactory implements ValidateFactoryInterface
{
    protected static ValidateAttributesFactory $instance;

    public static function instance(): ValidateAttributesFactory
    {
        if (empty(self::$instance)) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    public function getValidate(string $controller, string $scene = ''): bool | Validate
    {
        try {
            $controller = new \ReflectionClass($controller);
            $methods    = $controller->getMethod($scene);
            $validate   = $methods->getAttributes(Validator::class);
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
