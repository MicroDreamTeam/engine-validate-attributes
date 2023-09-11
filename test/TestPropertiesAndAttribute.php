<?php

namespace Itwmw\Validate\Attributes\Test;

use Itwmw\Validate\Attributes\EventFunc;
use Itwmw\Validate\Attributes\PropertyValidator;
use Itwmw\Validate\Attributes\Rules\Numeric;
use Itwmw\Validate\Attributes\Rules\Required;
use Itwmw\Validate\Attributes\Rules\StringRule;
use Itwmw\Validate\Attributes\Validator;
use W7\Validate\Exception\ValidateException;
use W7\Validate\Validate;

class PropertiesAndAttributeValidator extends Validate
{
    protected $rule = [
        'desc' => 'required',
        'id'   => 'required|numeric'
    ];

    protected $scene = [
       'only_desc' => ['desc'],
       'only_id'   => ['id'],
    ];
}
class PropertiesAndAttributeDataClass
{
    #[StringRule]
    #[Required]
    private string $name;

    #[Required]
    #[Numeric]
    public int $age = 1;

    public function checkAge(array $data, int $min_age = 18): bool|string
    {
        return $this->age >= $min_age ? true : '未成年人不能注册';
    }
}

class PropertiesAndAttributeClass
{
    #[PropertyValidator(PropertiesAndAttributeDataClass::class)]
    public function test()
    {
    }

    #[PropertyValidator(PropertiesAndAttributeDataClass::class, ['name'])]
    public function test2()
    {
    }

    #[Validator(PropertiesAndAttributeValidator::class)]
    #[PropertyValidator(PropertiesAndAttributeDataClass::class)]
    public function test3()
    {
    }

    #[Validator(PropertiesAndAttributeValidator::class, fields: ['desc'])]
    #[PropertyValidator(PropertiesAndAttributeDataClass::class, fields: ['age'])]
    public function test4()
    {
    }

    #[Validator(PropertiesAndAttributeValidator::class, scene: 'only_desc')]
    #[PropertyValidator(PropertiesAndAttributeDataClass::class, fields: ['age'])]
    public function test5()
    {
    }

    #[PropertyValidator(PropertiesAndAttributeDataClass::class, after: new EventFunc('checkAge'))]
    public function test6()
    {
    }

    #[PropertyValidator(PropertiesAndAttributeDataClass::class, after: new EventFunc('checkAge', 12))]
    public function test7()
    {
    }
}
class TestPropertiesAndAttribute extends BaseTestCase
{
    /**
     * @test 通过属性验证器验证
     */
    public function testValidatorForProperties()
    {
        $validators = get_class_method_validator(PropertiesAndAttributeClass::class, 'test');
        $data       = [];
        foreach ($validators as $validator) {
            $_data = $validator->check([
                'name' => '123',
                'age'  => 18,
                'ttl'  => 999
            ]);
            $data = array_merge($data, $_data);
        }

        $this->assertSame('123', $data['name']);
        $this->assertSame(18, $data['age']);
        $this->assertArrayNotHasKey('ttl', $data);
    }

    /**
     * @test 测试属性验证器由PropertyValidator类提供
     */
    public function testValidatorForPropertiesClass()
    {
        $validators = get_class_method_validator(PropertiesAndAttributeClass::class, 'test2');
        $data       = [];
        foreach ($validators as $validator) {
            $_data = $validator->check([
                'name' => '123',
                'age'  => 18,
                'ttl'  => 999
            ]);
            $data = array_merge($data, $_data);
        }

        $this->assertSame('123', $data['name']);
        $this->assertArrayNotHasKey('ttl', $data);
        $this->assertArrayNotHasKey('age', $data);
    }

    /**
     * @test 测试验证器和属性验证器同时存在
     */
    public function testValidatorForValidateAndDataClass()
    {
        $validators = get_class_method_validator(PropertiesAndAttributeClass::class, 'test3');
        $data       = [];
        foreach ($validators as $validator) {
            $_data = $validator->check([
                'name' => '123',
                'age'  => 18,
                'ttl'  => 999,
                'desc' => '描述',
                'id'   => 1
            ]);
            $data = array_merge($data, $_data);
        }

        $this->assertSame('123', $data['name']);
        $this->assertSame(18, $data['age']);
        $this->assertArrayNotHasKey('ttl', $data);
        $this->assertSame('描述', $data['desc']);
        $this->assertSame(1, $data['id']);
    }

    /**
     * @test 测试验证器和属性验证器同时存在,并且指定验证字段
     */
    public function testValidatorForValidateAndDataClassAssignField()
    {
        $validators = get_class_method_validator(PropertiesAndAttributeClass::class, 'test4');
        $data       = [];
        foreach ($validators as $validator) {
            $_data = $validator->check([
                'name' => '123',
                'age'  => 18,
                'ttl'  => 999,
                'desc' => '描述',
                'id'   => 1
            ]);
            $data = array_merge($data, $_data);
        }

        $this->assertSame(18, $data['age']);
        $this->assertSame('描述', $data['desc']);
        $this->assertArrayNotHasKey('ttl', $data);
        $this->assertArrayNotHasKey('name', $data);
        $this->assertArrayNotHasKey('id', $data);
    }

    /**
     * @test 测试验证器和属性验证器同时存在,并且指定验证场景
     */
    public function testValidatorForValidateAndDataClassAssignScene()
    {
        $validators = get_class_method_validator(PropertiesAndAttributeClass::class, 'test5');
        $data       = [];
        foreach ($validators as $validator) {
            $_data = $validator->check([
                'name' => '123',
                'age'  => 18,
                'ttl'  => 999,
                'desc' => '描述',
                'id'   => 1
            ]);
            $data = array_merge($data, $_data);
        }

        $this->assertSame(18, $data['age']);
        $this->assertSame('描述', $data['desc']);
        $this->assertArrayNotHasKey('ttl', $data);
        $this->assertArrayNotHasKey('name', $data);
        $this->assertArrayNotHasKey('id', $data);
    }

    public function testValidatorForPropertyValidatorAndUseAfter()
    {
        $this->expectException(ValidateException::class);
        $this->expectExceptionMessage('未成年人不能注册');
        $validators = get_class_method_validator(PropertiesAndAttributeClass::class, 'test6');
        $data       = [];
        foreach ($validators as $validator) {
            $_data = $validator->check([
                'name' => 'test',
                'age'  => 12,
            ]);
            $data = array_merge($data, $_data);
        }
    }

    public function testValidatorForPropertyValidatorAndUseAfterSetParams()
    {
        $validators = get_class_method_validator(PropertiesAndAttributeClass::class, 'test7');
        $data       = [];
        foreach ($validators as $validator) {
            $_data = $validator->check([
                'name' => 'test',
                'age'  => 12,
            ]);
            $data = array_merge($data, $_data);
        }

        $this->assertSame(12, $data['age']);
    }
}
