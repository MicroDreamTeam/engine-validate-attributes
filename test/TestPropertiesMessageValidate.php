<?php

namespace Itwmw\Validate\Attributes\Test;

use Itwmw\Validate\Attributes\Message;
use Itwmw\Validate\Attributes\Rules\Max;
use Itwmw\Validate\Attributes\Rules\Min;
use Itwmw\Validate\Attributes\Rules\Required;
use Itwmw\Validate\Attributes\Rules\StringRule;
use W7\Validate\Exception\ValidateException;

class TestMessage
{
    #[Required]
    #[StringRule]
    #[Min(5)]
    #[Max(10)]
    #[Message('名称', [
        Min::class => '长度不能小于:min',
        Max::class => '长度不能大于:max'
    ])]
    public string $name;
}

class TestPropertiesMessageValidate extends BaseTestCase
{
    public function testMessageMin()
    {
        $this->expectException(ValidateException::class);
        $this->expectExceptionMessage('长度不能小于5');
        validate_attribute(new TestMessage(), [
            'name' => '123'
        ]);
    }

    public function testMessageMax()
    {
        $this->expectException(ValidateException::class);
        $this->expectExceptionMessage('长度不能大于10');
        validate_attribute(new TestMessage(), [
            'name' => '1234567891011213'
        ]);
    }

    public function testMessageRequired()
    {
        $this->expectException(ValidateException::class);
        $this->expectExceptionMessage('名称 不能为空。');
        validate_attribute(new TestMessage(), []);
    }
}
