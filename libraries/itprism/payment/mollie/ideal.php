<?php
/**
 * @package      ITPrism
 * @subpackage   Payment\iDEAL
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2014 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

// no direct access
defined('JPATH_PLATFORM') or die;

require_once dirname(__FILE__) . "/ideal/Payment.php";

/**
 * This class contains methods that interact
 * with iDEAL payment gateway via Mollie service.
 *
 * @package     ITPrism
 * @subpackage  Payment\iDEAL
 */
class ITPrismPaymentMollieIdeal
{
    private $payment;

    /**
     * Initialize the object.
     *
     * <code>
     * $partnerId = "PARTNER_123";
     *
     * $mollie = new ITPrismPaymentMollieIdeal($partnerId);
     * </code>
     *
     * @param mixed $partnerId
     */
    public function __construct($partnerId)
    {
        $this->payment = new Mollie_iDEAL_Payment($partnerId);
    }

    /**
     * Get a list with banks from Mollie servers.
     *
     * <code>
     * $partnerId = "PARTNER_123";
     *
     * $mollie = new ITPrismPaymentMollieIdeal($partnerId);
     * $mollie->getBanks();
     * </code>
     *
     * @return array
     */
    public function getBanks()
    {
        return $this->payment->getBanks();
    }

    /**
     * Enable test mode which gives ability to make test requests to Mollie servers.
     *
     * <code>
     * $partnerId = "PARTNER_123";
     *
     * $mollie = new ITPrismPaymentMollieIdeal($partnerId);
     * $mollie->enableTestmode();
     * </code>
     */
    public function enableTestmode()
    {
        $this->payment->setTestmode(true);
    }

    /**
     * Create a payment link that will point the user to a payment page on Mollie.
     *
     * <code>
     * $partnerId = "PARTNER_123";
     *
     * $options = array(
     *     "bank_id" = 1,
     *     "amount" = 10,
     *     "description" = "Item name",
     *     "return_url" = "....",
     *     "report" = "...."
     * );
     *
     * $mollie = new ITPrismPaymentMollieIdeal($partnerId);
     * $mollie->createPayment($options);
     * </code>
     *
     * @param $options
     *
     * @throws Exception
     */
    public function createPayment($options)
    {
        $bankId      = JArrayHelper::getValue($options, "bank_id");
        $amount      = JArrayHelper::getValue($options, "amount");
        $description = JArrayHelper::getValue($options, "description");
        $returnUrl   = JArrayHelper::getValue($options, "return_url");
        $reportUrl   = JArrayHelper::getValue($options, "report_url");

        if (!$this->payment->createPayment($bankId, $amount, $description, $returnUrl, $reportUrl)) {
            throw new Exception($this->payment->getErrorMessage());
        }
    }

    /**
     * Return an URL to a bank.
     *
     * <code>
     * $partnerId = "PARTNER_123";
     *
     * $mollie = new ITPrismPaymentMollieIdeal($partnerId);
     * $bankUrl = $mollie->getBankURL();
     * </code>
     *
     * @return string
     */
    public function getBankURL()
    {
        return $this->payment->getBankURL();
    }

    /**
     * Return transaction ID.
     *
     * <code>
     * $partnerId = "PARTNER_123";
     *
     * $mollie = new ITPrismPaymentMollieIdeal($partnerId);
     * $txnId = $mollie->getTransactionId();
     * </code>
     *
     * @return string
     */
    public function getTransactionId()
    {
        return $this->payment->getTransactionId();
    }

    /**
     * Check for existing payment.
     *
     * <code>
     * $partnerId = "PARTNER_123";
     * $transaction = "TXN_ID";
     *
     * $mollie = new ITPrismPaymentMollieIdeal($partnerId);
     * if (!$mollie->checkPayment($transaction)) {
     * ...
     * }
     * </code>
     *
     * @param $transactionId
     *
     * @return bool
     */
    public function checkPayment($transactionId)
    {
        return $this->payment->checkPayment($transactionId);
    }

    /**
     * Return paid status.
     *
     * <code>
     * $partnerId = "PARTNER_123";
     *
     * $mollie = new ITPrismPaymentMollieIdeal($partnerId);
     * $status = $mollie->getPaidStatus();
     * </code>
     *
     * @return string
     */
    public function getPaidStatus()
    {
        return $this->payment->getPaidStatus();
    }

    /**
     * Return consumer information.
     *
     * <code>
     * $partnerId = "PARTNER_123";
     *
     * $mollie = new ITPrismPaymentMollieIdeal($partnerId);
     * $information = $mollie->getConsumerInfo();
     * </code>
     *
     * @return string
     */
    public function getConsumerInfo()
    {
        return $this->payment->getConsumerInfo();
    }

    /**
     * Return amount.
     *
     * <code>
     * $partnerId = "PARTNER_123";
     *
     * $mollie = new ITPrismPaymentMollieIdeal($partnerId);
     * $amount = $mollie->getAmount();
     * </code>
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->payment->getAmount();
    }

    /**
     * Return bank status.
     *
     * <code>
     * $partnerId = "PARTNER_123";
     *
     * $mollie = new ITPrismPaymentMollieIdeal($partnerId);
     * $bankStatus = $mollie->getBankStatus();
     * </code>
     *
     * @return string
     */
    public function getBankStatus()
    {
        return $this->payment->getBankStatus();
    }
}
