<?php

/**
 * @package     Extly.Components
 * @subpackage  com_autotweet - A powerful social content platform to manage multiple social networks.
 *
 * @author      Prieco S.A. <support@extly.com>
 * @copyright   Copyright (C) 2007 - 2015 Prieco, S.A. All rights reserved.
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @link        http://www.extly.com http://support.extly.com
 */
// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * ImageUtil
 *
 * @package     Extly.Components
 * @subpackage  com_autotweet
 * @since       1.0
 */
class ImageUtil
{
	protected $has_tmp_file = false;

	/**
	 * getInstance
	 *
	 * @return	object
	 */
	public static function getInstance()
	{
		static $helper = null;

		if (!$helper)
		{
			$helper = new ImageUtil;
		}

		return $helper;
	}

	/**
	 * isValidImageFile
	 *
	 * @param   string  $imagefile  Param
	 *
	 * @return	bool
	 */
	public function isValidImageFile($imagefile)
	{
		list($width, $height, $type, $attr) = getimagesize($imagefile);

		$logger = AutotweetLogger::getInstance();
		$logger->log(JLog::INFO, "isValidImage ($width, $height, $type, $attr)");

		if (!in_array($type, array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG)))
		{
			return false;
		}

		$image_minx = EParameter::getComponentParam(CAUTOTWEETNG, 'image_minx', 100);

		if ($width < $image_minx)
		{
			return false;
		}

		$image_miny = EParameter::getComponentParam(CAUTOTWEETNG, 'image_miny', 0);

		if ($height < $image_miny)
		{
			return false;
		}

		return true;
	}

	/**
	 * isImage
	 *
	 * @param   object  $imagefile  Param.
	 *
	 * @return	string
	 */
	public static function isImage($imagefile)
	{
		return self::getInstance()->isValidImageFile($imagefile);
	}

	/**
	 * isValidImage
	 *
	 * @param   string  $image_url  Param
	 *
	 * @return	bool
	 */
	public function isValidImageUrl($image_url)
	{
		$file = self::downloadImage($image_url);

		if (!$file)
		{
			return false;
		}

		if ($this->has_tmp_file)
		{
			$this->releaseImage($file);
		}

		return true;
	}

	/**
	 * loadImage
	 *
	 * @param   string  $image_url  Param
	 *
	 * @return	string
	 */
	public function downloadImage($image_url)
	{
		$router = RouteHelp::getInstance();
		$this->has_tmp_file = false;
		$imagefile = $image_url;

		// Is Url?
		if ($router->isAbsoluteUrl($image_url))
		{
			$imagefile = str_replace($router->getRoot(), JPATH_ROOT . DIRECTORY_SEPARATOR, $image_url);

			// Is still Url ?
			if ($router->isAbsoluteUrl($imagefile))
			{
				// Download it in a tmp file
				$imagefile = JInstallerHelper::downloadPackage($image_url);

				if ($imagefile)
				{
					$this->has_tmp_file = true;
					$imagefile = JFactory::getConfig()->get('tmp_path') . DIRECTORY_SEPARATOR . $imagefile;
				}
			}

			// $imagefile is an absolute file
		}
		else
		{
			// Is relative file?
			if (strpos($image_url, DIRECTORY_SEPARATOR) > 0)
			{
				$imagefile = JPATH_ROOT . DIRECTORY_SEPARATOR . $image_url;
			}

			// $imagefile is an absolute file
		}

		// External Image? Download it into a tmp file, just to post it
		if (!is_file($imagefile))
		{
			return null;
		}

		$logger = AutotweetLogger::getInstance();
		$logger->log(JLog::INFO, 'downloadImage: ' . $imagefile);

		if (!$this->isValidImageFile($imagefile))
		{
			if ($this->has_tmp_file)
			{
				$this->releaseImage($imagefile);
			}

			return null;
		}

		return $imagefile;
	}

	/**
	 * releaseImage
	 *
	 * @param   string  $imagefile  Param
	 *
	 * @return	void
	 */
	public function releaseImage($imagefile)
	{
		// Double check
		if (($this->has_tmp_file) && ($imagefile)
			&& (strpos($imagefile, JFactory::getConfig()->get('tmp_path')) === 0))
		{
			JFile::delete($imagefile);
			$this->has_tmp_file = false;
		}
	}
}
