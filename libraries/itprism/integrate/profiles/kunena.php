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

/**
 * This class provides functionality used for integrating
 * extensions with the profile of Kunena.
 *
 * @package      ITPrism
 * @subpackage   Integrations\Profiles
 */
class ITPrismIntegrateProfilesKunena implements ITPrismIntegrateInterfaceProfiles
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
     * $profiles = new ITPrismIntegrateProfilesKunena($ids);
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
     * $profiles = new ITPrismIntegrateProfilesKunena();
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
                ->select("a.userid AS user_id, a.avatar")
                ->from($this->db->quoteName("#__kunena_users", "a"))
                ->where("a.userid IN ( " . implode(",", $ids) . ")");

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
     * $profiles = new ITPrismIntegrateProfilesKunena();
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
    public function getAvatar($userId, $size = 72)
    {
        if (!isset($this->profiles[$userId])) {
            $link = "";
        } else {

            if (empty($this->profiles[$userId]->avatar)) {
                $link = JUri::root() . "media/kunena/avatars/nophoto.jpg";
            } else {
                $link = JUri::root() . "media/kunena/avatars/resized/size" . (int)$size . "/" . $this->profiles[$userId]->avatar;
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
     * $profiles = new ITPrismIntegrateProfilesKunena();
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
            $link = KunenaRoute::_("index.php?option=com_kunena&view=profile&userid=" . (int)$this->profiles[$userId]->user_id, false);
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
     * $profiles = new ITPrismIntegrateProfilesKunena();
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
     * $profiles = new ITPrismIntegrateProfilesKunena();
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
