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

/**
 * This class contains methods that verify PayPal IPN response.
 *
 * @package     ITPrism
 * @subpackage  Payment\PayPal
 */
class ITPrismPayPalIpn
{
    const VERIFIED = "VERIFIED";
    const INVALID  = "INVALID";

    protected $url = "";
    protected $data = array();
    protected $status = null;

    protected $error = null;

    /**
     * Initialize the object.
     *
     * <code>
     * $url = "https://www.paypal.com/cgi-bin/webscr";
     * $data = array(
     *     "property1" => 1,
     *     "property2" => 2,
     * ...
     * );
     *
     * $ipn = new ITPrismPayPalIpn($url, $data);
     * </code>
     *
     * @param string $url
     * @param array $data
     */
    public function __construct($url, $data)
    {
        $this->url  = $url;
        $this->data = $data;
    }

    /**
     * Check for valid PayPal response.
     *
     * <code>
     * $url = "https://www.paypal.com/cgi-bin/webscr";
     * $data = array(
     *     "property1" => 1,
     *     "property2" => 2,
     * ...
     * );
     *
     * $ipn = new ITPrismPayPalIpn($url, $data);
     * $ipn->verify();
     *
     * if (!$ipn->isVerified()) {
     * ...
     * }
     * </code>
     *
     * @return bool
     */
    public function isVerified()
    {
        if ($this->status == self::VERIFIED) {
            return true;
        }

        return false;
    }

    /**
     * Validate PayPal response.
     *
     * <code>
     * $url = "https://www.paypal.com/cgi-bin/webscr";
     * $data = array(
     *     "property1" => 1,
     *     "property2" => 2,
     * ...
     * );
     *
     * $ipn = new ITPrismPayPalIpn($url, $data);
     * $ipn->verify();
     * </code>
     *
     * @param bool $loadCertificate Load or not certificate which will encrypt the requests.
     *
     * @return void
     */
    public function verify($loadCertificate = false)
    {
        if (!function_exists('curl_version')) {
            $this->error = JText::sprintf("LIB_ITPRISM_ERROR_CURL_LIBRARY_NOT_LOADED");
            return;
        }

        // Decode data
        foreach ($this->data as $key => $value) {
            $this->data[$key] = rawurldecode($value);
        }

        // Strip slashes if magic quotes are enabled
        if (function_exists('get_magic_quotes_gpc')) {

            if (1 == get_magic_quotes_gpc()) {
                foreach ($this->data as $key => $value) {
                    $this->data[$key] = stripslashes($value);
                }
            }

        }

        // Prepare request data
        $request = 'cmd=_notify-validate';
        foreach ($this->data as $key => $value) {
            $request .= "&" . $key . "=" . rawurlencode($value);
        }

        $ch = curl_init($this->url);
        if (false === $ch) {
            $this->error = JText::sprintf("LIB_ITPRISM_ERROR_PAYPAL_CONNECTION", $this->url) . "\n";
            return;
        }

        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));

        if ($loadCertificate) {
            curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/cacert.pem');
        }

        $result = curl_exec($ch);

        if (false === $result) {
            $this->error = JText::sprintf("LIB_ITPRISM_ERROR_PAYPAL_RECEIVING_DATA", $this->url) . "\n";
            $this->error .= curl_error($ch);
            return;
        }

        // If the payment is verified then set the status as verified.
        if ($result == "VERIFIED") {
            $this->status = self::VERIFIED;
        } else {
            $this->status = self::INVALID;
        }
    }

    /**
     * Return an error message.
     *
     * <code>
     * $url = "https://www.paypal.com/cgi-bin/webscr";
     * $data = array(
     *     "property1" => 1,
     *     "property2" => 2,
     * ...
     * );
     *
     * $ipn = new ITPrismPayPalIpn($url, $data);
     * $ipn->verify();
     *
     * if (!$ipn->isVerified()) {
     *     echo $ipn->getError();
     * }
     *
     * </code>
     *
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }
}
