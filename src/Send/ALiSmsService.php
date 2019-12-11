<?php

declare(strict_types=1);

namespace XiaoLaoMen\ALiSms\Send;

use Hyperf\Di\Annotation\Inject;
use Hyperf\Guzzle\ClientFactory;

class ALiSmsService
{

    /**
     * @var \Hyperf\Guzzle\ClientFactory
     */
    private $clientFactory;

    private $url = 'http://dysmsapi.aliyuncs.com/?';

    public function __construct(ClientFactory $clientFactory)
    {
        $this->clientFactory = $clientFactory;
    }

    private function percentEncode($string) {

        $string = urlencode ( $string );

        $string = preg_replace ( '/\+/', '%20', $string );

        $string = preg_replace ( '/\*/', '%2A', $string );

        $string = preg_replace ( '/%7E/', '~', $string );

        return $string;

    }

    protected function getPublicParam()
    {
        return  $params = array (
            'Version' => '2017-05-25',
            'Timestamp' => gmdate ( 'Y-m-d\TH:i:s\Z' ),
            'SignatureVersion' => '1.0',
            'SignatureNonce' => uniqid (),
            'SignatureMethod' => 'HMAC-SHA1',
            'Format' => 'JSON'
        );
    }

    protected function getSign(string $accessKeySecret,$array=array())
    {

        $params = $this->getPublicParam();

        $newArray = array_merge($params,$array);

        unset($newArray['Signature']);

        ksort ( $newArray );

        $canonicalizedQueryString = '';

        foreach ( $newArray as $key => $value ) {

            $canonicalizedQueryString .= '&' . $this->percentEncode ( $key ) . '=' . $this->percentEncode ( $value );

        }

        $stringToSign = 'GET&%2F&' . $this->percentencode ( substr ( $canonicalizedQueryString, 1 ) );

        $signature = base64_encode ( hash_hmac ( 'sha1', $stringToSign, $accessKeySecret . '&', true ) );
        $newArray ['Signature'] = $signature;

        $client = $this->clientFactory->create($options=[]);

        $url = $this->url . http_build_query ( $newArray );

        $result = $client->request('get',$url);

        return json_decode($result->getBody()->getContents(),true);
    }


    public function sendSms(string $accessKeySecret,$array=array())
    {
        $array['Action']='SendSms';
        return $this->getSign($accessKeySecret,$array);
    }

    public function phoneNumberJson(string $accessKeySecret,$array=array())
    {
        $array['Action']='SendBatchSms';
        return $this->getSign($accessKeySecret,$array);
    }

    public function getSendDetails(string $accessKeySecret,$array=array())
    {
        $array['Action']='QuerySendDetails';
        return $this->getSign($accessKeySecret,$array);
    }

}