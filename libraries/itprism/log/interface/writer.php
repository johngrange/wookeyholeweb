<?php
/**
 * @package         ITPrism
 * @subpackage      Logs\Interfaces
 * @author          Todor Iliev
 * @copyright       Copyright (C) 2014 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license         http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined('JPATH_PLATFORM') or die;

/**
 * This is the interface of log writer classes.
 *
 * @package         ITPrism
 * @subpackage      Logs\Interfaces
 */
interface ITPrismLogInterfaceWriter
{
    public function setTitle($title);
    public function setType($type);
    public function setData($data);
    public function setDate($date);
    public function store();
}
