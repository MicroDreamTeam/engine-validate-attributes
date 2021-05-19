## 微擎表单验证器注解支持
使用注解的方式来指定对应的验证器，场景或验证的字段

## 安装
```shell
composer require itwmw/engine-validate-attributes
```

## 使用
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
use W7\Validate\Support\Storage\ValidateMiddlewareConfig;

ValidateMiddlewareConfig::instance()->setValidateFactory(new ValidateAttributesFactory());
```
接下来中间件将自动获取注解指定的验证器来完成验证

### 独立使用
可以通过以下方式来获取到指定的验证器
```php
$validate = (new \Itwmw\Validate\Attributes\ValidateAttributesFactory())->getValidate(UserController::class,"login");

$validate->check($userInput);
```
`getValidate`方法需要两个参数
- `$controller` 控制器名或者类名
- `$scene` 方法名或者场景名，非必填