<?php
/**
 * @package      ITPrism
 * @subpackage   Validators\Interfaces
 * @copyright    Copyright (C) 2014 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

// no direct access
defined('JPATH_PLATFORM') or die;

/**
 * This interface describes validators methods.
 *
 * @package      ITPrism
 * @subpackage   Validators\Interfaces
 */
interface ITPrismValidatorInterface
{
    public function isValid();
}
