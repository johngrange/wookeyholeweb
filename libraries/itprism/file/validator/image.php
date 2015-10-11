<?php
/**
 * @package      ITPrism
 * @subpackage   Files\Validators
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2014 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined('JPATH_PLATFORM') or die;

jimport("joomla.filesystem.path");
jimport("joomla.filesystem.file");
jimport('joomla.image.image');

JLoader::register("ITPrismFileValidator", JPATH_LIBRARIES . "/itprism/file/validator.php");

/**
 * This class provides functionality for validating an image
 * by MIME type and file extensions.
 *
 * @package      ITPrism
 * @subpackage   Files\Validators
 */
class ITPrismFileValidatorImage extends ITPrismFileValidator
{
    protected $file;
    protected $fileName;

    protected $mimeTypes;
    protected $imageExtensions;

    /**
     * Initialize the object.
     *
     * <code>
     * $myFile     = "/tmp/myfile.jpg";
     * $fileName   = "myfile.jpg";
     *
     * $validator = new ITPrismFileValidatorImage($myFile, $fileName);
     * </code>
     *
     * @param string $file A path to the file.
     * @param string $fileName File name
     */
    public function __construct($file = "", $fileName = "")
    {
        $this->file     = JPath::clean($file);
        $this->fileName = JFile::makeSafe(basename($fileName));
    }

    /**
     * Set a location of a file.
     *
     * <code>
     * $myFile     = "/tmp/myfile.jpg";
     *
     * $validator = new ITPrismFileValidatorImage();
     * $validator->setFile($myFile);
     * </code>
     *
     * @param string $file
     */
    public function setFile($file)
    {
        $this->file = JPath::clean($file);
    }

    /**
     * Set a file name.
     *
     * <code>
     * $fileName  = "myfile.jpg";
     *
     * $validator = new ITPrismFileValidatorImage();
     * $validator->setFileName($fileName);
     * </code>
     *
     * @param string $fileName
     */
    public function setFileName($fileName)
    {
        $this->fileName = JFile::makeSafe($fileName);
    }

    /**
     * Set a mime types that are allowed.
     *
     * <code>
     * $mimeTypes  = array("image/jpeg", "image/gif");
     *
     * $validator = new ITPrismFileValidatorImage();
     * $validator->setMimeTypes($mimeTypes);
     * </code>
     *
     * @param array $mimeTypes
     */
    public function setMimeTypes($mimeTypes)
    {
        $this->mimeTypes = $mimeTypes;
    }

    /**
     * Set a file extensions that are allowed.
     *
     * <code>
     * $imageExtensions  = array("jpg", "png");
     *
     * $validator = new ITPrismFileValidatorImage();
     * $validator->setImageExtensions($imageExtensions);
     * </code>
     *
     * @param array $imageExtensions
     */
    public function setImageExtensions($imageExtensions)
    {
        $this->imageExtensions = $imageExtensions;
    }

    /**
     * Validate image type and extension.
     *
     * <code>
     * $myFile     = "/tmp/myfile.jpg";
     * $fileName   = "myfile.jpg";
     *
     * $validator = new ITPrismFileValidatorImage($myFile, $fileName);
     *
     * if (!$validator->isValid()) {
     *     echo $validator->getMessage();
     * }
     * </code>
     *
     * @return bool
     */
    public function isValid()
    {
        if (!JFile::exists($this->file)) {
            $this->message = JText::sprintf('LIB_ITPRISM_ERROR_FILE_DOES_NOT_EXISTS', $this->file);
            return false;
        }
        $imageProperties = JImage::getImageFileProperties($this->file);

        // Check mime type of the file
        if (false === array_search($imageProperties->mime, $this->mimeTypes)) {
            $this->message = JText::_('LIB_ITPRISM_ERROR_FILE_TYPE');
            return false;
        }

        // Check file extension
        $ext = JString::strtolower(JFile::getExt($this->fileName));

        if (false === array_search($ext, $this->imageExtensions)) {
            $this->message = JText::sprintf('LIB_ITPRISM_ERROR_FILE_EXTENSIONS', $ext);
            return false;
        }

        return true;
    }
}
