<?php
/**
 * Created by https://yue.dev
 * Author: Justin Wang
 * Email: hi@yue.dev
 */

use Yue\MailGunEmailValidation\EmailValidator;

class ValidatorTest extends \PHPUnit\Framework\TestCase
{
    protected $privateKey = '';
    protected $publicKey = '';

    public function testValidatorV3(){
        $validator = EmailValidator::GetInstance([
            'version'=>EmailValidator::V3,
            'public_key'=>$this->publicKey,
            'private_key'=>$this->privateKey,
        ]);
        $email = 'justinwang24@yahoo.com.au';

        try{
            $result = $validator->validate($email);
            $this->assertTrue($result->success());
        }catch (\Exception $exception){
            var_dump($exception->getMessage());
            $this->assertTrue(1===2);
        }
    }

    public function testValidatorV4(){
        $validator = EmailValidator::GetInstance([
            'public_key'=>$this->publicKey,
            'private_key'=>$this->privateKey,
        ]);
        $email = 'justinwang24@yahoo.com.au';

        try{
            $result = $validator->validate($email);
            $this->assertTrue($result->success());
        }catch (\Exception $exception){
            var_dump($exception->getMessage());
            $this->assertTrue(1===2);
        }
    }

    public function testValidatorV4ForNotValid(){
        $validator = EmailValidator::GetInstance([
            'public_key'=>$this->publicKey,
            'private_key'=>$this->privateKey,
        ]);
        $email = 'justinwang24';

        $this->expectException(EmailValidationException::class);
        $validator->validate($email);
    }

    /**
     * Test V3 result
     */
    public function testResultV4(){
        try{
            $email = 'justinwang24@yahoo.com.au';
            $res = \Yue\MailGunEmailValidation\CurlClient::single(
                \Yue\MailGunEmailValidation\EmailValidator::V4,
                \Yue\MailGunEmailValidation\EndPoint::Create(\Yue\MailGunEmailValidation\EmailValidator::V4),
                $email,
                $this->privateKey
            );

            $this->assertEquals(200, $res->getStatusCode());
            $this->assertNotNull($res->getBody());

            $result = new \Yue\MailGunEmailValidation\EmailValidationResult($res->getBody()->getContents());

            $this->assertTrue($result->success());

        }catch (\GuzzleHttp\Exception\GuzzleException $exception){
            var_dump($exception->getMessage());
            $this->assertTrue(1===2);
        }
    }

    /**
     * Test V3 result
     */
    public function testResultV3(){
        try{
            $email = 'justinwang24@yahoo.com.au';
            $res = \Yue\MailGunEmailValidation\CurlClient::single(
                \Yue\MailGunEmailValidation\EmailValidator::V3,
                \Yue\MailGunEmailValidation\EndPoint::Create(\Yue\MailGunEmailValidation\EmailValidator::V3),
                $email,
                $this->publicKey
            );

            $this->assertEquals(200, $res->getStatusCode());
            $this->assertNotNull($res->getBody());

            $result = new \Yue\MailGunEmailValidation\EmailValidationResult($res->getBody()->getContents());

            $this->assertTrue($result->success());

        }catch (\GuzzleHttp\Exception\GuzzleException $exception){
            var_dump($exception->getMessage());
            $this->assertTrue(1===2);
        }
    }

    /**
     * Test V3 client
     */
    public function testEmailValidateSingleV3(){
        try{
            $email = 'justinwang24@yahoo.com.au';
            $res = \Yue\MailGunEmailValidation\CurlClient::single(
                \Yue\MailGunEmailValidation\EmailValidator::V3,
                \Yue\MailGunEmailValidation\EndPoint::Create(\Yue\MailGunEmailValidation\EmailValidator::V3),
                $email,
                $this->publicKey
            );

            $this->assertEquals(200, $res->getStatusCode());
            $this->assertNotNull($res->getBody());

            $json = $res->getBody()->getContents();
            $data = json_decode($json, true);

            $this->assertArrayHasKey('address',$data);
            $this->assertEquals($email, $data['address']);
            $this->assertArrayHasKey('did_you_mean',$data);
            $this->assertArrayHasKey('is_disposable_address',$data);
            $this->assertArrayHasKey('is_role_address',$data);
            $this->assertArrayHasKey('is_valid',$data);
            $this->assertArrayHasKey('mailbox_verification',$data);
            $this->assertArrayHasKey('parts',$data);
            $this->assertArrayHasKey('reason',$data);

            $this->assertIsArray($data['parts']);

        }catch (\GuzzleHttp\Exception\GuzzleException $exception){
            var_dump($exception->getMessage());
            $this->assertTrue(1===2);
        }
    }

    /**
     * Test V4 client
     */
    public function testEmailValidateSingleV4(){
        try{
            $email = 'justinwang24@yahoo.com.au';
            $res = \Yue\MailGunEmailValidation\CurlClient::single(
                \Yue\MailGunEmailValidation\EmailValidator::V4,
                \Yue\MailGunEmailValidation\EndPoint::Create(\Yue\MailGunEmailValidation\EmailValidator::V4),
                $email,
                $this->privateKey
            );

            $this->assertEquals(200, $res->getStatusCode());
            $this->assertNotNull($res->getBody());

            $json = $res->getBody()->getContents();
            $data = json_decode($json, true);

            $this->assertArrayHasKey('address',$data);
            $this->assertEquals($email, $data['address']);
            $this->assertArrayHasKey('is_disposable_address',$data);
            $this->assertArrayHasKey('is_role_address',$data);
            $this->assertArrayHasKey('reason',$data);
            $this->assertArrayHasKey('result',$data);
            $this->assertEquals(\Yue\MailGunEmailValidation\IValidationResult::STATUS_DELIVERABLE, $data['result']);
            $this->assertArrayHasKey('risk',$data);
            $this->assertEquals(\Yue\MailGunEmailValidation\IValidationResult::RISK_LOW, $data['risk']);

        }catch (\GuzzleHttp\Exception\GuzzleException $exception){
            var_dump($exception->getMessage());
            $this->assertTrue(1===2);
        }
    }

    /**
     * Test endpoint for v3 and v4
     */
    public function testEndPoint(){
        $v3Api = \Yue\MailGunEmailValidation\EndPoint::Create(\Yue\MailGunEmailValidation\EmailValidator::V3);
        $this->assertEquals(
            'https://api.mailgun.net/v3/address/validate',
            $v3Api
        );
        $v4Api = \Yue\MailGunEmailValidation\EndPoint::Create(\Yue\MailGunEmailValidation\EmailValidator::V4);
        $this->assertEquals(
            'https://api.mailgun.net/v4/address/validate',
            $v4Api
        );
    }
}