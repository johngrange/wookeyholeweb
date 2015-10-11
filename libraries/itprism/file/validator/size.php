<?php
/**
 * @package      ITPrism
 * @subpackage   Files\Validators
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2014 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined('JPATH_PLATFORM') or die;

JLoader::register("ITPrismFileValidator", JPATH_LIBRARIES . "/itprism/file/validator.php");

/**
 * This class provides functionality for validating a file size.
 *
 * @package      ITPrism
 * @subpackage   Files\Validators
 */
class ITPrismFileValidatorSize extends ITPrismFileValidator
{
    /**
     * A maximum file size allowed for uploading.
     *
     * @var integer
     */
    protected $maxFileSize = 0;

    /**
     * The size of the file which will be validated.
     *
     * @var integer
     */
    protected $fileSize = 0;

    /**
     * Initialize the object.
     *
     * <code>
     * $fileSize  = 100000;
     * $maxFileSize  = 600000;
     *
     * $validator = new ITPrismFileValidatorSize($fileSize, $maxFileSize);
     * </code>
     *
     * @param integer $fileSize File size in bytes ( 1024 * 1024 ).
     * @param integer $maxFileSize Maximum allowed file size in bytes ( 1024 * 1024 ).
     */
    public function __construct($fileSize = 0, $maxFileSize = 0)
    {
        $this->fileSize    = (int)$fileSize;
        $this->maxFileSize = (int)$maxFileSize;
    }

    /**
     * Set a maximum allowed file size.
     *
     * <code>
     * $fileSize  = 100000;
     * $maxFileSize  = 600000;
     *
     * $validator = new ITPrismFileValidatorSize($fileSize);
     * $validator->setMaxFileSize($maxFileSize);
     * </code>
     *
     * @param integer $maxFileSize File size in bytes ( 1024 * 1024 ).
     */
    public function setMaxFileSize($maxFileSize)
    {
        $this->maxFileSize = (int)$maxFileSize;
    }

    /**
     * Validate the size of the file.
     *
     * <code>
     * $fileSize  = 100000;
     * $maxFileSize  = 600000;
     *
     * $validator = new ITPrismFileValidatorSize($fileSize, $maxFileSize);
     * if (!$validator->isValid()) {
     * .....
     * }
     * </code>
     *
     * @return bool
     */
    public function isValid()
    {
        $KB = 1024 * 1024;

        // Verify file size
        $uploadMaxFileSize = (int)ini_get('upload_max_filesize');
        $uploadMaxFileSize = $uploadMaxFileSize * $KB;

        $postMaxSize = (int)(ini_get('post_max_size'));
        $postMaxSize = $postMaxSize * $KB;

        $memoryLimit = (int)(ini_get('memory_limit'));
        if ($memoryLimit != -1) {
            $memoryLimit = $memoryLimit * $KB;
        }

        if (
            ($this->fileSize > $uploadMaxFileSize) or
            ($this->fileSize > $postMaxSize) or
            (($this->fileSize > $memoryLimit) and ($memoryLimit != -1))
        ) { // Log error

            $info  = JText::sprintf(
                "LIB_ITPRISM_ERROR_FILE_INFORMATION",
                round($this->fileSize / $KB, 0),
                round($uploadMaxFileSize / $KB, 0),
                round($postMaxSize / $KB, 0),
                round($memoryLimit / $KB, 0)
            );

            JLog::add($info);
            $this->message = JText::_("LIB_ITPRISM_ERROR_WARNFILETOOLARGE");
            return false;
        }

        // Validate the max file size set by the user.
        if (($this->maxFileSize != 0) and ($this->fileSize > $this->maxFileSize)) {

            $info = JText::sprintf(
                "LIB_ITPRISM_ERROR_FILE_INFORMATION",
                round($this->fileSize / $KB, 0),
                round($this->maxFileSize / $KB, 0)
            );

            JLog::add($info);

            $this->message = JText::_("LIB_ITPRISM_ERROR_WARNFILETOOLARGE");
            return false;
        }

        return true;
    }
}
