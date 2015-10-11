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
 * FeedImage
 *
 * @package     Extly.Components
 * @subpackage  com_autotweet
 * @since       1.0
 */
class FeedImage
{
	public $src = null;

	public $original_src = null;

	public $title = null;

	public $alt = null;

	public $class = null;

	public $style = null;

	public $align = null;

	public $border = null;

	public $width = null;

	public $height = null;

	/**
	 * generateTag
	 *
	 * @return	string
	 */
	public function generateTag()
	{
		$tag = array();
		$tag[] = '<img src="';
		$tag[] = $this->src;
		$tag[] = '"';

		if ($this->title)
		{
			$tag[] = ' title="';
			$tag[] = $this->title . '"';
		}

		if ($this->alt)
		{
			$tag[] = ' alt="';
			$tag[] = $this->alt . '"';
		}

		if ($this->class)
		{
			$tag[] = ' class="';
			$tag[] = $this->class . '"';
		}

		if ($this->style)
		{
			$tag[] = ' style="';
			$tag[] = $this->style . '"';
		}

		if ($this->align)
		{
			$tag[] = ' align="';
			$tag[] = $this->align . '"';
		}

		if ($this->border)
		{
			$tag[] = ' border="';
			$tag[] = $this->border . '"';
		}

		if ($this->width)
		{
			$tag[] = ' width="';
			$tag[] = $this->width . '"';
		}

		if ($this->height)
		{
			$tag[] = ' height="';
			$tag[] = $this->height . '"';
		}

		$tag[] = '/>';

		return implode('', $tag);
	}

	/**
	 * download
	 *
	 * @param   array  $params  Params
	 *
	 * @return	bool
	 */
	public function download($params)
	{
		$logger = AutotweetLogger::getInstance();
		$rel_src = $params->get('rel_src');
		$img_folder = $params->get('img_folder');
		$sub_folder = $params->get('sub_folder');
		$img_name_type = $params->get('img_name_type');
		$imageHelper = ImageUtil::getInstance();

		$filename = $imageHelper->downloadImage($this->src);

		if ( (!$filename) || (!file_exists($filename)) )
		{
			$logger->log(JLog::ERROR, 'download: failed ' . $this->src);

			return false;
		}

		// Main folder
		$path = JPATH_ROOT . DIRECTORY_SEPARATOR . $img_folder;

		// Sub folder
		$path_subfolder = $this->_getSubfolder($path, $sub_folder);

		if (!JFolder::exists($path_subfolder))
		{
			$result = JFolder::create($path_subfolder);

			if (!$result)
			{
				$imageHelper->releaseImage($filename);
				$logger->log(JLog::ERROR, 'download: JFolder::create subfolder ' . $path_subfolder);

				return false;
			}
		}

		$img_filename = $this->_getImgFilename($filename, $img_name_type);
		$final_filename = $path_subfolder . DIRECTORY_SEPARATOR . $img_filename;
		$result = JFile::move($filename, $final_filename);

		if (!$result)
		{
			$imageHelper->releaseImage($filename);
			$logger->log(JLog::ERROR, 'download: JFile::move ' . $filename . ' - ' . $final_filename);

			return false;
		}

		$imgurl = str_replace(JPATH_ROOT . DIRECTORY_SEPARATOR, '', $final_filename);
		$this->original_src = $this->src;

		if ($rel_src)
		{
			$this->src = $imgurl;
		}
		else
		{
			$this->src = RouteHelp::getInstance()->getRoot() . $imgurl;
		}

		return true;
	}

	/**
	 * _getSubfolder
	 *
	 * @param   string  $rootpath    Params
	 * @param   int     $sub_folder  Params
	 *
	 * @return	string
	 */
	private function _getSubfolder($rootpath, $sub_folder)
	{
		$path = array();

		$time = JFactory::getDate()->toUnix();

		// Year
		$path[] = date('Y', $time);

		// Month
		if ($sub_folder == 3)
		{
			$path[] = date('m', $time);
		}

		// Week
		if ($sub_folder == 2)
		{
			$path[] = date('W', $time);
		}

		// Day
		if ($sub_folder == 1)
		{
			$path[] = date('m', $time);
			$path[] = date('d', $time);
		}

		$subpath = implode(DIRECTORY_SEPARATOR, $path);

		return $rootpath . DIRECTORY_SEPARATOR . $subpath;
	}

	/**
	 * _getImgFilename
	 *
	 * @param   string  $filename  Params
	 * @param   int     $type      Params
	 *
	 * @return	string
	 */
	private function _getImgFilename($filename, $type)
	{
		$filename = basename($filename);

		// Use Image Title/Alt
		if ($type == 0)
		{
			$ext = pathinfo($filename, PATHINFO_EXTENSION);

			if (!empty($this->title))
			{
				$filename = $this->title;
			}
			elseif (!empty($this->alt))
			{
				$filename = $this->alt;
			}
			else
			{
				return $filename;
			}

			$filename = TextUtil::convertUrlSafe($filename);

			return $filename . '.' . $ext;
		}

		// Use Original Filename
		if ($type == 1)
		{
			return $filename;
		}

		// Use md5 hash
		if ($type == 2)
		{
			$ext = pathinfo($filename, PATHINFO_EXTENSION);
			$filename = md5($filename);

			return $filename . '.' . $ext;
		}

		return $filename;
	}
}
