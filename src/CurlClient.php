<?php
/**
 * Created by https://yue.dev
 * Author: Justin Wang
 * Email: hi@yue.dev
 */

namespace Yue\MailGunEmailValidation;


use GuzzleHttp\Client;

class CurlClient
{
    /**
     * @param $endpoint
     * @param $email
     * @param $apiKey
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function single($version,$endpoint, $email, $apiKey){
        $client = new Client();
        if($version === EmailValidator::V4){
            return $client->get(
                $endpoint,
                [
                    'query'=>[
                        'address'=>$email
                    ],
                    'auth'=>[
                        'api',
                        $apiKey
                    ]
                ]
            );
        }
        else{
            return $client->get(
                $endpoint,
                [
                    'query'=>[
                        'address'=>$email,
                        'mailbox_verification' => true
                    ],
                    'auth'=>[
                        'api',
                        $apiKey
                    ]
                ]
            );
        }
    }

    public static function bulk($apiKey, $emails){

    }
}