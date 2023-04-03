<?php

namespace Itwmw\Validate\Attributes\Test;

use Itwmw\Validate\Attributes\Message;
use Itwmw\Validate\Attributes\Preprocessor;
use Itwmw\Validate\Attributes\Rules\Numeric;
use Itwmw\Validate\Attributes\Rules\Required;
use Itwmw\Validate\Attributes\Rules\StringRule;
use W7\Validate\Exception\ValidateException;
use W7\Validate\Support\PreprocessorOptions;
use W7\Validate\Support\PreprocessorParams;

class PropertiesTest
{
    #[Required]
    #[Preprocessor('test', PreprocessorOptions::WHEN_EMPTY)]
    public string $name;

    #[Numeric]
    #[Required]
    #[Preprocessor('setAge', PreprocessorOptions::WHEN_EMPTY)]
    public int $age;

    #[Required]
    #[StringRule]
    #[Preprocessor('trim', PreprocessorOptions::WHEN_NOT_EMPTY, PreprocessorParams::Value)]
    #[Message('自我介绍')]
    public string $selfIntroduction;

    public function setAge(): int
    {
        return 18;
    }
}
class TestPropertiesPreprocessor extends BaseTestCase
{
    public function testSetDefault(): void
    {
        $data = validate_attribute(PropertiesTest::class, fields: ['name']);
        $this->assertEquals('test', $data->name);

        $data = validate_attribute(PropertiesTest::class, ['name' => 'yuyu'], fields: ['name']);
        $this->assertEquals('yuyu', $data->name);
    }

    public function testSetAge(): void
    {
        $data = validate_attribute(PropertiesTest::class, fields: ['age']);
        $this->assertEquals(18, $data->age);

        $data = validate_attribute(PropertiesTest::class, ['age' => 20], fields: ['age']);
        $this->assertEquals(20, $data->age);
    }

    public function testTrim(): void
    {
        $data = validate_attribute(PropertiesTest::class, [
            'selfIntroduction' => '  噢哈哈哈  '
        ]);
        $this->assertEquals('噢哈哈哈', $data->selfIntroduction);

        $this->expectException(ValidateException::class);
        $this->expectExceptionMessage('自我介绍 不能为空');
        validate_attribute(PropertiesTest::class, []);
    }
}
