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

    protected Validate $validator;
    /**
     *
     * @param class-string<T> $dataClass
     * @param array<string>|null $fields
     * @param array<EventFunc>|EventFunc $after
     * @param array<EventFunc>|EventFunc $before
     */
    public function __construct(
        public readonly string $dataClass,
        public readonly ?array $fields = null,
        public readonly array|EventFunc $after = [],
        public readonly array|EventFunc $before = [],
    ) {
        if (!class_exists($this->dataClass)) {
            throw new \InvalidArgumentException('没有找到指定的数据类');
        }
        $this->class = new $this->dataClass;
    }

    /**
     * @return array<EventFunc>
     */
    public function getAfter(): array
    {
        return is_array($this->after) ? $this->after : [$this->after];
    }

    /**
     * @return array<EventFunc>
     */
    public function getBefore(): array
    {
        return is_array($this->before) ? $this->before : [$this->before];
    }

    /**
     * @return Validate
     *
     * @throws \ReflectionException
     * @throws \W7\Validate\Exception\ValidateException
     *
     * @noinspection PhpFullyQualifiedNameUsageInspection
     */
    public function getValidator(): Validate
    {
        if (isset($this->validator)) {
            return $this->validator;
        }

        /** @var Validate $validator */
        $validator = validate_attribute($this->dataClass, fields: $this->fields, validate: false);
        $scene     = $validator->makeValidateScene();
        $scene->only(is_null($this->fields) ? true : $this->fields);
        if (!empty($before = $this->getBefore())) {
            foreach ($before as $item) {
                $refMethod = new \ReflectionMethod($this->class, $item->method);
                $closure   = $refMethod->getClosure($this->class);
                $scene->before($closure, ...$item->getArgs());
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
            foreach ($after as $item) {
                $refMethod = new \ReflectionMethod($this->class, $item->method);
                $closure   = $refMethod->getClosure($this->class);
                $scene->after($closure, ...$item->getArgs());
            }
        }

        $validator->setScene(['v' => $scene])->scene('v');

        $this->validator = $validator;
        return $validator;
    }

    /**
     * @param array $data
     *
     * @return object<T>
     *
     * @throws \ReflectionException
     * @throws \W7\Validate\Exception\ValidateException
     *
     * @noinspection PhpFullyQualifiedNameUsageInspection
     */
    public function check(array $data): object
    {
        $this->getValidator()->check($data);
        return $this->getDataClass();
    }

    /**
     * @return object<T>
     */
    public function getDataClass(): object
    {
        return $this->class;
    }
}
