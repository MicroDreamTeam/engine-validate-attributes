<?php

namespace Itwmw\Validate\Attributes;

use W7\Validate\Support\Processor\ProcessorExecCond;
use W7\Validate\Support\Processor\ProcessorOptions;
use W7\Validate\Support\Processor\ProcessorParams;
use W7\Validate\Support\Processor\ProcessorSupport;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
class Preprocessor
{
    /**
     * @var ProcessorOptions[]|ProcessorParams[]|ProcessorExecCond[]
     */
    protected array $params = [];

    /**
     * @param mixed|null                                         $handler
     * @param ProcessorOptions|ProcessorParams|ProcessorExecCond ...$params
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
