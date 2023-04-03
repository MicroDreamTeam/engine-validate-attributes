<?php

namespace Itwmw\Validate\Attributes;

use Itwmw\Validate\Middleware\ValidateFactory;
use Itwmw\Validate\Middleware\ValidateFactoryInterface;
use JetBrains\PhpStorm\Pure;

class ValidateAttributesFactory extends ValidateFactory implements ValidateFactoryInterface
{
    #[Pure]
    public static function make(): ValidateAttributesFactory
    {
        return new static();
    }

    public function getValidate(string $controller, string $scene = '')
    {
        $validators = get_class_method_validator($controller, $scene);
        if (empty($validators)) {
            return parent::getValidate($controller, $scene);
        }

        return $validators;
    }
}
