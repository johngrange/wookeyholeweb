<?php
/**
 * @package      ITPrism
 * @subpackage   Integrations\Interfaces
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2014 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined('JPATH_PLATFORM') or die;

/**
 * This class provides functionality to
 * integrate extensions with social profiles.
 *
 * @package      ITPrism
 * @subpackage   Integrations\Interfaces
 */
interface ITPrismIntegrateInterfaceProfile
{
    public function bind($data);
    public function load($id);
    public function getLink();
    public function getAvatar();
    public function getLocation();
    public function getCountryCode();
}
