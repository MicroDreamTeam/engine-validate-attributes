<?php

namespace Itwmw\Validate\Attributes;

use Attribute;
use W7\Validate\Support\PreprocessorSupport;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Preprocessor
{
    /**
     * @var PreprocessorSupport[]
     */
    protected array $params = [];

    public function __construct(
        protected mixed $handler = null,
        PreprocessorSupport ...$params
    )
    {
        $this->params = $params ?: [];
    }

    public function getHandler()
    {
        return $this->handler;
    }

    public function getParams(): array
    {
        return $this->params;
    }
}
