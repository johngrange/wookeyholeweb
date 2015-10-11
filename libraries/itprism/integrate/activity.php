<?php
/**
 * @package      ITPrism
 * @subpackage   Integrations\Activities
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2014 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined('JPATH_PLATFORM') or die;

/**
 * This class contains methods which creates activity object,
 * based on social extension name.
 *
 * @package      ITPrism
 * @subpackage   Integrations\Activities
 */
abstract class ITPrismIntegrateActivity
{
    /**
     * Create an object based on social extension name.
     *
     * <code>
     * $name = "socialcommunity";
     *
     * $profile = ITPrismIntegrateActivity::factory($name);
     * </code>
     *
     * @param  string $name This is the name, on which is based the results.
     *
     * @return object
     * @throws Exception
     */
    public static function factory($name)
    {
        $name   = JString::strtolower($name);
        $loaded = jimport("itprism.integrate.activity." . $name);

        if (!$loaded) {
            throw new Exception('The integration for this social extension does not exists.');
        } else {
            // Build the name of the class, instantiate, and return
            $className = 'ITPrismIntegrateActivity' . ucfirst($name);

            return new $className();
        }
    }
}
