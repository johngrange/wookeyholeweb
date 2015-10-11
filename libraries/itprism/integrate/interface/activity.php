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
 * This class provides functionality
 * to integrate extensions with activity services.
 *
 * @package      ITPrism
 * @subpackage   Integrations\Interfaces
 */
interface ITPrismIntegrateInterfaceActivity
{
    public function store();
}
