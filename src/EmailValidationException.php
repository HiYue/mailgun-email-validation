<?php
/**
 * Created by PhpStorm.
 * User: Justin Wang
 * Date: 26/4/19
 * Time: 2:29 PM
 */

namespace Yue\MailGunEmailValidation;

class EmailValidationException extends \Exception
{

    public function __construct($msg)
    {
        $this->message = $msg;
    }
}