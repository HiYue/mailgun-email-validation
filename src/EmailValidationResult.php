<?php
/**
 * Created by https://yue.dev
 * Author: Justin Wang
 */

namespace Yue\MailGunEmailValidation;

class EmailValidationResult implements IValidationResult
{
    // Email address being validated
    private $address = '';

    // (Optional) Null if nothing, however if a potential typo is made, the closest suggestion is provided
    private $did_you_mean = null;

    // If the domain is in a list of disposable email addresses, this will be appropriately categorized
    private $is_disposable_address = false;

    // Checks the mailbox portion of the email if it matches a specific role type (‘admin’, ‘sales’, ‘webmaster’)
    private $is_role_address = false;

    // List of potential reasons why a specific validation may be unsuccessful.
    private $reason = [];

    // Either “deliverable”, “undeliverable” or “unknown” status given the evaluation.
    private $result = '';

    // “High”, “Medium”, “Low” or “null” Depending on the evaluation of all aspects of the given email.
    private $risk = '';

    /**
     * V3 version
     */
    private $is_valid = false;
    private $mailbox_verification = false;

    private $content = null;

    /**
     * EmailValidationResult constructor.
     * @param string $responseInJson
     */
    public function __construct($responseInJson)
    {
        $res                            = json_decode($responseInJson, true);
        $this->address                  = $res['address'];   // v3 + v4
        $this->did_you_mean             = isset($res['did_you_mean']) ? $res['did_you_mean'] : null; // v3 + v4
        $this->is_disposable_address    = $res['is_disposable_address']; // v3 + v4
        $this->is_role_address          = $res['is_role_address']; // v3 + v4
        $this->reason                   = $res['reason']; // v3 + v4
        $this->result                   = isset($res['result']) ? $res['result'] : null;  // v4
        $this->risk                     = isset($res['risk']) ? $res['risk'] : null; // v4
        // For V3
        $this->is_valid                 = isset($res['is_valid']) ? $res['is_valid'] : false;
        $this->mailbox_verification     = isset($res['mailbox_verification'])
            ? 'true'==strtolower($res['mailbox_verification']) : false;
        $this->content                  = $res;
    }

    public function success()
    {
        return $this->result === IValidationResult::STATUS_DELIVERABLE ||
            ($this->is_valid && $this->mailbox_verification);
    }

    public function getRisk()
    {
        return $this->risk;
    }

    public function getReason()
    {
        return is_array($this->reason) ? $this->reason : [$this->reason];
    }

    public function getOriginalContent()
    {
        return $this->content;
    }
}