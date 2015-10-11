<?php
/**
 * @package      ITPrism
 * @subpackage   Integrations\Profiles
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2014 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined('JPATH_PLATFORM') or die;

jimport("socialcommunity.init");
JLoader::register("ITPrismIntegrateInterfaceProfiles", JPATH_LIBRARIES . '/itprism/integrate/interface/profiles.php');

/**
 * This class provides functionality used for integrating
 * extensions with the profile of SocialCommunity.
 *
 * @package      ITPrism
 * @subpackage   Integrations\Profiles
 */
class ITPrismIntegrateProfilesSocialCommunity implements ITPrismIntegrateInterfaceProfiles
{
    protected $profiles = array();

    protected $path;

    /**
     * Database driver
     *
     * @var JDatabaseDriver
     */
    protected $db;

    /**
     * Initialize the object
     *
     * <code>
     * $ids = array(1, 2, 3, 4);
     *
     * $profiles = new ITPrismIntegrateProfilesSocialCommunity($ids);
     * </code>
     *
     * @param  array $ids Users IDs
     */
    public function __construct($ids)
    {
        $this->db = JFactory::getDbo();

        if (!empty($ids)) {
            $this->load($ids);

            // Set path to pictures
            $params = JComponentHelper::getParams("com_socialcommunity");
            /** @var  $params Joomla\Registry\Registry */
            
            $path   = $params->get("images_directory", "/images/profiles");

            $this->setPath($path);
        }
    }

    /**
     * Load data about profiles from database.
     *
     * <code>
     * $ids = array(1, 2, 3, 4);
     *
     * $profiles = new ITPrismIntegrateProfilesSocialCommunity();
     * $profiles->load($ids);
     * </code>
     *
     * @param array $ids
     */
    public function load($ids)
    {
        if (!empty($ids)) {

            // Create a new query object.
            $query = $this->db->getQuery(true);
            $query
                ->select(
                    "a.id AS user_id, a.image_square AS avatar, a.image_small as avatar_small, " .
                    $query->concatenate(array("a.id", "a.alias"), ":") . " AS slug, " .
                    "b.name as location, b.country_code"
                )
                ->from($this->db->quoteName("#__itpsc_profiles", "a"))
                ->leftJoin($this->db->quoteName("#__itpsc_locations", "b") . " ON a.location_id = b.id")
                ->where("a.id IN ( " . implode(",", $ids) . ")");

            $this->db->setQuery($query);
            $results = $this->db->loadObjectList();

            if (!empty($results)) {
                foreach ($results as $result) {
                    $this->profiles[$result->user_id] = $result;
                }
            }
        }
    }

    /**
     * Get a link to user avatar.
     *
     * <code>
     * $ids = array(1, 2, 3, 4);
     * $userId = 1;
     *
     * $profiles = new ITPrismIntegrateProfilesSocialCommunity();
     * $profiles->load($ids);
     *
     * $avatar = $profiles->getAvatar($userId);
     * </code>
     * 
     * @param integer $userId
     * @param mixed   $size
     *
     * @return string
     */
    public function getAvatar($userId, $size = null)
    {
        if (!isset($this->profiles[$userId])) {
            $link = "";
        } else {

            if (empty($this->profiles[$userId]->avatar)) {
                $link = JUri::root() . "media/com_socialcommunity/images/no_profile_200x200.png";
            } else {
                $link = JUri::root() . ltrim($this->path . "/". $this->profiles[$userId]->avatar, "/");
            }
        }

        return $link;
    }

    /**
     * Get a link to user profile.
     *
     * <code>
     * $ids = array(1, 2, 3, 4);
     * $userId = 1;
     *
     * $profiles = new ITPrismIntegrateProfilesSocialCommunity();
     * $profiles->load($ids);
     *
     * $link = $profiles->getLink($userId);
     * </code>
     * 
     * @param integer $userId
     *
     * @return string
     */
    public function getLink($userId)
    {
        if (!isset($this->profiles[$userId])) {
            $link = "";
        } else {
            $link = SocialCommunityHelperRoute::getProfileRoute($this->profiles[$userId]->slug);
        }

        return $link;
    }

    /**
     * Return a location name where the user lives.
     *
     * <code>
     * $ids = array(1, 2, 3, 4);
     * $userId = 1;
     *
     * $profiles = new ITPrismIntegrateProfilesSocialCommunity();
     * $profiles->load($ids);
     *
     * $location = $profiles->getLocation($userId);
     * </code>
     *
     * @param int $userId
     *
     * @return string
     */
    public function getLocation($userId)
    {
        if (!isset($this->profiles[$userId])) {
            return null;
        } else {
            return $this->profiles[$userId]->location;
        }
    }

    /**
     * Return a country code of a country where the user lives.
     *
     * <code>
     * $ids = array(1, 2, 3, 4);
     * $userId = 1;
     *
     * $profiles = new ITPrismIntegrateProfilesSocialCommunity();
     * $profiles->load($ids);
     *
     * $countryCode = $profiles->getCountryCode($userId);
     * </code>
     *
     * @param int $userId
     * @return string
     */
    public function getCountryCode($userId)
    {
        if (!isset($this->profiles[$userId])) {
            return null;
        } else {
            return $this->profiles[$userId]->country_code;
        }
    }

    /**
     * Set the path to the images folder.
     *
     * <code>
     * $ids = array(1, 2, 3, 4);
     * $path = "/images/profiles;
     *
     * $profiles = new ITPrismIntegrateProfilesSocialCommunity();
     * $profiles->setPath($path);
     * </code>
     * 
     * @param string $path
     * @return self
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }
}
