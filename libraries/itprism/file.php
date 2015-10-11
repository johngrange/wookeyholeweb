<?php
/**
 * @package      ITPrism
 * @subpackage   Files
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2014 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined('JPATH_PLATFORM') or die;

jimport("joomla.filesystem.file");
jimport("joomla.filesystem.path");

/**
 * This class provides functionality for uploading files and
 * validates the process.
 *
 * @package      ITPrism
 * @subpackage   Files
 */
class ITPrismFile
{
    protected $file;

    protected $errors = array();

    /**
     * @var ITPrismFileInterfaceUploader
     */
    protected $uploader;

    protected $validators = array();
    protected $removers = array();

    /**
     * Initialize the object.
     *
     * <code>
     * $myFile   = "/tmp/myfile.txt";
     *
     * $file = new ITPrismFile($myFile);
     * </code>
     *
     * @param  mixed $file
     */
    public function __construct($file = "")
    {
        if (!empty($file)) {
            $this->file = JPath::clean($file);
        }
    }

    /**
     * Set an object that will be used for uploading files.
     *
     * <code>
     * $myFile   = "/tmp/myfile.txt";
     *
     * $uploader = new ITPrismFileUploaderLocal();
     *
     * $file = new ITPrismFile($myFile);
     * $file->setUploader($uploader);
     * </code>
     *
     * @param ITPrismFileInterfaceUploader $uploader
     */
    public function setUploader(ITPrismFileInterfaceUploader $uploader)
    {
        $this->uploader = $uploader;
    }

    /**
     * Get a file location ( path and file name ).
     *
     * <code>
     * $myFile   = "/tmp/myfile.txt";
     *
     * $file = new ITPrismFile($myFile);
     * $file->upload();
     *
     * $myNewFileLocation = $file->getFile();
     * </code>
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Upload a file.
     *
     * <code>
     * $myFile   = "/tmp/myfile.txt";
     *
     * $uploader = new ITPrismFileUploaderLocal();
     *
     * $file = new ITPrismFile($myFile);
     * $file->setUploader($uploader);
     *
     * $file->upload();
     * </code>
     *
     * @param  array $file An array that comes from JInput.
     * @param  string $destination Destination where the file is going to be saved.
     */
    public function upload(array $file = array(), $destination = "")
    {
        if (!empty($file)) {
            $this->uploader->setFile($file);
        }

        if (!empty($destination)) {
            $this->uploader->setDestination($destination);
        }

        $this->uploader->upload();

        $this->file = JPath::clean($this->uploader->getDestination());
    }

    /**
     * Add an object that validates the file.
     *
     * <code>
     * $validator = new ITPrismFileValidatorImage();
     *
     * $file = new ITPrismFile();
     * $file->addValidator($validator);
     * </code>
     *
     * @param  ITPrismFileValidator $validator An object that validate a file.
     * @param  bool $reset Remove existing validators.
     *
     * @return self
     */
    public function addValidator(ITPrismFileValidator $validator, $reset = false)
    {
        if (!empty($reset)) {
            $this->validators = array();
        }

        $this->validators[] = $validator;

        return $this;
    }

    /**
     * Add an object that removes the file.
     *
     * <code>
     * $remover = new ITPrismFileInterfaceRemover();
     *
     * $file = new ITPrismFile();
     * $file->addRemover($remover);
     * </code>
     *
     * @param  ITPrismFileInterfaceRemover $remover An object that validate a file.
     * @param  bool $reset Reset existing removers.
     *
     * @return self
     */
    public function addRemover(ITPrismFileInterfaceRemover $remover, $reset = false)
    {
        if (!empty($reset)) {
            $this->removers = array();
        }

        $this->removers[] = $remover;

        return $this;
    }

    /**
     * Validate the file.
     *
     * <code>
     * $myFile   = "/tmp/myfile.jpg";
     *
     * $validator = new ITPrismFileValidatorImage();
     *
     * $file = new ITPrismFile($myFile);
     * $file->addValidator($validator);
     *
     * if (!$file->isValid()) {
     * ...
     * )
     * </code>
     */
    public function isValid()
    {
        /** @var $validator ITPrismFileValidator */
        foreach ($this->validators as $validator) {
            if (!$validator->isValid()) {
                $this->errors[] = $validator->getMessage();
                return false;
            }
        }

        return true;
    }

    /**
     * Remove the file.
     *
     * <code>
     * $myFile   = "/tmp/myfile.jpg";
     *
     * $remover = new ITPrismFileInterfaceRemover();
     *
     * $file = new ITPrismFile($myFile);
     * $file->addRemover($remover);
     *
     * $file->remove();
     * </code>
     */
    public function remove()
    {
        /** @var  $remover ITPrismFileInterfaceRemover */
        foreach ($this->removers as $remover) {
            $remover->remove();
        }

        $this->file = "";
    }

    /**
     * Return all error messages.
     *
     * <code>
     * $myFile   = "/tmp/myfile.jpg";
     *
     * $validator = new ITPrismFileValidatorImage();
     *
     * $file = new ITPrismFile($myFile);
     * $file->addValidator($validator);
     *
     * if (!$file->isValid()) {
     *     $errors = $file->getErrors();
     * )
     * </code>
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Return last error message.
     *
     * <code>
     * $myFile   = "/tmp/myfile.jpg";
     *
     * $validator = new ITPrismFileValidatorImage();
     *
     * $file = new ITPrismFile($myFile);
     * $file->addValidator($validator);
     *
     * if (!$file->isValid()) {
     *     echo $file->getError();
     * )
     * </code>
     *
     * @return string
     */
    public function getError()
    {
        return end($this->errors);
    }
}
