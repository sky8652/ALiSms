# ALiSms
基于hyperf框架的阿里云短信

###发送单条

        $params = array (
            'SignName' => '签名',
            'AccessKeyId' => 'xxx',
            'TemplateCode' => '模板id',
            'PhoneNumbers' => '1xxxxxxx',
            'TemplateParam' => '{"code":"1234"}',
            'RegionId' => 'cn-beijing',
        );
        
phoneNumberJson(批量发送)
        
            $params = array (
            'PhoneNumberJson'=>json_encode(array('手机号')),
            'SignNameJson'=>json_encode(array('签名')),
            'TemplateCode'=>'模板id',
            'AccessKeyId' => 'xxxx',
            'TemplateParamJson'=>json_encode(array(array('code'=>'0000'))),
        );
        
getSendDetails(查询发送记录)

        $params = array (
                    'CurrentPage'=>'1',
                    'PageSize'=>'30',
                    'PhoneNumber'=>'手机号',
                    'SendDate'=>'20191210',
                    'AccessKeyId' => 'xxxx',
                );        
                
调用

    $accessKeySecret='xxxx';
    $this->ALiSmsService->getSendDetails($accessKeySecret,$params);

返回结果具体参数看阿里云.返回格式是数组格式.

    array(4) {
      ["Message"]=>
      string(2) "OK"
      ["RequestId"]=>
      string(36) "9FFC1339-249D-4335-9DBE-87843DA315CB"
      ["BizId"]=>
      string(20) "785908076031923306^0"
      ["Code"]=>
      string(2) "OK"
    }
 
 
