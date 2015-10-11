<?php
/**
 * @package      ITPrism
 * @subpackage   Files
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2014 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined('JPATH_PLATFORM') or die;

jimport("itprism.file");

/**
 * This class contains methods that are used for managing currency.
 *
 * @package      ITPrism
 * @subpackage   Files
 */
class ITPrismFileImage extends ITPrismFile
{
    /**
     * Create a thumbnail from an image file.
     *
     * <code>
     * $myFile   = "/tmp/myfile.jpg";
     *
     * $options = array(
     *     "destination" => "image/mypic.jpg",
     *     "width" => 200,
     *     "height" => 200,
     *     "scale" => JImage::SCALE_INSIDE
     * );
     *
     * $file = new ITPrismFileImage($myFile);
     * $file->createThumbnail($options);
     *
     * </code>
     *
     * @param  array $options Some options used in the process of generating thumbnail.
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     *
     * @return string A location to the new file.
     */
    public function createThumbnail($options)
    {
        $destination = JArrayHelper::getValue($options, "destination");
        $width       = JArrayHelper::getValue($options, "width", 100);
        $height      = JArrayHelper::getValue($options, "height", 100);
        $scale       = JArrayHelper::getValue($options, "scale", JImage::SCALE_INSIDE);

        if (!$destination) {
            throw new InvalidArgumentException(JText::_("LIB_ITPRISM_ERROR_INVALID_FILE_DESTINATION"));
        }

        // Generate thumbnail.
        $image = new JImage();
        $image->loadFile($this->file);
        if (!$image->isLoaded()) {
            throw new RuntimeException(JText::sprintf('LIB_ITPRISM_ERROR_FILE_NOT_FOUND', $this->file));
        }

        // Resize the file as a new object
        $thumb = $image->resize($width, $height, true, $scale);

        $fileName = basename($this->file);
        $ext      = JString::strtolower(JFile::getExt(JFile::makeSafe($fileName)));

        switch ($ext) {
            case "gif":
                $type = IMAGETYPE_GIF;
                break;

            case "png":
                $type = IMAGETYPE_PNG;
                break;

            case IMAGETYPE_JPEG:
            default:
                $type = IMAGETYPE_JPEG;
        }

        $thumb->toFile($destination, $type);

        return $destination;
    }
}
