<?php
/**
 * @package         ITPrism
 * @subpackage      Files
 * @author          Todor Iliev
 * @copyright       Copyright (C) 2014 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license         http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined('JPATH_PLATFORM') or die;

/**
 * This is the abstract class of file validators.
 *
 * @package         ITPrism
 * @subpackage      Files\Validators
 */
abstract class ITPrismFileValidator
{
    /**
     * Error message.
     *
     * @var string
     */
    protected $message;

    abstract public function isValid();

    /**
     * Return error message.
     * <code>
     * $validator = new ITPrismFileValidatorSize();
     *
     * if (!$validator->isValid()) {
     *     echo $validator->getMessage();
     * }
     * </code>
     *
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }
}
