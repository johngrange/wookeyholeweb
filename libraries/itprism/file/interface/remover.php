<?php
/**
 * @package         ITPrism
 * @subpackage      Files\Interfaces
 * @author          Todor Iliev
 * @copyright       Copyright (C) 2014 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license         http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined('JPATH_PLATFORM') or die;

/**
 * An interface of file removers.
 *
 * @package         ITPrism
 * @subpackage      Files\Interfaces
 */
interface ITPrismFileInterfaceRemover
{
    public function remove();
}
