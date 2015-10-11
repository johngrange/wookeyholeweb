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
 * This class validates date.
 *
 * @package      ITPrism
 * @subpackage   Validators
 */
class ITPrismValidatorDate implements ITPrismValidatorInterface
{
    /**
     * A date string.
     *
     * @var string
     */
    protected $date;

    /**
     * Initialize the object.
     *
     * <code>
     * $date = "01-01-2020";
     *
     * $validator = new ITPrismValidatorDate($date);
     * </code>
     *
     * @param string $date
     */
    public function __construct($date)
    {
        $this->date = $date;
    }

    /**
     * Validate a date.
     *
     * <code>
     * $date = "01-01-2020";
     *
     * $validator = new ITPrismValidatorDate($date);
     *
     * if (!$validator->isValid()) {
     * ...
     * }
     *
     * </code>
     *
     * @return bool
     */
    public function isValid()
    {
        $string = JString::trim($this->date);

        try {
            $date = new DateTime($string);
        } catch (Exception $e) {
            return false;
        }

        $month = $date->format('m');
        $day   = $date->format('d');
        $year  = $date->format('Y');

        if (checkdate($month, $day, $year)) {
            return true;
        } else {
            return false;
        }
    }
}
