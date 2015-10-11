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
 * This class contains methods which creates social profile object,
 * based on social extension name.
 *
 * @package      ITPrism
 * @subpackage   Integrations\Profiles
 */
abstract class ITPrismIntegrateProfile
{
    /**
     * Create an object based on social extension name.
     *
     * <code>
     * $name = "socialcommunity";
     *
     * $profile = ITPrismIntegrateProfile::factory($name);
     * </code>
     *
     * @param  string $name A name of a social platform or service.
     * @param  integer  $id User ID
     *
     * @return object
     * @throws Exception
     */
    public static function factory($name, $id = 0)
    {
        $name   = JString::strtolower($name);
        $loaded = jimport("itprism.integrate.profile." . $name);

        if (!$loaded) {
            throw new Exception('The integration for this social extension does not exists.');
        } else {
            // Build the name of the class, instantiate, and return
            $className = 'ITPrismIntegrateProfile' . ucfirst($name);

            return new $className($id);
        }
    }
}
