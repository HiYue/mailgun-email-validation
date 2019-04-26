<?php
/**
 * Created by https://yue.dev
 * Author: Justin Wang
 * Email: hi@yue.dev
 */

namespace Yue\MailGunEmailValidation;


class EndPoint
{
    public static function Create($version = EmailValidator::V4){
        if($version === EmailValidator::V3){
            return 'https://api.mailgun.net/v3/address/validate';
        }
        else{
            return 'https://api.mailgun.net/v4/address/validate';
        }
    }
}