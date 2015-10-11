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

JLoader::register("ITPrismFileValidator", JPATH_LIBRARIES . "/itprism/file/validator.php");

/**
 * This class provides functionality for validating a file
 * by MIME type and file extensions.
 *
 * @package      ITPrism
 * @subpackage   Files\Validators
 */
class ITPrismFileValidatorType extends ITPrismFileValidator
{
    protected $file;
    protected $fileName;

    protected $mimeTypes;
    protected $legalExtensions;

    /**
     * Initialize the object.
     *
     * <code>
     * $myFile     = "/tmp/myfile.jpg";
     * $fileName   = "myfile.jpg";
     *
     * $validator = new ITPrismFileValidatorType($myFile, $fileName);
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
     * $validator = new ITPrismFileValidatorType();
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
     * $validator = new ITPrismFileValidatorType();
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
     * $validator = new ITPrismFileValidatorType();
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
     * $legalExtensions  = array("jpg", "png");
     *
     * $validator = new ITPrismFileValidatorType();
     * $validator->setLegalExtensions($legalExtensions);
     * </code>
     *
     * @param array $legalExtensions
     */
    public function setLegalExtensions($legalExtensions)
    {
        $this->legalExtensions = $legalExtensions;
    }

    /**
     * Validate the file by MIME type and extension.
     *
     * <code>
     * $myFile     = "/tmp/myfile.jpg";
     * $fileName   = "myfile.jpg";
     *
     * $validator = new ITPrismFileValidatorType($myFile, $fileName);
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

        // Get mime type
        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($fileInfo, $this->file);
        finfo_close($fileInfo);

        // Check mime type of the file
        if (false === array_search($mimeType, $this->mimeTypes)) {
            $this->message = JText::sprintf('LIB_ITPRISM_ERROR_FILE_TYPE', $this->fileName, $mimeType);
            return false;
        }

        // Check file extension
        $ext = JString::strtolower(JFile::getExt($this->fileName));

        if (false === array_search($ext, $this->legalExtensions)) {
            $this->message = JText::sprintf('LIB_ITPRISM_ERROR_FILE_EXTENSIONS', $ext);
            return false;
        }

        return true;
    }
}
