<?php

namespace Itwmw\Validate\Attributes\Test;

use Itwmw\Validate\Attributes\Message;
use Itwmw\Validate\Attributes\Rule;
use Itwmw\Validate\Attributes\RuleMessage;
use Itwmw\Validate\Attributes\Rules\Email;
use Itwmw\Validate\Attributes\Rules\StringRule;
use Itwmw\Validation\Support\Arr;
use W7\Validate\Exception\ValidateException;

class CustomRuleValidate
{
    #[Rule('checkAge')]
    public string $age;

    #[Rule('checkNotEmpty', type: Rule::TYPE_IMPLICIT)]
    public string $name;

    #[Email]
    #[Rule('contains', 'provider', type: Rule::TYPE_DEPENDENT)]
    public string $email;

    #[StringRule]
    public string $provider;

    protected function checkAge($attribute, $value): bool
    {
        return $value > 0 && $value < 100;
    }

    private function checkNotEmpty($attribute, $value): bool
    {
        return !empty($value);
    }

    public function contains($attribute, $value, $parameters, $validator): bool
    {
        return str_contains($value, Arr::get($validator->getData(), $parameters[0]));
    }
}

class CustomRuleMessageValidate
{
    #[Rule('checkAge')]
    public string $age;

    #[Rule('checkNotEmpty', type: Rule::TYPE_IMPLICIT)]
    #[Message(messages: [
        'checkNotEmpty' => '名字不能为空'
    ])]
    public string $name;

    #[RuleMessage('不符合要求的年龄')]
    protected function checkAge($attribute, $value): bool
    {
        return $value > 0 && $value < 100;
    }

    private function checkNotEmpty($attribute, $value): bool
    {
        return !empty($value);
    }
}
class TestPropertiesCustomRuleValidate extends BaseTestCase
{
    public function testCustomNormalRule()
    {
        $this->expectException(ValidateException::class);
        $this->expectExceptionMessage('validation.check_age');
        validate_attribute(CustomRuleValidate::class, [
            'age' => '123'
        ], ['age']);
    }

    public function testCustomImplicitRule()
    {
        $this->expectException(ValidateException::class);
        $this->expectExceptionMessage('validation.check_not_empty');
        validate_attribute(CustomRuleValidate::class, [
            'name' => ''
        ], ['name']);
    }

    public function testCustomDependentRule()
    {
        $data = validate_attribute(CustomRuleValidate::class, [
            'email'    => '132@qq.com',
            'provider' => 'qq.com'
        ], ['email', 'provider']);
        $this->assertSame('132@qq.com', $data->email);

        $this->expectException(ValidateException::class);
        $this->expectExceptionMessage('validation.contains');

        validate_attribute(CustomRuleValidate::class, [
            'email'    => '132@163.com',
            'provider' => 'qq.com'
        ], ['email', 'provider']);
    }

    public function testCustomNormalRuleMessage()
    {
        $this->expectException(ValidateException::class);
        $this->expectExceptionMessage('不符合要求的年龄');
        validate_attribute(CustomRuleMessageValidate::class, [
            'age' => '123'
        ], ['age']);
    }

    public function testCustomImplicitRuleMessage()
    {
        $this->expectException(ValidateException::class);
        $this->expectExceptionMessage('名字不能为空');
        validate_attribute(CustomRuleMessageValidate::class, [
            'name' => ''
        ], ['name']);
    }
}
