<?php

namespace Itwmw\Validate\Attributes\Test;

use Itwmw\Validate\Attributes\Message;
use Itwmw\Validate\Attributes\Postprocessor;
use Itwmw\Validate\Attributes\Preprocessor;
use Itwmw\Validate\Attributes\PropertyValidator;
use Itwmw\Validate\Attributes\Rules\ArrayRule;
use Itwmw\Validate\Attributes\Rules\Between;
use Itwmw\Validate\Attributes\Rules\ChsAlphaNum;
use Itwmw\Validate\Attributes\Rules\Email;
use Itwmw\Validate\Attributes\Rules\Required;
use Itwmw\Validate\Attributes\Rules\StringRule;
use Itwmw\Validate\Attributes\ValidateAttributesFactory;
use Itwmw\Validate\Attributes\Validator;
use Itwmw\Validate\Middleware\ValidateMiddlewareConfig;
use W7\Validate\Support\Processor\ProcessorExecCond;
use W7\Validate\Support\Processor\ProcessorParams;
use W7\Validate\Validate;

class UserValidate extends Validate
{
    protected $rule = [
        'user' => 'required',
        'pass' => 'required',
        'code' => 'required',
    ];
}

class UserInfo
{
    #[Required]
    #[ChsAlphaNum]
    #[Between(min: 1, max: 10)]
    #[Message('昵称')]
    public string $nickname;

    #[Required]
    #[Email]
    #[Message(messages: [
        Email::class    => '邮箱格式错误',
        Required::class => '邮箱不能为空'
    ])]
    public string $email;

    #[Required]
    #[ArrayRule('@keyInt')]
    #[Preprocessor([0], ProcessorExecCond::WHEN_EMPTY)]
    #[Postprocessor('array_unique', ProcessorExecCond::WHEN_NOT_EMPTY, ProcessorParams::Value)]
    public array $group;

    #[StringRule]
    #[Message(name: '备注')]
    #[Preprocessor('trim', ProcessorExecCond::WHEN_NOT_EMPTY, ProcessorParams::Value)]
    #[Postprocessor('removeXss', ProcessorExecCond::WHEN_NOT_EMPTY, ProcessorParams::Value)]
    public ?string $remark = '';

    public function removeXss($value): string
    {
        // 处理xss demo，仅供展示
        $value = preg_replace('/<script.*?>.*?<\/script>/si', '', $value);
        return strip_tags($value);
    }
}
class UserController
{
    #[Validator(validate: UserValidate::class, scene: 'login')]
    public function login()
    {
    }

    #[Validator(validate: UserValidate::class, fields: ['user', 'pass'])]
    public function register()
    {
    }

    #[Validator(validate: UserValidate::class, fields: ['pass'])]
    #[PropertyValidator(dataClass: UserInfo::class)]
    public function saveUserInfo()
    {
    }

    #[PropertyValidator(dataClass: UserInfo::class, fields: ['email'])]
    public function saveEmail()
    {
    }
}
class TestGetValidate extends BaseTestCase
{
    public function testScene()
    {
        $validate = ValidateMiddlewareConfig::instance()->getValidateFactory()->getValidate(UserController::class, 'login');
        $this->assertCount(1, $validate);
        $validate = reset($validate);
        /** @var Validate $validate */
        $this->assertEquals('login', $validate->getCurrentSceneName());
        $this->assertEquals(UserValidate::class, $validate::class);

        $validate = (new ValidateAttributesFactory())->getValidate(UserController::class, 'login');
        $this->assertCount(1, $validate);
        $validate = reset($validate);
        /** @var Validate $validate */
        $this->assertEquals('login', $validate->getCurrentSceneName());
        $this->assertEquals(UserValidate::class, $validate::class);
    }

    public function testFields()
    {
        $validate = ValidateMiddlewareConfig::instance()->getValidateFactory()->getValidate(UserController::class, 'register');
        $this->assertCount(1, $validate);
        $validate = reset($validate);

        $validatorRule = (new UserValidate)->getInitialRules();
        $validatorRule = array_intersect_key($validatorRule, array_flip(['user', 'pass']));
        /** @var Validate $validate */
        $attValidatorRule = $validate->getInitialRules();

        $this->assertEquals($validatorRule, $attValidatorRule);
    }

    public function testValidatorAndDataClassValidator()
    {
        $validate = ValidateMiddlewareConfig::instance()->getValidateFactory()->getValidate(UserController::class, 'saveUserInfo');
        $this->assertCount(2, $validate);
        $allData = [];
        foreach ($validate as $item) {
            /** @var Validate $item */
            $data = $item->check([
                'pass'     => 'test',
                'nickname' => '昵称',
                'email'    => '123@qq.com'
            ]);

            $allData += $data;
        }

        $this->assertCount(5, $allData);
    }

    public function testDataClassValidatorForField()
    {
        $validate = ValidateMiddlewareConfig::instance()->getValidateFactory()->getValidate(UserController::class, 'saveEmail');
        $data     = $validate[0]->check([
            'email' => '123@qq.com'
        ]);

        $this->assertCount(1, $data);
        $this->assertSame('123@qq.com', $data['email']);
    }
}
