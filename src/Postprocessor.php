<?php

namespace Itwmw\Validate\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class Postprocessor extends Preprocessor
{

}
