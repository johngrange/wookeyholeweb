<?php
/**
 * @package      ITPrism
 * @subpackage   Integrations\Profiles
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2014 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined('JPATH_PLATFORM') or die;

/**
 * This class contains methods which creates social profiles object,
 * based on social extension name.
 *
 * @package      ITPrism
 * @subpackage   Integrations\Profiles
 */
abstract class ITPrismIntegrateProfiles
{
    /**
     * Create an object based on social extension name.
     *
     * <code>
     * $name = "socialcommunity";
     *
     * $profiles = ITPrismIntegrateProfiles::factory($name);
     * </code>
     *
     * @param  string $name This is the name, on which is based the results.
     * @param  array  $keys These are the keys, which will be used for loading users data.
     *
     * @return object
     * @throws Exception
     */
    public static function factory($name, $keys = array())
    {
        $name   = JString::strtolower($name);
        $loaded = jimport("itprism.integrate.profiles." . $name);

        if (!$loaded) {
            throw new Exception('The integration for this social extension does not exists.');
        } else {
            // Build the name of the class, instantiate, and return
            $className = 'ITPrismIntegrateProfiles' . ucfirst($name);

            return new $className($keys);
        }
    }
}
