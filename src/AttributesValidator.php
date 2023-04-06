<?php

namespace Itwmw\Validate\Attributes;

use Itwmw\Validate\Attributes\Rules\RuleInterface;
use Itwmw\Validation\Support\Str;
use W7\Validate\Support\Processor\ProcessorOptions;
use W7\Validate\Validate;
use ReflectionAttribute;
use ReflectionClass;

/**
 * @template T
 */
class AttributesValidator
{
    /**
     * @param class-string<T>|T $class 带有验证注解的类或完整类名
     *
     * @noinspection PhpDocSignatureInspection
     */
    public function __construct(protected string|object $class)
    {
    }

    /**
     * @param array|null $input         验证数据，如果为null则从类中获取
     * @param array|null $fields        待验证的字段，如果为null则验证全部字段
     * @param bool       $validate      是否需要对类进行验证，如果为true则进行验证，否则返回验证器
     *
     * @return T
     *
     * @throws \ReflectionException
     * @throws \W7\Validate\Exception\ValidateException
     *
     * @noinspection PhpFullyQualifiedNameUsageInspection
     */
    public function validate(?array $input = null, ?array $fields = null, bool $validate = true)
    {
        $class = $this->class;
        if (!is_object($class)) {
            $class = new $class;
        }

        $ref            = new ReflectionClass($class);
        $rules          = [];
        $properties     = [];
        $names          = [];
        $messages       = [];
        $preprocessors  = [];
        $postprocessors = [];
        if (is_null($input)) {
            $input = [];
        }

        foreach ($ref->getProperties() as $property) {
            $propertyName = $property->getName();
            if (!is_null($fields) && !in_array($propertyName, $fields)) {
                continue;
            }
            $propertyType = $property->getType();

            // 从类中获取默认值数据
            if ($validate && !isset($input[$propertyName]) && $property->isInitialized($class)) {
                $input[$propertyName] = $property->getValue($class);
            }

            $properties[$propertyName] = $property;
            $validateRules             = $property->getAttributes(RuleInterface::class, ReflectionAttribute::IS_INSTANCEOF);
            $subRules                  = [];

            foreach ($validateRules as $rule) {
                $subRules[] = $this->parseRule($rule->newInstance());
            }
            if (!in_array('nullable', $subRules) && $propertyType->allowsNull()) {
                $subRules[] = 'nullable';
            }

            $rules[$propertyName] = $subRules;

            $validateMessage = $property->getAttributes(Message::class, ReflectionAttribute::IS_INSTANCEOF);
            if (!empty($validateMessage)) {
                $messages[$propertyName] = [];
                /** @var Message $validateMessage */
                $validateMessage      = $validateMessage[0]->newInstance();
                $names[$propertyName] = $validateMessage->getName();
                foreach ($validateMessage->getMessages() as $ruleNameClass => $message) {
                    $messages[$propertyName][Str::snake(basename($ruleNameClass))] = $message;
                }
            }

            $this->getProcessor($property, Preprocessor::class, $class, $ref, $preprocessors);
            $this->getProcessor($property, Postprocessor::class, $class, $ref, $postprocessors);
        }

        $validator = new class extends Validate {
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
        };

        $messages         = array_filter($messages, fn ($message) => !empty($message));
        $customAttributes = array_filter($names, fn ($name) => !empty($name));

        $validator->setRules($rules);
        $validator->setCustomAttributes($customAttributes);
        $validator->setMessages($messages);
        $validator->setPreprocessor($preprocessors);
        $validator->setPostprocessor($postprocessors);

        if (!$validate) {
            return $validator;
        }

        $validateResultData = $validator->check($input);
        foreach ($validateResultData as $key => $value) {
            $properties[$key]->setValue($class, $value);
        }
        return $class;
    }

    protected function getProcessor(\ReflectionProperty $property, string $name, object $class, ReflectionClass $ref, array &$preprocessors): void
    {
        $validateProcessors = $property->getAttributes($name);
        if (!empty($validateProcessors)) {
            $name = $property->getName();
            foreach ($validateProcessors as $validateProcessor) {
                $validateProcessor = $validateProcessor->newInstance();
                /** @var Preprocessor $validateProcessor */
                $handler = $validateProcessor->getHandler();
                if (is_string($handler) && !is_callable($handler)) {
                    if (method_exists($class, $handler)) {
                        $handler = fn (...$params) => $ref->getMethod($handler)->invokeArgs($class, $params);
                    }
                }
                $params = $validateProcessor->getParams();
                if (!empty($params)) {
                    $processor = [$handler, ...$params];
                } else {
                    $processor = $handler;
                }
                $preprocessors[$name][] = $processor;
            }
            if (1 === count($preprocessors[$name])) {
                $preprocessors[$name] = $preprocessors[$name][0];
            } else {
                $preprocessors[$name][] = ProcessorOptions::MULTIPLE;
            }
        }
    }

    protected function parseRule(RuleInterface $rule): string
    {
        $ruleName = basename(get_class($rule));
        if (str_ends_with($ruleName, 'Rule')) {
            $ruleName = substr($ruleName, 0, -4);
        }

        $ruleName = lcfirst($ruleName);

        $ruleArgs = implode(',', $rule->getArgs());
        if (0 === strlen($ruleArgs)) {
            $ruleString = $ruleName;
        } else {
            $ruleString = "{$ruleName}:{$ruleArgs}";
        }

        return $ruleString;
    }
}
