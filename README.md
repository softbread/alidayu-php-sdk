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

```
$sendSms = new Softbread\AlidayuSdk\AliSms;
$sendSms->setKey('api_key');
$sendSms->setSecret('api_secret');
$sendSms->setSmsSign('sms_free_sign');
$sendSms->setTemplateParams([
  'param1' => 'var1',
  'param2' => 'var2',
]);
$sendSms->setTemplate('template_id');
$sendSms->setSmsMobile('13000000000);
$sendSms->send();
```

##4. To-do
- Add Voice-Text message
- Add unit tests
- Improve exception detection and handling
