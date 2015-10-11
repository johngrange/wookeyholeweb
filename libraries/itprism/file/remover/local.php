<?php
/**
 * @package      ITPrism
 * @subpackage   Files\Removers
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2014 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined('JPATH_PLATFORM') or die;

jimport("joomla.filesystem.file");

JLoader::register("ITPrismFileInterfaceRemover", JPATH_LIBRARIES . "/itprism/file/interface/remover.php");

/**
 * This class provides functionality for removing file.
 *
 * @package      ITPrism
 * @subpackage   Files\Removers
 */
class ITPrismFileRemoverLocal implements ITPrismFileInterfaceRemover
{
    protected $file = "";

    /**
     * Initialize the object.
     *
     * <code>
     * $myFile   = "/tmp/myfile.jpg";
     *
     * $file = new ITPrismFileRemoverLocal($myFile);
     * </code>
     *
     * @param  string $file A file location and name.
     */
    public function __construct($file = "")
    {
        $this->file = JPath::clean($file);
    }

    /**
     * Set a file location.
     *
     * <code>
     * $myFile   = "/tmp/myfile.jpg";
     *
     * $file = new ITPrismFileRemoverLocal();
     * $file->setFile($myFile);
     * </code>
     *
     * @param  string $file A file location and name.
     *
     * @return self
     */
    public function setFile($file)
    {
        $this->file = JPath::clean($file);

        return $this;
    }

    /**
     * Remove a file from the file system.
     *
     * <code>
     * $myFile   = "/tmp/myfile.jpg";
     *
     * $file = new ITPrismFileRemoverLocal($myFile);
     * $file->remove();
     * </code>
     */
    public function remove()
    {
        if (!$this->file or !JFile::exists($this->file)) {
            throw new RuntimeException(JText::_('LIB_ITPRISM_ERROR_INVALID_FILE'));
        }

        if (!JFile::delete($this->file)) {
            throw new RuntimeException(JText::_('LIB_ITPRISM_ERROR_FILE_CANT_BE_REMOVED'));
        }

        $this->file = "";
    }
}
