<?php

namespace Itwmw\Validate\Attributes;

use W7\Validate\Validate;

/**
 * @internal
 */
class AttributeValidate extends Validate
{
    public function setPreprocessor(array $preprocessor): static
    {
        $this->preprocessor = $preprocessor;
        return $this;
    }

    public function setPostprocessor(array $postprocessor): static
    {
        $this->postprocessor = $postprocessor;
        return $this;
    }

    public function setRuleMessages(array $ruleMessages): static
    {
        $this->ruleMessage = $ruleMessages;
        return $this;
    }
}
