<?php

namespace Itwmw\Validate\Attributes\Test;

use Itwmw\Validate\Attributes\Validator;
use Itwmw\Validate\Middleware\ValidateMiddlewareConfig;
use W7\Validate\Validate;

class TestController
{
    #[Validator(Test1Validator::class, fields: ['a', 'b'])]
    #[Validator(Test2Validator::class, fields: ['b', 'c', 'd'])]
    #[Validator(Test3Validator::class, scene: 'one')]
    public function one()
    {
    }
}

class Test1Validator extends Validate
{
    protected $rule = [
        'a' => 'required',
        'b' => 'required'
    ];
}

class Test2Validator extends Validate
{
    protected $rule = [
        'b' => 'required',
        'c' => 'required',
        'd' => 'required',
    ];
}

class Test3Validator extends Validate
{
    protected $rule = [
        'e' => 'required',
        'f' => 'required',
        'g' => 'required',
    ];

    protected $scene = [
        'one' => ['e', 'g']
    ];
}
class TestMultipleValidate extends BaseTestCase
{
    public function testGetMultipleValidator()
    {
        /** @var Validate[] $validates */
        $validates = ValidateMiddlewareConfig::instance()->getValidateFactory()->getValidate(TestController::class, 'one');
        $this->assertCount(3, $validates);

        $rules = [];
        foreach ($validates as $validate) {
            $rules = array_merge([], $rules, array_keys($validate->getInitialRules()));
        }
        $checkDataKey = ['a', 'b', 'c', 'd', 'e', 'g'];

        $this->assertEmpty(array_diff($checkDataKey, $rules));

        $data = array_combine($checkDataKey, array_fill(0, count($checkDataKey), 1));
        foreach ($validates as $validate) {
            $checkedData = $validate->check($data);
            $this->assertNotEmpty($checkedData);
        }
    }
}
