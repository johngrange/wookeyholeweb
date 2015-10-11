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
class ITPrismIntegrateProfileKunena implements ITPrismIntegrateInterfaceProfile
{
    protected $user_id;
    protected $avatar;

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
     * $profile = new ITPrismIntegrateProfileKunena($userId);
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
     * $profile = ITPrismIntegrateProfileKunena::getInstance($userId);
     * </code>
     *
     * @param  int $id
     *
     * @return null|ITPrismIntegrateProfileKunena
     */
    public static function getInstance($id)
    {
        if (empty(self::$instances[$id])) {
            $item                 = new ITPrismIntegrateProfileKunena($id);
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
     * $profile = new ITPrismIntegrateProfileKunena();
     * $profile->load($userId);
     * </code>
     *
     * @param int $id
     */
    public function load($id)
    {
        $query = $this->db->getQuery(true);
        $query
            ->select("a.userid AS user_id, a.avatar")
            ->from($this->db->quoteName("#__kunena_users", "a"))
            ->where("a.userid = " . (int)$id);

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
     * $profile = new ITPrismIntegrateProfileKunena();
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
     * $profile = new ITPrismIntegrateProfileKunena($userId);
     * $link = $profile->getLink();
     * </code>
     *
     * @return string Return a link to the profile.
     */
    public function getLink()
    {
        return KunenaRoute::_("index.php?option=com_kunena&view=profile&userid=" . $this->user_id, false);
    }

    /**
     * Provide a link to social avatar.
     *
     * <code>
     * $userId = 1;
     *
     * $profile = new ITPrismIntegrateProfileKunena($userId);
     * $avatar = $profile->getAvatar();
     * </code>
     *
     * @param int $size One of Kunena avatar sizes.
     * 
     * @return string Return a link to the picture.
     */
    public function getAvatar($size = 72)
    {
        if (!$this->avatar) {
            $link = JUri::root() . "media/kunena/avatars/nophoto.jpg";
        } else {
            $link = JUri::root() . "media/kunena/avatars/resized/size" . (int)$size . "/" . $this->avatar;
        }

        return $link;
    }

    /**
     * Return a location name where the user lives.
     *
     * <code>
     * $userId = 1;
     *
     * $profile = new ITPrismIntegrateProfileKunena($userId);
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
     * $profile = new ITPrismIntegrateProfileKunena($userId);
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
