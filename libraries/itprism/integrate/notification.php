<?php
/**
 * @package      ITPrism
 * @subpackage   Integrations\Notifications
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2014 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined('JPATH_PLATFORM') or die;

/**
 * This class contains methods which creates notification object,
 * based on social extension name.
 *
 * @package      ITPrism
 * @subpackage   Integrations\Notifications
 */
abstract class ITPrismIntegrateNotification
{
    /**
     * Create an object based on social extension name.
     *
     * <code>
     * $name = "socialcommunity";
     *
     * $profile = ITPrismIntegrateNotification::factory($name);
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
        $loaded = jimport("itprism.integrate.notification." . $name);

        if (!$loaded) {
            throw new Exception('The integration for this social extension does not exists.');
        } else {
            // Build the name of the class, instantiate, and return
            $className = 'ITPrismIntegrateNotification' . ucfirst($name);

            return new $className();
        }
    }
}
