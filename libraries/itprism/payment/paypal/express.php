<?php
/**
 * @package      ITPrism
 * @subpackage   Payment\PayPal
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2014 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

// no direct access
defined('JPATH_PLATFORM') or die;

jimport("joomla.http.http");
jimport("joomla.http.transport.curl");

/**
 * This class contains methods that manage PayPal Express.
 *
 * @package     ITPrism
 * @subpackage  Payment\PayPal
 */
class ITPrismPayPalExpress
{
    protected $url;

    /**
     * @var Joomla\Registry\Registry
     */
    protected $options;

    /**
     * @var JHttp
     */
    protected $transport;

    protected $error;
    protected $errorCode;

    /**
     * Initialize the object.
     *
     * <code>
     * $url = "https://api-3t.paypal.com/nvp";
     *
     * $paypal = new ITPrismPayPalExpress($url);
     * </code>
     *
     * @param string $url
     * @param JRegistry $options
     */
    public function __construct($url, JRegistry $options)
    {
        $this->url     = $url;
        $this->options = $options;
    }

    /**
     * Set an option value.
     *
     * <code>
     * $url = "https://api-3t.paypal.com/nvp";
     *
     * $paypal = new ITPrismPayPalExpress($url);
     *
     * $paypal->setOption("credentials.username", "itprism");
     * </code>
     *
     * @param string $key
     * @param mixed $value
     *
     * @return self
     */
    public function setOption($key, $value)
    {
        $this->options->set($key, $value);

        return $this;
    }

    public function getOption($key, $default = null)
    {
        return $this->options->get($key, $default);
    }

    /**
     * Set an option value.
     *
     * <code>
     * $url  = "https://api-3t.paypal.com/nvp";
     * $http = new JHttp();
     *
     * $paypal = new ITPrismPayPalExpress($url);
     *
     * $paypal->setTransport($http);
     * </code>
     *
     * @param JHttp $transport
     *
     * @return self
     */
    public function setTransport($transport)
    {
        $this->transport = $transport;

        return $this;
    }

    /**
     * Return information about error.
     *
     * <code>
     * $url  = "https://api-3t.paypal.com/nvp";
     *
     * $paypal = new ITPrismPayPalExpress($url);
     *
     * $error = $paypal->getError();
     * </code>
     *
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Return error code.
     *
     * <code>
     * $url  = "https://api-3t.paypal.com/nvp";
     *
     * $paypal = new ITPrismPayPalExpress($url);
     *
     * $errorCode = $paypal->getErrorCode();
     * </code>
     *
     * @return string
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * Send data to PayPal servers and handling the PayPal method "setExpressCheckout".
     *
     * <code>
     * $url  = "https://api-3t.paypal.com/nvp";
     *
     * $options = new JRegistry();
     * $options->set("credentials.username", "itprism");
     * ....
     *
     * $paypal = new ITPrismPayPalExpress($url, $options);
     * $paypal->setExpressCheckout();
     * </code>
     *
     * @return string
     *
     * @throws RuntimeException
     */
    public function setExpressCheckout()
    {
        $data = array(
            "METHOD"    => "SetExpressCheckout",
            "USER"      => $this->options->get("credentials.username"),
            "PWD"       => $this->options->get("credentials.password"),
            "SIGNATURE" => $this->options->get("credentials.signature"),
            "VERSION"   => $this->options->get("api.version"),
            "RETURNURL" => $this->options->get("urls.return"),
            "CANCELURL" => $this->options->get("urls.cancel"),
        );

        $data["REQCONFIRMSHIPPING"] = $this->options->get("shipping.require_confirm", 0);
        $data["NOSHIPPING"]         = $this->options->get("shipping.no_shipping", 1);
        $data["ALLOWNOTE"]          = $this->options->get("shipping.allow_note", 0);

        $data["ADDROVERRIDE"] = $this->options->get("shipping.address_override", 0);
        $data["LOCALECODE"]   = $this->options->get("locale.code", "en_US");

        if ($this->options->get("style.logo_image")) {
            $data["LOGOIMG"] = $this->options->get("style.logo_image");
        }

        if ($this->options->get("style.brand_name")) {
            $data["BRANDNAME"] = $this->options->get("style.brand_name", JText::_("LIB_ITPRISM_MERCHANT"));
        }

        $data["SOLUTIONTYPE"] = $this->options->get("payment.solution_type", "Sole");
        $data["CHANNELTYPE"]  = $this->options->get("payment.channel_type", "Merchant");

        $data["PAYMENTREQUEST_0_PAYMENTACTION"] = $this->options->get("payment.action", "Sale");

        $data["PAYMENTREQUEST_0_AMT"]          = $this->options->get("payment.amount", 0);
        $data["PAYMENTREQUEST_0_CURRENCYCODE"] = $this->options->get("payment.currency", "USD");

        if ($this->options->get("payment.description")) {
            $data["PAYMENTREQUEST_0_DESC"] = $this->options->get("payment.description");
        }

        if ($this->options->get("payment.custom")) {
            $data["PAYMENTREQUEST_0_CUSTOM"] = $this->options->get("payment.custom");
        }

        $response = $this->transport->post($this->url, $data);

        $body = $this->parseResponse($response);

        if (strcmp("Success", $body["ACK"]) != 0) {
            $this->error     = JArrayHelper::getValue($body, "L_SHORTMESSAGE0") . ":" . JArrayHelper::getValue($body, "L_LONGMESSAGE0");
            $this->errorCode = JArrayHelper::getValue($body, "L_ERRORCODE0");
            throw new RuntimeException($this->error, $this->errorCode);
        }

        return $body;
    }

