#  Email validator with MailGun Service API V3 + V4

## About Mailgun email validator
To validate an email address which submitted by your customer, prevent your website from bad reputation.
Easy, fast, simple and **highly testable**.

## Installation
Require this package in your composer.json and update your dependencies:

```bash
composer require justinwang/mailgun-email-validation
```
## Requirements
**You need to register at <a href="https://www.mailgun.com/" target="_blank">Mailgun</a> first**.

## Usage
```php
require 'vendor/autoload.php';  // Put this line if necessary

use Yue\MailGunEmailValidation\EmailValidator;

// Init the configurations
$config = [
    // Required: Public validation key, in your Mailgun settings.
    'public_key'    =>'your_public_validation_key', 
    
    // Required: Private API key, in your Mailgun settings.
    'private_key'   =>'your_private_api_key',    
    
    // Optional: By default, we will use V4 version API    
    'version'       =>EmailValidator::V3,           
];

// Get validator instance
$validator = EmailValidator::GetInstance($config);

// Validate an email
$email = 'example@your_domain.com';
try{
    $result = $validator->validate($email);
    $valid  = $result->success();   // true for the valid email
}catch (\Exception $exception){
    var_dump($exception->getMessage());
}

// Todo: next ...
```