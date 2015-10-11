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
 * @subpackage   Integrations\Helpers
 */
abstract class ITPrismIntegrateHelper
{
    /**
     * Get a user ID from request.
     * This method should be used for pages,
     * which are intended to be use profiles and provides details about user.
     * Those pages should contains user ID with request parameters.
     *
     * <code>
     * $userId = ITPrismIntegrateHelper::getUserId();
     * </code>
     *
     * @return int
     */
    public static function getUserId()
    {
        $app = JFactory::getApplication();
        /** @var $app JApplicationSite */

        $option = $app->input->get("option");

        switch ($option) {

            case "com_socialcommunity":
                $userId = $app->input->getInt("id");
                break;

            case "com_community":
                $userId = $app->input->getInt("userid");
                break;

            case "com_easysocial":
                $userId = $app->input->getInt("id");
                break;

            case "com_kunena":
                $userId = $app->input->getInt("userid");
                break;

            default:
                $userId = JFactory::getUser()->get("id");
                break;
        }

        if (!$userId) {
            $userId = JFactory::getUser()->get("id");
        }

        return $userId;
    }
}
