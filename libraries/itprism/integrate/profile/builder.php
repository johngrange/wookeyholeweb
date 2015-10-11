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
class ITPrismIntegrateProfileBuilder
{
    protected $config = array();
    protected $profile;

    /**
     * Initialize the object.
     *
     * <code>
     * $options = array(
     *    "social_platform" => "socialcommunity",
     *    "user_id" => 1
     * );
     *
     * $profileBuilder = new ITPrismIntegrateProfileBuilder($options);
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
     *    "user_id" => 1
     * );
     *
     * $profileBuilder = new ITPrismIntegrateProfileBuilder($options);
     * $profileBuilder->build();
     *
     * $profile = $profileBuilder->getProfile();
     * </code>
     */
    public function build()
    {
        $type   = JArrayHelper::getValue($this->config, "social_platform");
        $userId = JArrayHelper::getValue($this->config, "user_id");

        switch ($type) {

            case "socialcommunity":

                jimport("socialcommunity.init");

                /** @var  $params Joomla\Registry\Registry */
                $params = JComponentHelper::getParams("com_socialcommunity");
                $path   = $params->get("images_directory", "/images/profiles");

                jimport("itprism.integrate.profile.socialcommunity");
                $profile = ITPrismIntegrateProfileSocialCommunity::getInstance($userId);

                $profile->setPath($path);

                break;

            case "gravatar":

                jimport("itprism.integrate.profile.gravatar");
                $profile = ITPrismIntegrateProfileGravatar::getInstance($userId);

                break;

            case "kunena":

                jimport("itprism.integrate.profile.kunena");
                $profile = ITPrismIntegrateProfileKunena::getInstance($userId);

                break;

            case "jomsocial":

                // Register JomSocial Router
                if (!class_exists("CRoute")) {
                    JLoader::register("CRoute", JPATH_SITE."/components/com_community/libraries/core.php");
                }

                jimport("itprism.integrate.profile.jomsocial");
                $profile = ITPrismIntegrateProfileJomSocial::getInstance($userId);

                break;

            case "easysocial":

                jimport("itprism.integrate.profile.easysocial");
                $profile = ITPrismIntegrateProfileEasySocial::getInstance($userId);

                break;

            default:
                $profile = null;
                break;
        }

        $this->profile = $profile;
    }

    /**
     * Return a social profile object.
     *
     * <code>
     * $options = array(
     *    "social_platform" => "socialcommunity",
     *    "user_id" => 1
     * );
     *
     * $profileBuilder = new ITPrismIntegrateProfileBuilder($options);
     * $profileBuilder->build();
     *
     * $profile = $profileBuilder->getProfile();
     * </code>
     *
     * @return null|object
     */
    public function getProfile()
    {
        return $this->profile;
    }
}
