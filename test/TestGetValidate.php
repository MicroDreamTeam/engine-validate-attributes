<?php

namespace Itwmw\Validate\Attributes\Test;

use Itwmw\Validate\Attributes\ValidateAttributesFactory;
use Itwmw\Validate\Attributes\Validator;
use Itwmw\Validate\Middleware\ValidateMiddlewareConfig;
use W7\Validate\Validate;

class UserValidate extends Validate
{
    protected $rule = [
        'user' => 'required',
        'pass' => 'required',
        'code' => 'required',
    ];
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
}
class TestGetValidate extends BaseTestCase
{
    public function testScene()
    {
        $validate = ValidateMiddlewareConfig::instance()->getValidateFactory()->getValidate(UserController::class, 'login');
        $this->assertCount(1, $validate);
        /** @var Validate $validate */
        $validate = reset($validate);
        $this->assertEquals('login', $validate->getCurrentSceneName());
        $this->assertEquals(UserValidate::class, $validate::class);

        $validate = (new ValidateAttributesFactory())->getValidate(UserController::class, 'login');
        $this->assertCount(1, $validate);
        /** @var Validate $validate */
        $validate = reset($validate);
        $this->assertEquals('login', $validate->getCurrentSceneName());
        $this->assertEquals(UserValidate::class, $validate::class);
    }

    public function testFields()
    {
        $validate = ValidateMiddlewareConfig::instance()->getValidateFactory()->getValidate(UserController::class, 'register');
        $this->assertCount(1, $validate);
        /** @var Validate $validate */
        $validate = reset($validate);
        $this->assertEquals((new UserValidate)->getRules(['user', 'pass']), $validate->getRules());
    }
}
