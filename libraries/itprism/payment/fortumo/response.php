<?php
/**
 * @package      ITPrism
 * @subpackage   Payment\Fortumo
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2014 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

// no direct access
defined('JPATH_PLATFORM') or die;

/**
 * This class provides functionality for making transactions by Fortumo.
 *
 * @package      ITPrism
 * @subpackage   Payment\Fortumo
 */
class ITPrismPaymentFortumoResponse
{
    protected $message;
    protected $sender;
    protected $country;
    protected $price;
    protected $currency;
    protected $service_id;
    protected $message_id;
    protected $keyword;
    protected $shortcode;
    protected $operator;
    protected $billing_type;
    protected $status;
    protected $sig;

    protected $test;

    /**
     * Initialize the object.
     *
     * <code>
     * $data = array(
     *     "sender" => "...",
     *     "country" => "...",
     * ...
     * );
     *
     * $response = new ITPrismPaymentFortumoResponse($data);
     * </code>
     *
     * @param array $data
     */
    public function __construct($data = array())
    {
        if (!empty($data)) {
            $this->bind($data);
        }
    }

    /**
     * Set properties vaules to the object.
     *
     * <code>
     * $data = array(
     *     "sender" => "...",
     *     "country" => "...",
     * ...
     * );
     *
     * $response = new ITPrismPaymentFortumoResponse();
     * $response->bind($data);
     * </code>
     *
     * @param array $data
     * @param array $ignored
     */
    public function bind($data, $ignored = array())
    {
        foreach ($data as $key => $value) {
            if (!in_array($key, $ignored)) {
                $this->$key = $value;
            }
        }
    }

    /**
     * Return a status that comes from Fortumo response.
     *
     * <code>
     * $data = array(
     *     "sender" => "...",
     *     "country" => "...",
     * ...
     * );
     *
     * $response = new ITPrismPaymentFortumoResponse($data);
     * $status = $response->getStatus();
     * </code>
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Return a message that comes from Fortumo response.
     *
     * <code>
     * $data = array(
     *     "sender" => "...",
     *     "country" => "...",
     * ...
     * );
     *
     * $response = new ITPrismPaymentFortumoResponse($data);
     * $message = $response->getMessage();
     * </code>
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Return the name of the sender that comes from Fortumo response.
     *
     * <code>
     * $data = array(
     *     "sender" => "...",
     *     "country" => "...",
     * ...
     * );
     *
     * $response = new ITPrismPaymentFortumoResponse($data);
     * $sender = $response->getSender();
     * </code>
     *
     * @return string
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * Return a country name that comes from Fortumo response.
     *
     * <code>
     * $data = array(
     *     "sender" => "...",
     *     "country" => "...",
     * ...
     * );
     *
     * $response = new ITPrismPaymentFortumoResponse($data);
     * $country = $response->getCountry();
     * </code>
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Return an amount value that comes from Fortumo response.
     *
     * <code>
     * $data = array(
     *     "sender" => "...",
     *     "country" => "...",
     * ...
     * );
     *
     * $response = new ITPrismPaymentFortumoResponse($data);
     * $amount = $response->getPrice();
     * </code>
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Return a currency that comes from Fortumo response.
     *
     * <code>
     * $data = array(
     *     "sender" => "...",
     *     "country" => "...",
     * ...
     * );
     *
     * $response = new ITPrismPaymentFortumoResponse($data);
     * $currency = $response->getCurrency();
     * </code>
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Return a service ID that comes from Fortumo response.
     *
     * <code>
     * $data = array(
     *     "sender" => "...",
     *     "country" => "...",
     * ...
     * );
     *
     * $response = new ITPrismPaymentFortumoResponse($data);
     * $serviceId = $response->getServiceId();
     * </code>
     *
     * @return mixed
     */
    public function getServiceId()
    {
        return $this->service_id;
    }

    /**
     * Return a message ID that comes from Fortumo response.
     *
     * <code>
     * $data = array(
     *     "sender" => "...",
     *     "country" => "...",
     * ...
     * );
     *
     * $response = new ITPrismPaymentFortumoResponse($data);
     * $messageId = $response->getMessageId();
     * </code>
     *
     * @return mixed
     */
    public function getMessageId()
    {
        return $this->message_id;
    }

    /**
     * Return a keyword that comes from Fortumo response.
     *
     * <code>
     * $data = array(
     *     "sender" => "...",
     *     "country" => "...",
     * ...
     * );
     *
     * $response = new ITPrismPaymentFortumoResponse($data);
     * $keyword = $response->getKeyword();
     * </code>
     *
     * @return mixed
     */
    public function getKeyword()
    {
        return $this->keyword;
    }

    /**
     * Return a short code that comes from Fortumo response.
     *
     * <code>
     * $data = array(
     *     "sender" => "...",
     *     "country" => "...",
     * ...
     * );
     *
     * $response = new ITPrismPaymentFortumoResponse($data);
     * $shortCode = $response->getShortCode();
     * </code>
     *
     * @return string
     */
    public function getShortCode()
    {
        return $this->shortcode;
    }

    /**
     * Return a name of the operator that comes from Fortumo response.
     *
     * <code>
     * $data = array(
     *     "sender" => "...",
     *     "country" => "...",
     * ...
     * );
     *
     * $response = new ITPrismPaymentFortumoResponse($data);
     * $operator = $response->getOperator();
     * </code>
     *
     * @return string
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * Return a billing type that comes from Fortumo response.
     *
     * <code>
     * $data = array(
     *     "sender" => "...",
     *     "country" => "...",
     * ...
     * );
     *
     * $response = new ITPrismPaymentFortumoResponse($data);
     * $billingType = $response->getBillingType();
     * </code>
     *
     * @return string
     */
    public function getBillingType()
    {
        return $this->billing_type;
    }

    /**
     * Return the signature used to sign the response.
     *
     * <code>
     * $data = array(
     *     "sender" => "...",
     *     "country" => "...",
     * ...
     * );
     *
     * $response = new ITPrismPaymentFortumoResponse($data);
     * $sig = $response->getSig();
     * </code>
     *
     * @return string
     */
    public function getSig()
    {
        return $this->sig;
    }

    /**
     * Disable test mode.
     *
     * <code>
     * $response = new ITPrismPaymentFortumoResponse();
     * $response->disableTest();
     * </code>
     *
     * @return self
     */
    public function disableTest()
    {
        $this->test = false;

        return $this;
    }

    /**
     * Enable test mode.
     *
     * <code>
     * $response = new ITPrismPaymentFortumoResponse();
     * $response->disableTest();
     * </code>
     *
     * @return self
     */
    public function enableTest()
    {
        $this->test = true;

        return $this;
    }

    /**
     * Check for enabled test mode.
     *
     * <code>
     * $response = new ITPrismPaymentFortumoResponse();
     *
     * if (!$response->isTestMode()) {
     * ...
     * }
     * </code>
     *
     * @return self
     */
    public function isTestMode()
    {
        return (bool)$this->test;
    }
}
