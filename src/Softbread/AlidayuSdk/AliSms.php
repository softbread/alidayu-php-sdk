<?php

namespace Softbread\AlidayuSdk;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;


class AliSms
{
    const ALI_DAYU_REST_URL = 'https://eco.taobao.com/router/rest';
    const ALI_DAYU_REST_SANDBOX_URL = 'https://gw.api.tbsandbox.com/router/rest';
    const ALI_DAYU_REST_VERSION = '2.0';
    const ALI_DAYU_METHOD_SEND_SMS = 'alibaba.aliqin.fc.sms.num.send';
    
    public $error = [];
    private $_key;
    private $_secret;
    private $_setting = [];
    private $_restUrl;
    
    public function setEnv($useSandbox = false)
    {
        $this->_restUrl = $useSandbox
            ? self::ALI_DAYU_REST_SANDBOX_URL
            : self::ALI_DAYU_REST_URL;
    }
    
    public function setKey($key)
    {
        $this->_key = $key;
    }
    
    public function setSecret($secret)
    {
        $this->_secret = $secret;
    }
    
    public function setSmsMobile($mobile = '')
    {
        $this->_setting['rec_num'] = $mobile;
    }
    
    public function setSmsSign($sign = '')
    {
        $this->_setting['sms_free_sign_name'] = $sign;
    }
    
    public function setTemplate($code = '')
    {
        $this->_setting['sms_template_code'] = $code;
    }
    
    public function setTemplateParams($params = [])
    {
        $this->_setting['sms_param'] = json_encode($params);
    }
    
    public function send($smsMobile = '', $smsSign = '', $template = '', $templateParams = [])
    {
        $params = $this->loadAllParams($smsMobile, $smsSign, $template, $templateParams);
        $params['sign'] = $this->generateSignature($params);
        
        $reponse = $this->sendRequest($params);
        if ($reponse !== null) {
            $res = json_decode($reponse, true);
            $res = array_pop($res);
            if (isset($res['result'])) return true;
            $this->error = $res;
        } else {
            $this->error = [
                'code' => 0,
                'msg'  => 'HTTP_RESPONSE_NOT_WELL_FORMED',
            ];
        }
        return false;
    }
    
    private function loadAllParams($smsMobile, $smsSign, $template, $templateParams)
    {
        if ($smsMobile !== '' && $smsSign !== '' && $template !== '') {
            $this->setSmsMobile($smsMobile);
            $this->setSmsSign($smsSign);
            $this->setTemplate($template);
            $this->setTemplateParams($templateParams);
        }
        
        return [
            'app_key'     => $this->_key,
            'format'      => 'json',
            'method'      => self::ALI_DAYU_METHOD_SEND_SMS,
            'v'           => self::ALI_DAYU_REST_VERSION,
            'timestamp'   => date('Y-m-d H:i:s'),
            'sign_method' => 'md5',
            'sms_type'    => 'normal',
        ] + $this->_setting;
    }
    
    private function generateSignature($params)
    {
        ksort($params);
        
        $paramString = '';
        foreach ($params as $k => $v) {
            if (is_string($v) && '@' != substr($v, 0, 1)) {
                $paramString .= $k . $v;
            }
        }
        $paramString = $this->_secret . $paramString . $this->_secret;
        return strtoupper(md5($paramString));
    }
    
    protected function sendRequest($params)
    {
        $client = new Client([
            'base_uri' => $this->_restUrl,
            'timeout'  => 5.0,
        ]);
        
        $response = null;
        try {
            $response = $client->request('GET', $this->_restUrl, $params);
        } catch (ClientException $e) {
            return null;
        }
        
        return $response->getBody();
    }
}
