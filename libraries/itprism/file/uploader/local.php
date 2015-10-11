<?php
/**
 * @package      ITPrism
 * @subpackage   Files\Uploaders
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2014 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined('JPATH_PLATFORM') or die;

jimport("joomla.filesystem.file");
jimport("joomla.filesystem.path");
jimport("joomla.filesystem.folder");

JLoader::register("ITPrismFileInterfaceUploader", JPATH_LIBRARIES . "/itprism/file/interface/uploader.php");

/**
 * This class provides functionality for uploading files and
 * validates the process.
 *
 * @package      ITPrism
 * @subpackage   Files\Uploaders
 */
class ITPrismFileUploaderLocal implements ITPrismFileInterfaceUploader
{
    protected $file = "";
    protected $destination = "";

    /**
     * Initialize the object.
     *
     * <code>
     * $myFile   = "/tmp/myfile.txt";
     *
     * $file = new ITPrismFileUploaderLocal($myFile);
     * </code>
     *
     * @param  string $file A path to the file.
     */
    public function __construct($file = "")
    {
        $this->file = $file;
    }

    /**
     * Set a path to a file.
     *
     * <code>
     * $myFile   = "/tmp/myfile.txt";
     *
     * $file = new ITPrismFileUploaderLocal($myFile);
     * $file->setFile($myFile);
     * </code>
     *
     * @param  string $file A path to the file.
     *
     * @return self
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Set the destination where the file will be saved.
     *
     * <code>
     * $myFile   = "/tmp/myfile.jpg";
     * $destination   = "/images/mypic.jpg";
     *
     * $file = new ITPrismFileUploaderLocal($myFile);
     * $file->setDestination($destination);
     * </code>
     *
     * @param  string $destination A location where the file is going to be saved.
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     *
     * @return self
     */
    public function setDestination($destination)
    {
        $destination = JPath::clean($destination);
        if (!$destination) {
            throw new InvalidArgumentException(JText::_("LIB_ITPRISM_ERROR_INVALID_DESTINATION"));
        }

        $folder = dirname($destination);
        if (!JFolder::exists($folder)) {
            throw new RuntimeException(JText::sprintf("LIB_ITPRISM_ERROR_FOLDER_DOES_NOT_EXIST", $folder));
        }

        $this->destination = $destination;

        return $this;
    }

    /**
     * Return the location where the file has been uploaded ( path + filename ).
     *
     * <code>
     * $file->upload();
     *
     * $destination = $file->getDestination();
     * </code>
     *
     * @return string
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * Set the destination where the file will be saved.
     *
     * <code>
     * $myFile   = "/tmp/myfile.jpg";
     * $destination   = "/images/mypic.jpg";
     *
     * $file = new ITPrismFileUploaderLocal($myFile);
     * $file->setDestination($destination);
     * $file->upload();
     * </code>
     *
     * @throws RuntimeException
     */
    public function upload()
    {
        if (!JFile::upload($this->file, $this->destination)) {
            throw new RuntimeException(JText::_('LIB_ITPRISM_ERROR_FILE_CANT_BE_UPLOADED'));
        }
    }
}
