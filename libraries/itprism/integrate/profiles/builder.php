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
class ITPrismIntegrateProfilesBuilder
{
    protected $config = array();
    protected $profiles;

    /**
     * Initialize the object.
     *
     * <code>
     * $options = array(
     *    "social_platform" => "socialcommunity",
     *    "users_ids" => array(1,2,3)
     * );
     *
     * $profilesBuilder = new ITPrismIntegrateProfilesBuilder($options);
     * </code>
     *
     * @param  array  $config Options used in the process of building profile object.
     *
     */
    public function __construct($config = array())
    {
        $this->config = $config;
    }

    /**
     * Build a social profile object.
     *
     * <code>
     * $options = array(
     *    "social_platform" => "socialcommunity",
     *    "users_ids" => array(1,2,3)
     * );
     *
     * $profilesBuilder = new ITPrismIntegrateProfilesBuilder($options);
     * $profilesBuilder->build();
     *
     * $profiles = $profilesBuilder->getProfiles();
     * </code>
     */
    public function build()
    {
        $type     = JArrayHelper::getValue($this->config, "social_platform");
        $usersIds = JArrayHelper::getValue($this->config, "users_ids");

        switch ($type) {

            case "socialcommunity":

                jimport("socialcommunity.init");

                /** @var  $params Joomla\Registry\Registry */
                $params = JComponentHelper::getParams("com_socialcommunity");
                $path   = $params->get("images_directory", "/images/profiles");

                jimport("itprism.integrate.profiles.socialcommunity");
                $profiles = new ITPrismIntegrateProfilesSocialCommunity($usersIds);
                $profiles->setPath($path);

                break;

            case "gravatar":

                jimport("itprism.integrate.profiles.gravatar");
                $profiles = new ITPrismIntegrateProfilesGravatar($usersIds);

                break;

            case "kunena":

                jimport("itprism.integrate.profiles.kunena");
                $profiles = new ITPrismIntegrateProfilesKunena($usersIds);

                break;

            case "jomsocial":

                // Register JomSocial Router
                if (!class_exists("CRoute")) {
                    JLoader::register("CRoute", JPATH_SITE."/components/com_community/libraries/core.php");
                }

                jimport("itprism.integrate.profiles.jomsocial");
                $profiles = new ITPrismIntegrateProfilesJomSocial($usersIds);

                break;

            case "easysocial":

                jimport("itprism.integrate.profiles.easysocial");
                $profiles = new ITPrismIntegrateProfilesEasySocial($usersIds);

                break;

            default:
                $profiles = null;
                break;
        }

        $this->profiles = $profiles;
    }

    /**
     * Return a social profiles object.
     *
     * <code>
     * $options = array(
     *    "social_platform" => "socialcommunity",
     *    "users_ids" => array(1,2,3)
     * );
     *
     * $profilesBuilder = new ITPrismIntegrateProfilesBuilder($options);
     * $profilesBuilder->build();
     *
     * $profiles = $profilesBuilder->getProfiles();
     * </code>
     *
     * @return null|object
     */
    public function getProfiles()
    {
        return $this->profiles;
    }
}
