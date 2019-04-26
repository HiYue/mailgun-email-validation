<?php
/**
 * Created by https://yue.dev
 * Author: Justin Wang
 */
namespace Yue\MailGunEmailValidation;

use GuzzleHttp\Exception\GuzzleException;

class EmailValidator {
    const V3 = 'v3';
    const V4 = 'v4';

    /**
     * @var EmailValidator $validator
     */
    private static $validator = null;

    private $api_key = null;
    private $endpoint = null;
    private $version = null;
    private $publicKey = null;
    private $privateKey = null;

    /**
     * EmailValidator constructor.
     *
     * $params:
     * [
     *    'version' => 'v3' or 'v4' // Optional: default is V4
     *    'public_key' => 'your_public_key', // Required
     *    'private_key' => 'your_private_key', // Required
     * ]
     * @param array $params
     */
    private function __construct($params = [])
    {
        // By default, use latest V4
        $this->version      = isset($params['version']) ? strtolower($params['version']) : EmailValidator::V4;
        $this->endpoint     = EndPoint::Create($this->version);

        // Init the api key: V3 shall use public key; V4 shall use public key
        $this->api_key      = $this->version === EmailValidator::V4
            ? $params['private_key'] : $params['public_key'];
        $this->privateKey   = $params['private_key'];
        $this->publicKey    = $params['public_key'];
    }

    /**
     * Get the singleton validator instance
     * @param array $params
     * @return EmailValidator
     */
    public static function GetInstance($params = []){
        if(is_null(self::$validator)){
            self::$validator = new EmailValidator($params);
        }
        return self::$validator;
    }

    /**
     * @param string $email
     * @return EmailValidationResult
     * @throws EmailValidationException
     * @throws GuzzleException
     */
    public function validate($email){
        $result = null;
        // Check the email address format by PHP itself
        if(filter_var($email,FILTER_VALIDATE_EMAIL)){
            $res = CurlClient::single($this->version,$this->endpoint, $email, $this->api_key);
            if($res->getStatusCode() === 200){
                $result = new EmailValidationResult($res->getBody()->getContents());
            }
            else{
                throw new EmailValidationException('MailGun API service seems not available because get status code: '.$res->getStatusCode());
            }
        }
        else{
            throw new EmailValidationException('The email you provided is not valid.');
        }
        return $result;
    }

    /**
     * Set the api key on the fly
     * @param $key
     * @return EmailValidator
     */
    public function setApiKey($key){
        $this->api_key = $key;
        return $this;
    }
}