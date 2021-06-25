<?php

namespace Itwmw\Validate\Attributes\Test;

use Itwmw\Validate\Attributes\ValidateAttributesFactory;
use Itwmw\Validate\Middleware\ValidateMiddlewareConfig;
use PHPUnit\Framework\TestCase;

class BaseTestCase extends TestCase
{
    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        ValidateMiddlewareConfig::instance()->setValidateFactory(new ValidateAttributesFactory());
    }
}
