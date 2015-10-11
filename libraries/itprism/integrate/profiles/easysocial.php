<?php
/**
 * @package      ITPrism
 * @subpackage   Integrations\Profiles
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2014 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined('JPATH_PLATFORM') or die;

JLoader::register("ITPrismIntegrateInterfaceProfiles", JPATH_LIBRARIES . '/itprism/integrate/interface/profiles.php');
JLoader::register("Foundry", JPATH_ROOT . '/administrator/components/com_easysocial/includes/foundry.php');

/**
 * This class provides functionality used for integrating
 * extensions with the profile of EasySocial.
 *
 * @package      ITPrism
 * @subpackage   Integrations\Profiles
 */
class ITPrismIntegrateProfilesEasySocial implements ITPrismIntegrateInterfaceProfiles
{
    protected $profiles = array();

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
     * $profiles = new ITPrismIntegrateProfilesEasySocial($ids);
     * </code>
     *
     * @param  array $ids Users IDs
     */
    public function __construct($ids = array())
    {
        $this->db = JFactory::getDbo();

        if (!empty($ids)) {
            $this->load($ids);
        }
    }

    /**
     * Load data about profiles from database.
     *
     * <code>
     * $ids = array(1, 2, 3, 4);
     *
     * $profiles = new ITPrismIntegrateProfilesEasySocial();
     * $profiles->load($ids);
     * </code>
     *
     * @param $ids
     */
    public function load($ids)
    {
        if (!empty($ids)) {
            $query = $this->db->getQuery(true);
            $query
                ->select(
                    "a.id AS user_id, a.name, a.username, " .
                    "b.alias, b.permalink, " .
                    "c.medium AS avatar"
                )
                ->from($this->db->quoteName("#__users", "a"))
                ->leftJoin($this->db->quoteName("#__social_users", "b") . " ON a.id = b.user_id")
                ->leftJoin($this->db->quoteName("#__social_avatars", "c") . " ON a.id = c.uid")
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
     * $profiles = new ITPrismIntegrateProfilesEasySocial();
     * $profiles->load($ids);
     *
     * $avatar = $profiles->getAvatar($userId);
     * </code>
     *
     * @param integer $userId
     * @param mixed   $size
     *
     * @return string
     *
     * @todo improve avatar size
     */
    public function getAvatar($userId, $size = "medium")
    {
        if (!isset($this->profiles[$userId])) {
            $link = "";
        } else {

            if (empty($this->profiles[$userId]->avatar)) {
                $link = JUri::root() . "media/com_easysocial/defaults/avatars/users/medium.png";
            } else {
                $link = JUri::root() . "media/com_easysocial/avatars/users/" . (int)$this->profiles[$userId]->user_id . "/" . $this->profiles[$userId]->avatar;
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
     * $profiles = new ITPrismIntegrateProfilesEasySocial();
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

            $options = array('id' => $this->getAlias($this->profiles[$userId]));

            $link = FRoute::profile($options);
        }

        return $link;
    }

    protected function getAlias($user)
    {
        $config = Foundry::config();

        // Default permalink to use.
        $name = $config->get('users.aliasName') == 'realname' ? $user->name : $user->username;
        $name = $user->user_id . ':' . $name;

        // Check if the permalink is set
        if ($user->permalink && !empty($user->permalink)) {
            $name = $user->permalink;
        }

        // If alias exists and permalink doesn't we use the alias
        if ($user->alias && !empty($user->alias) && !$user->permalink) {
            $name = $user->alias;
        }

        // Ensure that the name is a safe url.
        $name = JFilterOutput::stringURLSafe($name);

        return $name;
    }

    /**
     * Return a location name where the user lives.
     *
     * <code>
     * $ids = array(1, 2, 3, 4);
     * $userId = 1;
     *
     * $profiles = new ITPrismIntegrateProfilesEasySocial();
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
        return "";
    }

    /**
     * Return a country code of a country where the user lives.
     *
     * <code>
     * $ids = array(1, 2, 3, 4);
     * $userId = 1;
     *
     * $profiles = new ITPrismIntegrateProfilesEasySocial();
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
        return "";
    }
}
