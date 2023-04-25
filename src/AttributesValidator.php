<?php

namespace Itwmw\Validate\Attributes;

use Itwmw\Validate\Attributes\Rules\RuleInterface;
use Itwmw\Validation\Support\Str;
use W7\Validate\Support\Processor\ProcessorOptions;
use ReflectionAttribute;
use ReflectionClass;

/**
 * @template T
 * @internal
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

        $extendRules          = [];
        $extendImplicitRules  = [];
        $extendDependentRules = [];
        $ruleMessages         = [];

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

            $customRules = $property->getAttributes(Rule::class, ReflectionAttribute::IS_INSTANCEOF);
            foreach ($customRules as $customRule) {
                $customRule = $customRule->newInstance();
                $subRules[] = $customRule->getRule();

                // 判断如果是当前类的方法实现的规则，则进行规则注入
                /** @var Rule $customRule */
                if (method_exists($class, $customRule->name)) {
                    $customRuleMethod = new \ReflectionMethod($class, $customRule->name);
                    $extension        = $customRuleMethod->getClosure($class);
                    if (Rule::TYPE_NORMAL === $customRule->type) {
                        $extendRules[$customRule->name] = $extension;
                    } elseif (Rule::TYPE_IMPLICIT === $customRule->type) {
                        $extendImplicitRules[$customRule->name] = $extension;
                    } elseif (Rule::TYPE_DEPENDENT === $customRule->type) {
                        $extendDependentRules[$customRule->name] = $extension;
                    } else {
                        throw new \RuntimeException('未知的自定义规则类型');
                    }
                    $customRuleMessage = $customRuleMethod->getAttributes(RuleMessage::class, ReflectionAttribute::IS_INSTANCEOF);
                    if (!empty($customRuleMessage)) {
                        $customRuleMessage                           = reset($customRuleMessage)->newInstance();
                        $ruleMessages[Str::snake($customRule->name)] = $customRuleMessage->getMessage();
                    }
                }
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
                    $messages[$propertyName][lcfirst(basename($ruleNameClass))] = $message;
                }
            }

            $this->getProcessor($property, Preprocessor::class, $class, $ref, $preprocessors);
            $this->getProcessor($property, Postprocessor::class, $class, $ref, $postprocessors);
        }

        $validator = new AttributeValidate();

        $messages         = array_filter($messages, fn ($message) => !empty($message));
        $customAttributes = array_filter($names, fn ($name) => !empty($name));

        $validator->setRules($rules);
        $validator->setCustomAttributes($customAttributes);
        $validator->setMessages($messages);
        $validator->setPreprocessor($preprocessors);
        $validator->setPostprocessor($postprocessors);
        $validator->setRuleMessages($ruleMessages);
        foreach ($extendRules as $key => $rule) {
            $validator->extend($key, $rule);
        }
        foreach ($extendImplicitRules as $key => $rule) {
            $validator->extendImplicit($key, $rule);
        }
        foreach ($extendDependentRules as $key => $rule) {
            $validator->extendDependent($key, $rule);
        }

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
