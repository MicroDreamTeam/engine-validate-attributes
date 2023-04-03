<?php

namespace Itwmw\Validate\Attributes\Test;

use Itwmw\Validate\Attributes\Postprocessor;
use Itwmw\Validate\Attributes\Rules\ArrayRule;
use Itwmw\Validate\Attributes\Rules\Required;
use Itwmw\Validate\Attributes\Rules\StringRule;
use W7\Validate\Support\ProcessorOptions;
use W7\Validate\Support\ProcessorParams;

class PropertiesPostprocessorTest
{
    #[Required]
    #[ArrayRule('@keyInt')]
    #[Postprocessor('array_unique', ProcessorOptions::WHEN_NOT_EMPTY, ProcessorParams::Value)]
    public array $group = [];

    #[Required]
    #[StringRule]
    #[Postprocessor('trim', ProcessorOptions::WHEN_NOT_EMPTY, ProcessorParams::Value)]
    public string $name;

    #[Postprocessor('trim', ProcessorOptions::WHEN_NOT_EMPTY, ProcessorParams::Value)]
    #[Postprocessor('base64_encode', ProcessorOptions::WHEN_NOT_EMPTY, ProcessorParams::Value)]
    public string $info;
}
class TestPropertiesPostprocessor extends BaseTestCase
{
    public function testArrayUnique(): void
    {
        $data = validate_attribute(PropertiesPostprocessorTest::class, [
            'group' => [
                1, 2, 3, 4, 1, 1, 1, 1, 2, 2, 2
            ]
        ], ['group']);
        $this->assertEquals([1, 2, 3, 4], $data->group);
    }

    public function testTrim(): void
    {
        $data = validate_attribute(PropertiesPostprocessorTest::class, [
            'name' => '  噢哈哈哈  '
        ], ['name']);
        $this->assertEquals('噢哈哈哈', $data->name);
    }

    public function testBase64Encode(): void
    {
        $data = validate_attribute(PropertiesPostprocessorTest::class, [
            'info' => '    test  '
        ], ['info']);

        $this->assertEquals('dGVzdA==', $data->info);
    }
}
