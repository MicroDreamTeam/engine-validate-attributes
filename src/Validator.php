<?php

namespace Itwmw\Validate\Attributes;

use Attribute;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_METHOD)]
class Validator
{
    public function __construct(
        public string $validate,
        public string $scene = '',
        public array $fields = []
    ) {
    }
}
