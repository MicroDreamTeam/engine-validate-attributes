<?php

namespace Itwmw\Validate\Attributes;

use W7\Validate\Validate;
use Attribute;

/**
 * @template T
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class PropertyValidator
{
    /**
     * @var object<T>
     */
    protected object $class;

    /**
     *
     * @param class-string<T> $dataClass
     * @param array<string>|null $fields
     * @param array<string>|string $after
     * @param array<string>|string $before
     */
    public function __construct(
        public readonly string $dataClass = '',
        public readonly ?array $fields = null,
        public readonly array|string $after = [],
        public readonly array|string $before = [],
    ) {
    }

    public function getAfter(): array
    {
        return is_array($this->after) ? $this->after : [$this->after];
    }

    public function getBefore(): array
    {
        return is_array($this->before) ? $this->before : [$this->before];
    }

    /**
     * @return bool|Validate
     *
     * @throws \ReflectionException
     * @throws \W7\Validate\Exception\ValidateException
     *
     * @noinspection PhpFullyQualifiedNameUsageInspection
     */
    public function getValidator(): bool|Validate
    {
        if (!empty($this->dataClass) && class_exists($this->dataClass)) {
            /** @var Validate $validator */
            $validator   = validate_attribute($this->dataClass, fields: $this->fields, validate: false);
            $this->class = new $this->dataClass;
            $scene       = $validator->makeValidateScene();
            $scene->only(is_null($this->fields) ? true : $this->fields);
            if (!empty($before = $this->getBefore())) {
                foreach ($this->methodToEventFunc($this->class, $before) as $closure) {
                    $scene->before($closure);
                }
            }

            $scene->after(function (array $data) {
                foreach ($data as $key => $value) {
                    $property = new \ReflectionProperty($this->class, $key);
                    $property->setValue($this->class, $value);
                }
                return true;
            });

            if (!empty($after = $this->getAfter())) {
                foreach ($this->methodToEventFunc($this->class, $after) as $closure) {
                    $scene->after($closure);
                }
            }

            $validator->setScene(['v' => $scene])->scene('v');

            return $validator;
        }

        return false;
    }

    /**
     * @return T
     */
    public function getDataClass(): object
    {
        return $this->class;
    }

    /**
     * @param object $class
     * @param array $methods
     *
     * @return array<Closure>
     *
     * @throws \ReflectionException
     *
     * @noinspection PhpFullyQualifiedNameUsageInspection
     */
    protected function methodToEventFunc(object $class, array $methods): array
    {
        $func = [];
        foreach ($methods as $method) {
            $refMethod = new \ReflectionMethod($class, $method);
            $func[]    = $refMethod->getClosure($class);
        }
        return $func;
    }
}
