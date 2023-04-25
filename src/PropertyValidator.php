<?php

namespace Itwmw\Validate\Attributes;

use W7\Validate\Validate;

class PropertyValidator
{
    /**
     * @param class-string $dataClass
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
            $validator = validate_attribute($this->dataClass, fields: $this->fields, validate: false);
            $dataClass = new $this->dataClass;
            $scene     = $validator->makeValidateScene();
            $scene->only(is_null($this->fields) ? true : $this->fields);
            if (!empty($before = $this->getBefore())) {
                foreach ($this->methodToEventFunc($dataClass, $before) as $closure) {
                    $scene->before($closure);
                }
            }

            $scene->after(function (array $data) use ($dataClass) {
                foreach ($data as $key => $value) {
                    $property = new \ReflectionProperty($dataClass, $key);
                    $property->setValue($dataClass, $value);
                }
                return true;
            });

            if (!empty($after = $this->getAfter())) {
                foreach ($this->methodToEventFunc($dataClass, $after) as $closure) {
                    $scene->after($closure);
                }
            }

            $validator->setScene(['v' => $scene])->scene('v');

            return $validator;
        }

        return false;
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
