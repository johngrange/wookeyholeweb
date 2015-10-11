<?php
/**
 * @package      ITPrism
 * @subpackage   Math
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2014 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

// no direct access
defined('JPATH_PLATFORM') or die;

/**
 * This class contains methods that are used for handling numbers.
 *
 * @package     ITPrism
 * @subpackage  Math
 */
class ITPrismMath
{
    protected $content;

    public function __construct($content = 0)
    {
        $this->content = $content;
    }

    /**
     * The method calculates percentage from two values.
     *
     * <code>
     * $percent = new ITPrismMath();
     * $percent->calculatePercentage(100, 1000);
     * echo $percent;
     * </code>
     *
     * @param int|float $value1
     * @param int|float  $value2
     * @param int  $decimalPoint
     */
    public function calculatePercentage($value1, $value2, $decimalPoint = 2)
    {
        if (($value1 == 0) or ($value2 == 0)) {
            $this->content = 0;
        } else {
            $value = ($value1 / $value2) * 100;
            $this->content = round($value, $decimalPoint);
        }
    }

    /**
     * The method calculates total value.
     *
     * <code>
     * $values = array(10, 10);
     *
     * $total = new ITPrismMath();
     * $total->calculateTotal($values);
     * echo $math;
     * </code>
     *
     * @param array $values
     * @param string  $action ( M = multiplication, S = calculate sum )
     * @param int  $decimalPoint
     */
    public function calculateTotal($values, $action = "M", $decimalPoint = 2)
    {
        $result = array_shift($values);

        switch ($action) {

            case "M":
                foreach ($values as $value) {
                    $result *=  $value;
                }
                break;

            case "S":
                foreach ($values as $value) {
                    $result +=  $value;
                }
                break;
        }

        $this->content = round($result, $decimalPoint);
    }

    public function __toString()
    {
        return (string)$this->content;
    }
}
