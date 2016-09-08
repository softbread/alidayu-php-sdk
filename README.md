# alidayu-php-sdk
This is a PHP SDK for Ali Dayu based on Guzzle 6.

This project is inspired by another PHP curl reversion for Ali Dayu
-- https://github.com/Verytops/alisms

一个简单的阿里大于短信发送的PHP SDK。如果项目已经有composer, 安装使用就更简单了。
本SDK是因为本人看到 https://github.com/Verytops/alisms 项目而受到启发, 然后决定为composer重写。
写得很赶, 很多东西没有按正规的composer project来写, 欢迎pull 或者 fork

##1. Pre-Request
- Composer
- PHP5.5+

##2. Install

`composer require softbread/alidayu-php-sdk:dev-master`

##3. Typical Usage

###a. Use setters:
```
$sendSms = new Softbread\AlidayuSdk\AliSms;
$sendSms->setEnv(false); // use sandbox or not
$sendSms->setKey('api_key'); // set API key
$sendSms->setSecret('api_secret'); // set API secret
$sendSms->setSmsSign('sms_free_sign'); // set free sign in SMS content
$sendSms->setTemplateParams([
  'param1' => 'var1',
  'param2' => 'var2',
]); // set template params
$sendSms->setTemplate('template_id'); // set template ID
$sendSms->setSmsMobile('13000000000'); // set mobile number
$sendSms->send();
```

###b. Use callable function for easier dependency injection
```
$sendSms = new Softbread\AlidayuSdk\AliSms([
    'env'    => false,
    'key'    => 'api_key',
    'secret' => 'api_secret',
]); // inject config as array

$sendSms->send(
    '13000000000',  // mobile number
    'sms_free_sign' , // free sign in SMS content
    'template_id',  // template ID
    [
      'param1' => 'var1',
      'param2' => 'var2',
    ]  // template params
);
```

##4. To-do
- Add Voice-Text message
- Add unit tests
- Improve exception detection and handling