    /**
     * Send data to PayPal servers and handling the PayPal method "doExpressCheckoutPayment".
     *
     * <code>
     * $url  = "https://api-3t.paypal.com/nvp";
     *
     * $options = new JRegistry();
     * $options->set("credentials.username", "itprism");
     * ....
     *
     * $paypal = new ITPrismPayPalExpress($url, $options);
     * $paypal->doExpressCheckoutPayment();
     * </code>
     *
     * @return string
     *
     * @throws RuntimeException
     */
    public function doExpressCheckoutPayment()
    {
        $data = array(
            "METHOD"    => "DoExpressCheckoutPayment",
            "USER"      => $this->options->get("credentials.username"),
            "PWD"       => $this->options->get("credentials.password"),
            "SIGNATURE" => $this->options->get("credentials.signature"),
            "VERSION"   => $this->options->get("api.version"),
        );

        $data["TOKEN"]   = $this->options->get("authorization.token");
        $data["PAYERID"] = $this->options->get("authorization.payer_id");

        $data["PAYMENTREQUEST_0_PAYMENTACTION"] = $this->options->get("payment.action", "Sale");

        $data["PAYMENTREQUEST_0_AMT"]          = $this->options->get("payment.amount", 0);
        $data["PAYMENTREQUEST_0_CURRENCYCODE"] = $this->options->get("payment.currency", "USD");

        if ($this->options->get("urls.notify")) {
            $data["PAYMENTREQUEST_0_NOTIFYURL"] = $this->options->get("urls.notify");
        }

        $response = $this->transport->post($this->url, $data);

        $body = $this->parseResponse($response);

        if (strcmp("Success", $body["ACK"]) != 0) {
            $this->error     = JArrayHelper::getValue($body, "L_SHORTMESSAGE0") . ":" . JArrayHelper::getValue($body, "L_LONGMESSAGE0");
            $this->errorCode = JArrayHelper::getValue($body, "L_ERRORCODE0");
            throw new RuntimeException($this->error, $this->errorCode);
        }

        return $body;
    }

    /**
     * Send data to PayPal servers and handling the PayPal method "doCapture".
     *
     * <code>
     * $url  = "https://api-3t.paypal.com/nvp";
     *
     * $options = new JRegistry();
     * $options->set("credentials.username", "itprism");
     * ....
     *
     * $paypal = new ITPrismPayPalExpress($url, $options);
     * $paypal->doCapture();
     * </code>
     *
     * @return string
     *
     * @throws RuntimeException
     */
    public function doCapture()
    {
        $data = array(
            "METHOD"    => "DoCapture",
            "USER"      => $this->options->get("credentials.username"),
            "PWD"       => $this->options->get("credentials.password"),
            "SIGNATURE" => $this->options->get("credentials.signature"),
            "VERSION"   => $this->options->get("api.version"),
        );

        $data["AUTHORIZATIONID"] = $this->options->get("payment.authorization_id");

        $data["AMT"]          = $this->options->get("payment.amount", 0);
        $data["CURRENCYCODE"] = $this->options->get("payment.currency", "USD");
        $data["COMPLETETYPE"] = $this->options->get("payment.complete_type", "Complete");

        $response = $this->transport->post($this->url, $data);

        $body = $this->parseResponse($response);

        if (strcmp("Success", $body["ACK"]) != 0) {
            $this->error     = JArrayHelper::getValue($body, "L_SHORTMESSAGE0") . ":" . JArrayHelper::getValue($body, "L_LONGMESSAGE0");
            $this->errorCode = JArrayHelper::getValue($body, "L_ERRORCODE0");
            throw new RuntimeException($this->error, $this->errorCode);
        }

        return $body;
    }

    /**
     * Send data to PayPal servers and handling the PayPal method "doVoid".
     *
     * <code>
     * $url  = "https://api-3t.paypal.com/nvp";
     *
     * $options = new JRegistry();
     * $options->set("credentials.username", "itprism");
     * ....
     *
     * $paypal = new ITPrismPayPalExpress($url, $options);
     * $paypal->doVoid();
     * </code>
     *
     * @return string
     *
     * @throws RuntimeException
     */
    public function doVoid()
    {
        $data = array(
            "METHOD"    => "DoVoid",
            "USER"      => $this->options->get("credentials.username"),
            "PWD"       => $this->options->get("credentials.password"),
            "SIGNATURE" => $this->options->get("credentials.signature"),
            "VERSION"   => $this->options->get("api.version"),
        );

        $data["AUTHORIZATIONID"] = $this->options->get("payment.authorization_id");

        $response = $this->transport->post($this->url, $data);

        $body = $this->parseResponse($response);

        if (strcmp("Success", $body["ACK"]) != 0) {
            $this->error     = JArrayHelper::getValue($body, "L_SHORTMESSAGE0") . ":" . JArrayHelper::getValue($body, "L_LONGMESSAGE0");
            $this->errorCode = JArrayHelper::getValue($body, "L_ERRORCODE0");
            throw new RuntimeException($this->error, $this->errorCode);
        }

        return $body;
    }

    /**
     * Return the response data that comes from PayPal.
     *
     * @param object $response
     *
     * @return array
     */
    protected function parseResponse($response)
    {
        $body = array();

        $body_ = explode("&", $response->body);
        foreach ($body_ as $value) {
            $value_ = rawurldecode($value);
            $values = explode("=", $value_);

            $body[$values[0]] = rawurldecode($values[1]);
        }

        return $body;
    }
}
