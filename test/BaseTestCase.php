<?php

namespace Itwmw\Validate\Attributes\Test;

use Itwmw\Validate\Attributes\ValidateAttributesFactory;
use PHPUnit\Framework\TestCase;
use W7\Validate\Support\Storage\ValidateMiddlewareConfig;

class BaseTestCase extends TestCase
{
    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        ValidateMiddlewareConfig::instance()->setValidateFactory(new ValidateAttributesFactory());
    }
}
