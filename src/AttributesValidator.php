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
     * @param array|null $input 验证数据，如果为null则从类中获取
     *
     * @return T
     *
     * @throws \ReflectionException
     * @throws \W7\Validate\Exception\ValidateException
     *
     * @noinspection PhpFullyQualifiedNameUsageInspection
     */
    public function check(?array $input = null)
    {
        $class = $this->class;
        if (!is_object($class)) {
            $class = new $class;
        }

        $ref        = new ReflectionClass($class);
        $validator  = Validate::make();
        $rules      = [];
        $properties = [];
        $names      = [];
        $messages   = [];
        $getValue   = is_null($input);
        if ($getValue) {
            $input = [];
        }

        foreach ($ref->getProperties() as $property) {
            $property->setAccessible(true);
            $propertyName = $property->getName();
            if ($getValue && $property->isInitialized($class)) {
                $input[$propertyName] = $property->getValue($class);
            }

            $properties[$propertyName] = $property;
            $validateRules             = $property->getAttributes(RuleInterface::class, ReflectionAttribute::IS_INSTANCEOF);
            $subRules                  = [];
            foreach ($validateRules as $rule) {
                $subRules[] = $this->parseRule($rule->newInstance());
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
        }

        $validator->setRules($rules);
        $validator->setCustomAttributes(array_filter($names, fn ($name) => !empty($name)));
        $validator->setMessages(array_filter($messages, fn ($message) => !empty($message)));

        $validateResultData = $validator->check($input);
        if (!$getValue) {
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
