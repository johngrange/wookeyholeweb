<?php
/**
 * @package      ITPrism
 * @subpackage   Transifex
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2014 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

// no direct access
defined('JPATH_PLATFORM') or die;

/**
 * This class provides methods for managing Transifex Response.
 *
 * @package      ITPrism
 * @subpackage   Transifex
 */
class ITPrismTransifexResponse
{
    /**
     * Set values to the parameters of the object.
     *
     * <code>
     * $data = array(
     *     "property_name" => 1,
     *     "property_name2" => 2
     * );
     *
     * $response = new ITPrismTransifexResponse();
     * $response->bind($data);
     *
     * </code>
     *
     * @param array $data
     * @param array $excluded
     */
    public function bind(array $data, $excluded = array())
    {
        foreach ($data as $key => $value) {
            if (!in_array($key, $excluded)) {
                $this->$key = $value;
            }
        }

    }

    /**
     * Get a value of a parameter.
     *
     * <code>
     * $response = new ITPrismTransifexResponse();
     *
     * $response->get("property_name");
     * </code>
     *
     * @param string $name
     * @param mixed $default
     *
     * @return mixed
     */
    public function get($name, $default = null)
    {
        return (!isset($this->$name)) ? $default : $this->$name;
    }

    /**
     * Set a value to a parameter.
     *
     * <code>
     * $response = new ITPrismTransifexResponse();
     *
     * $response->set("property_name", 123);
     * </code>
     *
     * @param string $name
     * @param mixed $value
     *
     * @return self
     */
    public function set($name, $value)
    {
        $this->$name = $value;

        return $this;
    }

    /**
     * Get a value of a parameter.
     *
     * <code>
     * $response = new ITPrismTransifexResponse();
     *
     * echo $response->property_name;
     * </code>
     *
     * @param string $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        return (!isset($this->$name)) ? null : $this->$name;
    }
}
