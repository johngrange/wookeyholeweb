<?php
/**
 * @package      ITPrism
 * @subpackage   Validators
 * @copyright    Copyright (C) 2014 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

// no direct access
defined('JPATH_PLATFORM') or die;

JLoader::register("ITPrismValidatorInterface", JPATH_LIBRARIES . "/itprism/validator/interface.php");

/**
 * This class validates IP addresses.
 *
 * @package      ITPrism
 * @subpackage   Validators
 */
class ITPrismValidatorIP implements ITPrismValidatorInterface
{
    /**
     * IP address.
     *
     * @var string
     */
    protected $ip;

    /**
     * Allowed IP addresses.
     *
     * @var array
     */
    protected $allowed = array();

    /**
     * Initialize the object.
     *
     * <code>
     * $ip = "127.0.0.1";
     *
     * $validator = new ITPrismValidatorIP($ip);
     * </code>
     *
     * @param string $ip
     */
    public function __construct($ip)
    {
        $this->ip = $ip;
    }

    /**
     * Validate an IP address.
     *
     * <code>
     * $ip = "127.0.0.1";
     *
     * $validator = new ITPrismValidatorIP($ip);
     *
     * if (!$validator->isValid()) {
     * ...
     * }
     * </code>
     *
     * @return bool
     */
    public function isValid()
    {
        $ip = long2ip(ip2long($this->ip));

        if (!$ip) {
            return false;
        }

        // Validate by allowed IP addresses.
        if (!empty($this->allowed) and (!in_array($ip, $this->allowed))) {
            return false;
        }

        return true;
    }

    /**
     * Set a list with IP addresses, that will be used in the process of validation.
     *
     * <code>
     * $ip = "127.0.0.1";
     * $allowed = array("127.0.0.1", "169.0.0.1");
     *
     * $validator = new ITPrismValidatorIP($ip);
     * $validator->setAllowed($allowed);
     *
     * if (!$validator->isValid()) {
     * ...
     * }
     * </code>
     *
     * @param array $allowed
     */
    public function setAllowed(array $allowed)
    {
        $this->allowed = $allowed;
    }
}
