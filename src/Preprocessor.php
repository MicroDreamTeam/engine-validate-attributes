<?php

namespace Itwmw\Validate\Attributes;

use Attribute;
use W7\Validate\Support\PreprocessorOptions;
use W7\Validate\Support\PreprocessorParams;
use W7\Validate\Support\PreprocessorSupport;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Preprocessor
{
    /**
     * @var PreprocessorOptions[]|PreprocessorParams[]
     */
    protected array $params = [];

    /**
     * @param mixed|null $handler
     * @param PreprocessorOptions|PreprocessorParams ...$params
     *
     * @noinspection PhpDocSignatureInspection
     */
    public function __construct(
        protected mixed $handler = null,
        PreprocessorSupport ...$params
    ) {
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
