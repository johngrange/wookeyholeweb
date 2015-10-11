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

JLoader::register("ITPrismValidatorInterface", JPATH_LIBRARIES . "/itprism/validator/interface.php");

/**
 * This class validates Fortumo response data.
 *
 * @package      ITPrism
 * @subpackage   Payment\Fortumo
 */
class ITPrismPaymentFortumoValidator implements ITPrismValidatorInterface
{
    protected $data = array();

    /**
     * This is the secret key.
     *
     * @var string
     */
    protected $secret = "";

    /**
     * Initialize the object.
     *
     * <code>
     * $secret = "SECRET_KEY";
     * $data = array(
     *     "sig" => "...",
     * );
     *
     * $validator = new ITPrismPaymentFortumoValidator($data, $secret);
     * </code>
     *
     * @param array $data Data that comes from Fortumo servers.
     * @param string $secret
     */
    public function __construct($data, $secret)
    {
        $this->data   = $data;
        $this->secret = $secret;
    }

    /**
     * Validate a response that comes from Fortumo servers.
     *
     * <code>
     * $secret = "SECRET_KEY";
     * $data = array(
     *     "sig" => "...",
     * );
     *
     * $validator = new ITPrismPaymentFortumoValidator($data, $secret);
     *
     * if (!$validator->isValid()) {
     * ....
     * }
     * </code>
     *
     * @return bool
     */
    public function isValid()
    {
        if (!isset($this->data['sig'])) {
            return false;
        }

        ksort($this->data);

        $str = '';
        foreach ($this->data as $k => $v) {
            if ($k != 'sig') {
                $str .= "$k=$v";
            }
        }
        $str .= $this->secret;
        $signature = md5($str);

        if (strcmp($this->data['sig'], $signature) != 0) {
            return false;
        }

        return true;
    }
}
