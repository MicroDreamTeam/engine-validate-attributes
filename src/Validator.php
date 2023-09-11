<?php

namespace Itwmw\Validate\Attributes;

#[\Attribute(\Attribute::IS_REPEATABLE | \Attribute::TARGET_METHOD)]
class Validator
{
    /**
     * @param class-string|null $validate
     * @param string            $scene
     * @param array             $fields
     */
    public function __construct(
        public ?string $validate = null,
        public string $scene = '',
        public array $fields = []
    ) {
    }
}
