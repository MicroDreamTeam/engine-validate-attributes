<?php

namespace Itwmw\Validate\Attributes\Test;

use Itwmw\Validate\Attributes\AttributesValidator;
use Itwmw\Validate\Attributes\Rules\ArrayRule;
use Itwmw\Validate\Attributes\Rules\Between;
use Itwmw\Validate\Attributes\Rules\ChsAlphaNum;
use Itwmw\Validate\Attributes\Rules\Email;
use Itwmw\Validate\Attributes\Message;
use Itwmw\Validate\Attributes\Rules\Regex;
use Itwmw\Validate\Attributes\Rules\Required;
use ReflectionException;
use W7\Validate\Exception\ValidateException;

class Data
{
    #[Required]
    #[ChsAlphaNum]
    #[Between(min:1, max: 10)]
    #[Message('名称')]
    public string $name;

    #[Required]
    #[Email]
    #[Message(messages: [
        Email::class    => '邮箱格式错误',
        Required::class => '邮箱不能为空'
    ])]
    public string $email;

    #[ArrayRule('@keyInt')]
    public array $data;

    #[Required]
    #[Regex('/^[0|1|2]$/')]
    public string $status;

    public string $not;

    public string $test = '1';
}

class InitData
{
    public function __construct(
        #[Required]
        #[ChsAlphaNum]
        #[Between(min:1, max: 10)]
        #[Message('名称')]
        public string $name,
        #[Required]
        #[Email]
        #[Message(messages: [
            Email::class    => '邮箱格式错误',
            Required::class => '邮箱不能为空'
        ])]
        public string $email,
        #[ArrayRule('@keyInt')]
        public array $data,
        #[Required]
        #[Regex('/^[0|1|2]$/')]
        public string $status,
        public string $test = '1',
    ) {
    }
}
class TestPropertiesValidate extends BaseTestCase
{
    /**
     * @test 测试使用数据结构和外部数据来验证
     *
     * @throws ReflectionException
     * @throws ValidateException
     */
    public function testCheckInputValidate()
    {
        $input = [
            'name'  => '虞灪',
            'email' => '123@qq.com',
            'data'  => [
                1, 2, 3, 4
            ],
            'everything' => 123,
            'status'     => 1
        ];

        $result = (new AttributesValidator(Data::class))->validate($input);

        $this->assertEquals($input['name'], $result->name);
        $this->assertEquals($input['status'], $result->status);
        $this->assertEquals($input['data'], $result->data);
        $this->assertFalse(property_exists($result, 'everything'));

        $this->expectException(ValidateException::class);
        $this->expectExceptionMessage('名称 不能为空。');
        (new AttributesValidator(Data::class))->validate();
    }

    /**
     * @test 测试使用对象来验证
     *
     * @throws ReflectionException
     * @throws ValidateException
     */
    public function testCheckObjectDataValidate()
    {
        $data   = new InitData('李浩', '259@qq.com', [1, 2, 3, 4], 1);
        $result = (new AttributesValidator($data))->validate();
        $this->assertEquals('李浩', $result->name);
        $this->assertEquals('259@qq.com', $result->email);
        $this->assertEquals([1, 2, 3, 4], $result->data);

        $data = new InitData('李浩', '', [1, 2, 3, 4], 1);
        $this->expectException(ValidateException::class);
        $this->expectExceptionMessage('邮箱不能为空');
        (new AttributesValidator($data))->validate();
    }
}
