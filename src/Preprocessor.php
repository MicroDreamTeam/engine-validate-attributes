<?php

namespace Itwmw\Validate\Attributes;

use Attribute;
use W7\Validate\Support\ProcessorOptions;
use W7\Validate\Support\ProcessorParams;
use W7\Validate\Support\ProcessorSupport;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class Preprocessor
{
    /**
     * @var ProcessorOptions[]|ProcessorParams[]
     */
    protected array $params = [];

    /**
     * @param mixed|null $handler
     * @param ProcessorOptions|ProcessorParams ...$params
     *
     * @noinspection PhpDocSignatureInspection
     */
    public function __construct(
        protected mixed $handler = null,
        ProcessorSupport ...$params
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
