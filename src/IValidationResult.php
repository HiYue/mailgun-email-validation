<?php
/**
 * Created by https://yue.dev
 * Author: Justin Wang
 * Email: hi@yue.dev
 */

namespace Yue\MailGunEmailValidation;

interface IValidationResult
{
    // The mailbox portion of the email
    const ROLE_ADMIN            = 'admin';
    const ROLE_SALES            = 'sales';
    const ROLE_WEB_MASTER       = 'webmaster';
    // Either “deliverable”, “undeliverable” or “unknown” status given the evaluation
    const STATUS_UNKNOWN        = 'unknown';
    const STATUS_UNDELIVERABLE  = 'undeliverable';
    const STATUS_DELIVERABLE    = 'deliverable';
    // “High”, “Medium”, “Low” or “null” Depending on the evaluation of all aspects of the given email
    const RISK_HIGH             = 'high';
    const RISK_MEDIUM           = 'medium';
    const RISK_LOW              = 'low';
    const RISK_NULL             = 'null';

    /**
     * If the result is success
     * @return bool
     */
    public function success();

    /**
     * Get the result in array format
     * @return array
     */
    public function getReason();

    /**
     * Get risk
     * @return string
     */
    public function getRisk();

    /**
     * Get original content
     * @return array
     */
    public function getOriginalContent();
}