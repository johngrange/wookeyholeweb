<?php
/**
 * @package      ITPrism
 * @subpackage   Integrations\Profile
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2014 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined('JPATH_PLATFORM') or die;

jimport("socialcommunity.init");
JLoader::register("ITPrismIntegrateInterfaceProfile", JPATH_LIBRARIES . '/itprism/integrate/interface/profile.php');

/**
 * This class provides functionality to
 * integrate extensions with the profile of Social Community.
 *
 * @package      ITPrism
 * @subpackage   Integrations\Profile
 */
class ITPrismIntegrateProfileSocialCommunity implements ITPrismIntegrateInterfaceProfile
{
    protected $user_id;
    protected $avatar;
    protected $location;
    protected $country_code;
    protected $slug;
    protected $path;

    /**
     * Database driver.
     *
     * @var JDatabaseDriver
     */
    protected $db;

    protected static $instances = array();

    /**
     * Initialize the object.
     *
     * <code>
     * $userId = 1;
     *
     * $profile = new ITPrismIntegrateProfileSocialCommunity($userId);
     * </code>
     * 
     * @param  int $id   User ID.
     */
    public function __construct($id)
    {
        $this->db = JFactory::getDbo();
        
        if (!empty($id)) {
            $this->load($id);

            // Set path to pictures
            $params = JComponentHelper::getParams("com_socialcommunity");
            /** @var  $params Joomla\Registry\Registry */

            $path   = $params->get("images_directory", "/images/profiles");

            $this->setPath($path);
        }
    }

    /**
     * Create an object
     *
     * <code>
     * $userId = 1;
     *
     * $profile = ITPrismIntegrateProfileSocialCommunity::getInstance($userId);
     * </code>
     * 
     * @param  int $id
     *
     * @return ITPrismIntegrateProfileSocialCommunity|null
     */
    public static function getInstance($id)
    {
        if (!isset(self::$instances[$id])) {
            $item                 = new ITPrismIntegrateProfileSocialCommunity($id);
            self::$instances[$id] = $item;
        }

        return self::$instances[$id];
    }

    /**
     * Load user data
     *
     * <code>
     * $userId = 1;
     *
     * $profile = new ITPrismIntegrateProfileSocialCommunity();
     * $profile->load($userId);
     * </code>
     * 
     * @param int $id User ID.
     */
    public function load($id)
    {
        $query = $this->db->getQuery(true);
        $query
            ->select(
                "a.id AS user_id, a.image_square AS avatar, a.image_small as avatar_small, " .
                $query->concatenate(array("a.id", "a.alias"), ":") . " AS slug, " .
                "b.name as location, b.country_code"
            )
            ->from($this->db->quoteName("#__itpsc_profiles", "a"))
            ->leftJoin($this->db->quoteName("#__itpsc_locations", "b") . " ON a.location_id = b.id")
            ->where("a.id = " . (int)$id);

        $this->db->setQuery($query);
        $result = $this->db->loadAssoc();

        if (!empty($result)) { // Set the values to the object properties.
            $this->bind($result);
        }
    }

    /**
     * Set values to object properties.
     *
     * <code>
     * $data = array(
     *     "name" => "...",
     *     "country" => "...",
     * ...
     * );
     *
     * $profile = new ITPrismIntegrateProfileSocialCommunity();
     * $profile->bind($data);
     * </code>
     *
     * @param array $data
     */
    public function bind($data)
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * Provide a link to social profile.
     *
     * <code>
     * $userId = 1;
     *
     * $profile = new ITPrismIntegrateProfileSocialCommunity($userId);
     * $link = $profile->getLink();
     * </code>
     *
     * @param bool $route Route or not the link.
     *
     * @return string
     */
    public function getLink($route = true)
    {
        $link = "";
        if (!empty($this->slug)) {
            $link = SocialCommunityHelperRoute::getProfileRoute($this->slug);
        }

        return $link;
    }

    /**
     * Provide a link to social avatar.
     *
     * <code>
     * $userId = 1;
     *
     * $profile = new ITPrismIntegrateProfileSocialCommunity($userId);
     * $avatar = $profile->getAvatar();
     * </code>
     * 
     * @return string
     */
    public function getAvatar()
    {
        if (!$this->avatar) {
            $link = JUri::root() . "media/com_socialcommunity/images/no_profile_200x200.png";
        } else {
            $link = JUri::root() . ltrim($this->path . "/".  $this->avatar, "/");
        }

        return $link;
    }

    /**
     * Return a location name where the user lives.
     *
     * <code>
     * $userId = 1;
     *
     * $profile = new ITPrismIntegrateProfileSocialCommunity($userId);
     * $location = $profile->getLocation();
     * </code>
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Return a country code of a country where the user lives.
     *
     * <code>
     * $userId = 1;
     *
     * $profile = new ITPrismIntegrateProfileSocialCommunity($userId);
     * $countryCode = $profile->getCountryCode();
     * </code>
     *
     * @return string
     */
    public function getCountryCode()
    {
        return $this->country_code;
    }

    /**
     * Set the path to the images folder.
     *
     * <code>
     * $ids = array(1, 2, 3, 4);
     * $path = "/images/profiles;
     *
     * $profile = new ITPrismIntegrateProfileSocialCommunity();
     * $profile->setPath($path);
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
