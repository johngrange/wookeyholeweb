<?php
/**
 * @package      ITPrism
 * @subpackage   Integrations\Profile
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2014 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined('JPATH_PLATFORM') or die;

jimport("itprism.integrate.interface.profile");

JLoader::register("ITPrismIntegrateInterfaceProfile", JPATH_LIBRARIES . '/itprism/integrate/interface/profile.php');

/**
 * This class provides functionality to
 * integrate extensions with the profile of JomSocial.
 *
 * @package      ITPrism
 * @subpackage   Integrations\Profile
 */
class ITPrismIntegrateProfileJomSocial implements ITPrismIntegrateInterfaceProfile
{
    protected $user_id;
    protected $avatar;
    protected $location;
    protected $country;
    protected $country_code;

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
     * $profile = new ITPrismIntegrateProfileJomSocial($userId);
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
     * @param  int $id
     *
     * <code>
     * $userId = 1;
     *
     * $profile = ITPrismIntegrateProfileJomSocial::getInstance($userId);
     * </code>
     * 
     * @return null|ITPrismIntegrateProfileJomSocial
     */
    public static function getInstance($id)
    {
        if (empty(self::$instances[$id])) {
            $item                 = new ITPrismIntegrateProfileJomSocial($id);
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
     * $profile = new ITPrismIntegrateProfileJomSocial();
     * $profile->load($userId);
     * </code>
     *
     * @param int $id
     */
    public function load($id)
    {
        $query = $this->db->getQuery(true);
        $query
            ->select("a.userid AS user_id, a.thumb AS avatar")
            ->from($this->db->quoteName("#__community_users", "a"))
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
     * $profile = new ITPrismIntegrateProfileJomSocial();
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
     * $profile = new ITPrismIntegrateProfileJomSocial($userId);
     * $link = $profile->getLink();
     * </code>
     * 
     * @return string Return a link to the profile.
     */
    public function getLink()
    {
        return CRoute::_('index.php?option=com_community&view=profile&userid=' . $this->user_id);
    }

    /**
     * Provide a link to social avatar.
     *
     * <code>
     * $userId = 1;
     *
     * $profile = new ITPrismIntegrateProfileJomSocial($userId);
     * $avatar = $profile->getAvatar();
     * </code>
     * 
     * @return string Return a link to the picture.
     */
    public function getAvatar()
    {
        if (!$this->avatar) {
            $link = JUri::root() . "components/com_community/assets/default_thumb.jpg";
        } else {
            $link = JUri::root() . $this->avatar;
        }

        return $link;
    }

    /**
     * Return a location name where the user lives.
     *
     * <code>
     * $userId = 1;
     *
     * $profile = new ITPrismIntegrateProfileJomSocial($userId);
     * $location = $profile->getLocation();
     * </code>
     *
     * @return string
     */
    public function getLocation()
    {
        if (is_null($this->location)) {

            $result = "";

            $query = $this->db->getQuery(true);

            $query
                ->select("a.id")
                ->from($this->db->quoteName("#__community_fields", "a"))
                ->where("a.type =  " . $this->db->quote("country"));

            $this->db->setQuery($query);
            $typeId = $this->db->loadResult();

            if (!empty($typeId)) {

                $query = $this->db->getQuery(true);

                $query
                    ->select("a.value")
                    ->from($this->db->quoteName("#__community_fields_values", "a"))
                    ->where("a.user_id =  " . (int)$this->user_id)
                    ->where("a.field_id =  " . (int)$typeId);

                $this->db->setQuery($query);
                $result = $this->db->loadResult();

                if (!$result) { // Set values to variables
                    $result = "";
                }
            }

            $this->location = $result;
        }

        return $this->location;
    }

    /**
     * Return a country code of a country where the user lives.
     *
     * <code>
     * $userId = 1;
     *
     * $profile = new ITPrismIntegrateProfileJomSocial($userId);
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
