<?php

namespace Itwmw\Validate\Attributes\Test;

use Itwmw\Validate\Attributes\PropertyValidator;
use Itwmw\Validate\Attributes\Rules\Numeric;
use Itwmw\Validate\Attributes\Rules\Required;
use Itwmw\Validate\Attributes\Rules\StringRule;
use Itwmw\Validate\Attributes\Validator;
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

    public function checkAge(): bool|string
    {
        return $this->age >= 18 ? true : '未成年人不能注册';
    }
}

class PropertiesAndAttributeClass
{
    #[Validator(dataClass: PropertiesAndAttributeDataClass::class)]
    public function test()
    {
    }

    #[Validator(dataClass: new PropertyValidator(PropertiesAndAttributeDataClass::class, ['name']))]
    public function test2()
    {
    }

    #[Validator(PropertiesAndAttributeValidator::class, dataClass: PropertiesAndAttributeDataClass::class)]
    public function test3()
    {
    }

    #[Validator(
        PropertiesAndAttributeValidator::class,
        fields: ['desc'],
        dataClass: new PropertyValidator(
            PropertiesAndAttributeDataClass::class,
            [
                'age'
            ]
        )
    )]
    public function test4()
    {
    }

    #[Validator(
        PropertiesAndAttributeValidator::class,
        scene: 'only_desc',
        dataClass: new PropertyValidator(
            PropertiesAndAttributeDataClass::class,
            [
                'age'
            ]
        )
    )]
    public function test5()
    {
    }

    #[Validator(dataClass: new PropertyValidator(
        PropertiesAndAttributeDataClass::class,
        after: 'checkAge'
    ))]
    public function test6()
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
        $validators = get_class_method_validator(PropertiesAndAttributeClass::class, 'test6');
        $data       = [];
        foreach ($validators as $validator) {
            $_data = $validator->check([
                'name' => 'test',
                'age'  => 18,
            ]);
            $data = array_merge($data, $_data);
        }

        $this->assertSame(18, $data['age']);
    }
}
