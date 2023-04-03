## 微擎表单验证器注解支持
使用注解的方式来指定对应的验证器，场景或验证的字段

## 安装
```shell
composer require itwmw/engine-validate-attributes
```

## 控制器使用
```php
use Itwmw\Validate\Attributes\Validator;

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
```

Validator有三个参数分别是：

- `$validate` 验证器的完整命名空间
- `$scene` 场景名称，如果提供了此值，则`$fields`参数无效
- `$fields` 要验证的字段，数组类型

### 中间件使用
如果你使用了验证器提供的中间件，则可以将本扩展注册到中间件配置中：
```php
use Itwmw\Validate\Attributes\ValidateAttributesFactory;
use Itwmw\Validate\Middleware\ValidateMiddlewareConfig;

ValidateMiddlewareConfig::instance()->setValidateFactory(new ValidateAttributesFactory());
```
接下来中间件将自动获取注解指定的验证器来完成验证

### 独立使用
可以通过以下方式来获取到指定的验证器
```php
$validate = (new Itwmw\Validate\Attributes\ValidateAttributesFactory())->getValidate(UserController::class, 'login');
// or
$validate = get_class_method_validator(UserController::class, 'login');
```
将返回验证器数组，如果没有指定验证器，则返回`false`

`getValidate`方法需要两个参数
- `$controller` 控制器名或者类名
- `$scene` 方法名或者场景名，非必填
## 类属性注解使用
目前注解支持验证器的全部内置规则以及数据处理器

定义：
```php
class UserInfo
{
    #[Required]
    #[ChsAlphaNum]
    #[Between(min:1, max: 10)]
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
```
验证：
```php
$data = validate_attribute(UserInfo::class, [
    'nickname' => 'test',
    'email'    => '123@qq.com',
    'remark'   => '<script>alert(1)</script>备注说明'
]);

print_r($data);

//UserInfo Object
//(
//    [nickname] => test
//    [email] => 123@qq.com
//    [group] => Array
//        (
//            [0] => 0
//        )
//
//    [remark] => 备注说明
//)
```
解释：
- `$nickname` 字段必填，只能是汉字、字母和数字，长度在1-10之间, 字段的名称为`昵称`
- `$email` 字段必填，必须是邮箱格式，如果为空则提示`邮箱不能为空`，如果不是邮箱格式则提示`邮箱格式错误`
- `$group` 字段必填，必须是数组，且数组的键必须是整数，如果为空则默认值为`[0]`,验证通过后使用`array_unique`去重
- `$remark` 字段非必填，如果不为空则使用`trim`去除两边空格，使用`removeXss`去除xss，字段的名称为`备注`

- `removeXss` 方法为自定义的数据处理器
- `ProcessorExecCond` 为数据处理器的执行时机,有三个值
    - `ProcessorOptions::WHEN_NOT_EMPTY` 当字段不为空时执行
    - `ProcessorOptions::WHEN_EMPTY` 当字段为空时执行
    - `ProcessorOptions::ALWAYS` 总是执行，默认行为
- `ProcessorParams` 为数据处理器的参数来源，有四个值
    - `ProcessorParams::Value` 使用字段的值作为参数
    - `ProcessorParams::Attribute` 使用字段的键作为参数
    - `ProcessorParams::OriginalData` 使用原始数据作为参数
    - `ProcessorParams::DataAttribute` 提供一个`DataAttribute`类作为参数
- `ProcessorOptions` 为数据处理器的可选参数，有两个值
    - `ProcessorOptions::MULTIPLE` 为同一个字段添加多个处理器时使用，此库中会自动处理
    - `ProcessorOptions::VALUE_IS_ARRAY` 当值为一个数组时使用，此库中无需使用
