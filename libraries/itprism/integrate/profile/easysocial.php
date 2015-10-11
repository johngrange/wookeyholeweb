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
JLoader::register("Foundry", JPATH_ROOT . '/administrator/components/com_easysocial/includes/foundry.php');

/**
 * This class provides functionality to
 * integrate extensions with the profile of JomSocial.
 *
 * @package      ITPrism
 * @subpackage   Integrations\Profile
 */
class ITPrismIntegrateProfileEasySocial implements ITPrismIntegrateInterfaceProfile
{
    protected $user_id;
    protected $avatar;
    protected $name;
    protected $username;
    protected $permalink;
    protected $alias;
    protected $location;
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
     * $profile = new ITPrismIntegrateProfileEasySocial($userId);
     * </code>
     *
     * @param  int $id User ID
     */
    public function __construct($id = 0)
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
     * $profile = ITPrismIntegrateProfileEasySocial::getInstance($userId);
     * </code>
     *
     * @param  int $id
     *
     * @return null|ITPrismIntegrateProfileEasySocial
     */
    public static function getInstance($id)
    {
        if (empty(self::$instances[$id])) {
            $item                 = new ITPrismIntegrateProfileEasySocial($id);
            self::$instances[$id] = $item;
        }

        return self::$instances[$id];
    }

    /**
     * Load user data from database.
     *
     * <code>
     * $userId = 1;
     *
     * $profile = new ITPrismIntegrateProfileEasySocial();
     * $profile->load($userId);
     * </code>
     *
     * @param int $id
     */
    public function load($id)
    {
        $query = $this->db->getQuery(true);
        $query
            ->select(
                "a.id AS user_id, a.name, a.username, ".
                "b.alias, b.permalink, " .
                "c.medium AS avatar"
            )
            ->from($this->db->quoteName("#__users", "a"))
            ->leftJoin($this->db->quoteName("#__social_users", "b") . " ON a.id = b.user_id")
            ->leftJoin($this->db->quoteName("#__social_avatars", "c") . " ON a.id = c.uid")
            ->where("a.id =" . (int)$id);

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
     * $profile = new ITPrismIntegrateProfileEasySocial();
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
     * $profile = new ITPrismIntegrateProfileEasySocial($userId);
     * $link = $profile->getLink();
     * </code>
     *
     * @return string Return a link to the profile.
     */
    public function getLink()
    {
        $options = array('id' => $this->getAlias());

        return FRoute::profile($options);
    }

    /**
     * Provide a link to social avatar.
     *
     * <code>
     * $userId = 1;
     *
     * $profile = new ITPrismIntegrateProfileEasySocial($userId);
     * $avatar = $profile->getAvatar();
     * </code>
     *
     * @param int $size  It is an avatar size.
     *
     * @return string Return a link to the picture.
     */
    public function getAvatar($size = 50)
    {
        if (!$this->avatar) {
            $link = JUri::root() . "media/com_easysocial/defaults/avatars/users/medium.png";
        } else {
            $link = JUri::root() . "media/com_easysocial/avatars/users/" . (int)$this->user_id . "/" . $this->avatar;
        }

        return $link;
    }

    protected function getAlias()
    {
        $config = Foundry::config();

        // Default permalink to use.
        $name = $config->get('users.aliasName') == 'realname' ? $this->name : $this->username;
        $name = $this->user_id . ':' . $name;

        // Check if the permalink is set
        if ($this->permalink && !empty($this->permalink)) {
            $name = $this->permalink;
        }

        // If alias exists and permalink doesn't we use the alias
        if ($this->alias && !empty($this->alias) && !$this->permalink) {
            $name = $this->alias;
        }

        // Ensure that the name is a safe url.
        $name = JFilterOutput::stringURLSafe($name);

        return $name;
    }

    /**
     * Return a location name where the user lives.
     *
     * <code>
     * $userId = 1;
     *
     * $profile = new ITPrismIntegrateProfileEasySocial($userId);
     * $location = $profile->getLocation();
     * </code>
     *
     * @return string
     */
    public function getLocation()
    {
        if (is_null($this->location)) {
            $this->prepareLocation();
        }

        return $this->location;
    }

    /**
     * Return a country code of a country where the user lives.
     *
     * <code>
     * $userId = 1;
     *
     * $profile = new ITPrismIntegrateProfileEasySocial($userId);
     * $countryCode = $profile->getCountryCode();
     * </code>
     *
     * @return string
     */
    public function getCountryCode()
    {
        if (is_null($this->country_code)) {
            $this->prepareLocation();
        }

        return $this->country_code;
    }

    private function prepareLocation()
    {
        $result = array();

        $query = $this->db->getQuery(true);

        $query
            ->select("a.id")
            ->from($this->db->quoteName("#__social_fields", "a"))
            ->where("a.unique_key =  " . $this->db->quote("ADDRESS"));

        $this->db->setQuery($query);
        $typeId = $this->db->loadResult();

        if (!empty($typeId)) {

            $query = $this->db->getQuery(true);

            $query
                ->select("a.data")
                ->from($this->db->quoteName("#__social_fields_data", "a"))
                ->where("a.uid =  " . (int)$this->user_id)
                ->where("a.field_id =  " . (int)$typeId);

            $this->db->setQuery($query);
            $result = $this->db->loadResult();

            if (!empty($result)) { // Set values to variables
                $result = json_decode($result, true);
            } else {
                $result = array();
            }
        }

        $this->location = (isset($result["city"])) ? $result["city"] : "";
        $this->country_code = (isset($result["country"])) ? $result["country"] : "";
    }
}
