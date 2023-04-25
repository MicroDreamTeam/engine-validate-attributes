<?php

namespace Itwmw\Validate\Attributes;

use Attribute;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_METHOD)]
class Validator
{

    /**
     * @param class-string|null $validate
     * @param string $scene
     * @param array $fields
     * @param string|null|PropertyValidator $dataClass
     */
    public function __construct(
        public ?string $validate = null,
        public string $scene = '',
        public array $fields = [],
        public null|string|PropertyValidator $dataClass = null
    ) {
    }
}
