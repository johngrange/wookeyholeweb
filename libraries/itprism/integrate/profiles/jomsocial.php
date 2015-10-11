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
JLoader::register("CRoute", JPATH_ROOT . '/components/com_community/libraries/core.php');

/**
 * This class provides functionality used for integrating
 * extensions with the profile of JomSocial.
 *
 * @package      ITPrism
 * @subpackage   Integrations\Profiles
 */
class ITPrismIntegrateProfilesJomSocial implements ITPrismIntegrateInterfaceProfiles
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
     * $profiles = new ITPrismIntegrateProfilesJomSocial($ids);
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
     * $profiles = new ITPrismIntegrateProfilesJomSocial();
     * $profiles->load($ids);
     * </code>
     *
     * @param array $ids
     */
    public function load($ids)
    {
        if (!empty($ids)) {

            $query = $this->db->getQuery(true);
            $query
                ->select("a.userid AS user_id, a.thumb AS avatar")
                ->from($this->db->quoteName("#__community_users", "a"))
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
     * $profiles = new ITPrismIntegrateProfilesJomSocial();
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

            if (!$this->profiles[$userId]->avatar) {
                $link = JUri::root() . "components/com_community/assets/default_thumb.jpg";
            } else {
                $link = JUri::root() . $this->profiles[$userId]->avatar;
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
     * $profiles = new ITPrismIntegrateProfilesJomSocial();
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
            $link = CRoute::_('index.php?option=com_community&view=profile&userid=' . $this->profiles[$userId]->user_id);
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
     * $profiles = new ITPrismIntegrateProfilesJomSocial();
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
     * $profiles = new ITPrismIntegrateProfilesJomSocial();
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
