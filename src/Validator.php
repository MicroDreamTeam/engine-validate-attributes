<?php

namespace Itwmw\Validate\Attributes;

use Attribute;

#[Attribute]
class Validator
{
    public function __construct(
        public string $validate,
        public string $scene = '',
        public array $fields = []
    ) {
    }
}
