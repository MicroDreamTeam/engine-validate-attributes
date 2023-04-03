<?php

namespace Itwmw\Validate\Attributes\Test;

use Itwmw\Validate\Attributes\Message;
use Itwmw\Validate\Attributes\Postprocessor;
use Itwmw\Validate\Attributes\Preprocessor;
use Itwmw\Validate\Attributes\Rules\Nullable;
use Itwmw\Validate\Attributes\Rules\Numeric;
use Itwmw\Validate\Attributes\Rules\Required;
use Itwmw\Validate\Attributes\Rules\StringRule;
use W7\Validate\Exception\ValidateException;
use W7\Validate\Support\ProcessorOptions;
use W7\Validate\Support\ProcessorParams;

class PropertiesPreprocessorTest
{
    #[Required]
    #[Preprocessor('test', ProcessorOptions::WHEN_EMPTY)]
    public string $name;

    #[Numeric]
    #[Required]
    #[Preprocessor('setAge', ProcessorOptions::WHEN_EMPTY)]
    public int $age;

    #[Required]
    #[StringRule]
    #[Preprocessor('trim', ProcessorOptions::WHEN_NOT_EMPTY, ProcessorParams::Value)]
    #[Message('自我介绍')]
    public string $selfIntroduction;

    #[Nullable]
    #[Preprocessor('trim', ProcessorOptions::WHEN_NOT_EMPTY, ProcessorParams::Value)]
    #[Preprocessor('base64_encode', ProcessorOptions::WHEN_NOT_EMPTY, ProcessorParams::Value)]
    public ?string $info;

    #[Nullable]
    #[Preprocessor('trim', ProcessorOptions::WHEN_NOT_EMPTY, ProcessorParams::Value)]
    #[Postprocessor('base64_encode', ProcessorOptions::WHEN_NOT_EMPTY, ProcessorParams::Value)]
    public ?string $info2;

    public function setAge(): int
    {
        return 18;
    }
}
class TestPropertiesPreprocessor extends BaseTestCase
{
    public function testSetDefault(): void
    {
        $data = validate_attribute(PropertiesPreprocessorTest::class, fields: ['name']);
        $this->assertEquals('test', $data->name);

        $data = validate_attribute(PropertiesPreprocessorTest::class, ['name' => 'yuyu'], fields: ['name']);
        $this->assertEquals('yuyu', $data->name);
    }

    public function testSetAge(): void
    {
        $data = validate_attribute(PropertiesPreprocessorTest::class, fields: ['age']);
        $this->assertEquals(18, $data->age);

        $data = validate_attribute(PropertiesPreprocessorTest::class, ['age' => 20], fields: ['age']);
        $this->assertEquals(20, $data->age);
    }

    public function testTrim(): void
    {
        $data = validate_attribute(PropertiesPreprocessorTest::class, [
            'selfIntroduction' => '  噢哈哈哈  '
        ]);
        $this->assertEquals('噢哈哈哈', $data->selfIntroduction);

        $this->expectException(ValidateException::class);
        $this->expectExceptionMessage('自我介绍 不能为空');
        validate_attribute(PropertiesPreprocessorTest::class, []);
    }

    public function testBase64Encode(): void
    {
        $data = validate_attribute(PropertiesPreprocessorTest::class, [
            'info' => '    test  '
        ], ['info']);
        $this->assertEquals('dGVzdA==', $data->info);

        $data = validate_attribute(PropertiesPreprocessorTest::class, [
            'info2' => '    test  '
        ], ['info2']);
        $this->assertEquals('dGVzdA==', $data->info2);
    }
}
