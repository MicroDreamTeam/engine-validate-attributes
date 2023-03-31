<?php

namespace Itwmw\Validate\Attributes;

use Itwmw\Validate\Attributes\Rules\RuleInterface;
use Itwmw\Validation\Support\Str;
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
     * @param bool       $only_validate 只验证输入数据，不对类进行读取默认值和赋值行为
     *
     * @return T
     *
     * @throws \ReflectionException
     * @throws \W7\Validate\Exception\ValidateException
     *
     * @noinspection PhpFullyQualifiedNameUsageInspection
     */
    public function check(?array $input = null, bool $only_validate = false)
    {
        $class = $this->class;
        if (!is_object($class)) {
            $class = new $class;
        }

        $ref           = new ReflectionClass($class);
        $rules         = [];
        $properties    = [];
        $names         = [];
        $messages      = [];
        $preprocessors = [];
        if (is_null($input)) {
            $input = [];
        }

        foreach ($ref->getProperties() as $property) {
            $propertyName = $property->getName();
            $propertyType = $property->getType();

            // 从类中获取默认值数据
            if (!$only_validate && !isset($input[$propertyName]) && $property->isInitialized($class)) {
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

            $validatePreprocessor = $property->getAttributes(Preprocessor::class, ReflectionAttribute::IS_INSTANCEOF);
            if (!empty($validatePreprocessor)) {
                /** @var Preprocessor $validatePreprocessor */
                $validatePreprocessor = $validatePreprocessor[0]->newInstance();
                $handler              = $validatePreprocessor->getHandler();
                if (is_string($handler) && !is_callable($handler)) {
                    if (method_exists($class, $handler)) {
                        $handler = fn (...$params) => $ref->getMethod($handler)->invokeArgs($class, $params);
                    }
                }
                $params = $validatePreprocessor->getParams();
                if (!empty($params)) {
                    $preprocessor = [$handler, ...$params];
                } else {
                    $preprocessor = $handler;
                }

                $preprocessors[$propertyName] = $preprocessor;
            }
        }

        $validator = new class extends Validate {
            public function setPreprocessor(array $preprocessor): static
            {
                $this->preprocessor = $preprocessor;
                return $this;
            }
        };

        $validator->setRules($rules);
        $validator->setCustomAttributes(array_filter($names, fn ($name) => !empty($name)));
        $validator->setMessages(array_filter($messages, fn ($message) => !empty($message)));
        $validator->setPreprocessor($preprocessors);

        $validateResultData = $validator->check($input);
        if (!$only_validate) {
            foreach ($validateResultData as $key => $value) {
                $properties[$key]->setValue($class, $value);
            }
        }
        return $class;
    }

    protected function parseRule(RuleInterface $rule): string
    {
        $ruleName = basename(get_class($rule));
        if (str_ends_with($ruleName, 'Rule')) {
            $ruleName = substr($ruleName, 0, -4);
        }

        $ruleName = Str::snake($ruleName);

        $ruleArgs = implode(',', $rule->getArgs());
        if (empty($ruleArgs)) {
            $ruleString = $ruleName;
        } else {
            $ruleString = "{$ruleName}:{$ruleArgs}";
        }

        return $ruleString;
    }
}
