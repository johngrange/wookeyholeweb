<?php
/**
 * @package      ITPrism
 * @subpackage   Integrations\Profile
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2014 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined('JPATH_PLATFORM') or die;

JLoader::register("ITPrismIntegrateInterfaceProfile", JPATH_LIBRARIES . '/itprism/integrate/interface/profile.php');

/**
 * This class provides functionality to
 * integrate extensions with the profile of Kunena.
 *
 * @package      ITPrism
 * @subpackage   Integrations\Profile
 */
class ITPrismIntegrateProfileGravatar implements ITPrismIntegrateInterfaceProfile
{
    protected $user_id;
    protected $hash;
    protected $email;

    /**
     * Database driver.
     * 
     * @var JDatabaseDriver
     */
    protected $db;

    protected static $instances = array();

    /**
     * Initialize the object
     *
     * <code>
     * $userId = 1;
     *
     * $profile = new ITPrismIntegrateProfileGravatar($userId);
     * </code>
     * 
     * @param  int $id   User ID.
     */
    public function __construct($id)
    {
        $this->db = JFactory::getDbo();
        
        if (!empty($id)) {
            $this->load($id);
        }
    }

    /**
     * Create an object.
     *
     * <code>
     * $userId = 1;
     *
     * $profile = ITPrismIntegrateProfileGravatar::getInstance($userId);
     * </code>
     * 
     * @param  int $id
     *
     * @return null|ITPrismIntegrateProfileGravatar
     */
    public static function getInstance($id)
    {
        if (empty(self::$instances[$id])) {
            $item                 = new ITPrismIntegrateProfileGravatar($id);
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
     * $profile = new ITPrismIntegrateProfileGravatar();
     * $profile->load($userId);
     * </code>
     * 
     * @param int $id
     */
    public function load($id)
    {
        $query = $this->db->getQuery(true);
        $query
            ->select("a.id AS user_id, a.email")
            ->from($this->db->quoteName("#__users", "a"))
            ->where("a.id = " . (int)$id);

        $this->db->setQuery($query);
        $result = $this->db->loadAssoc();

        if (!empty($result)) { // Set values to variables
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
     * $profile = new ITPrismIntegrateProfileGravatar();
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

        $this->hash = md5($this->email);
    }

    /**
     * Provide a link to social profile.
     * This method integrates users with profiles
     * of some Joomla! social extensions.
     *
     * <code>
     * $userId = 1;
     *
     * $profile = new ITPrismIntegrateProfileGravatar($userId);
     * $link = $profile->getLink();
     * </code>
     *
     * @return string Return a link to the profile.
     */
    public function getLink()
    {
        return "http://www.gravatar.com/" . $this->hash;
    }

    /**
     * Provide a link to social avatar.
     *
     * <code>
     * $userId = 1;
     *
     * $profile = new ITPrismIntegrateProfileGravatar($userId);
     * $avatar = $profile->getAvatar();
     * </code>
     * 
     * @param int $size  Avatar size in pixels.
     *
     * @return string Return a link to the picture.
     */
    public function getAvatar($size = 50)
    {
        $size = (int)$size;

        $link = "http://www.gravatar.com/avatar/" . $this->hash;

        if (!empty($size)) {
            $link .= "?s=" . $size;
        }

        return $link;
    }

    /**
     * Return a location name where the user lives.
     *
     * <code>
     * $userId = 1;
     *
     * $profile = new ITPrismIntegrateProfileGravatar($userId);
     * $location = $profile->getLocation();
     * </code>
     *
     * @return string
     */
    public function getLocation()
    {
        return "";
    }

    /**
     * Return a country code of a country where the user lives.
     *
     * <code>
     * $userId = 1;
     *
     * $profile = new ITPrismIntegrateProfileGravatar($userId);
     * $countryCode = $profile->getCountryCode();
     * </code>
     *
     * @return string
     */
    public function getCountryCode()
    {
        return "";
    }
}
