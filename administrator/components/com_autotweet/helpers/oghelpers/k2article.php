<?php

/**
 * @package     Extly.Components
 * @subpackage  PlgContentAutotweetOpenGraph - Plugin AutoTweet NG OpenGraph Tags-Extension for Joomla!
 *
 * @author      Prieco S.A. <support@extly.com>
 * @copyright   Copyright (C) 2007 - 2015 Prieco, S.A. All rights reserved.
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @link        http://www.extly.com http://support.extly.com
 */
// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * OGK2Article class.
 *
 * @package     Extly.Plugins
 * @subpackage  autotweettwittercard
 * @since       1.0
 */
class OGK2Article extends OGJArticleHelper
{
	/**
	 * __construct.
	 *
	 * @param   object  &$article  Params
	 */
	public function __construct(&$article)
	{
		parent::__construct($article);
		$this->articleObj->category_title = $this->categoryTitle($article);
	}

	/**
	 * introImage
	 *
	 * @param   object  &$article  Param
	 *
	 * @return	string
	 */
	protected function introImage(&$article)
	{
		$imageUrl = '';

		if (isset($article->imageMedium))
		{
			$imageUrl = $article->imageMedium;
		}

		if (empty($imageUrl) && isset($article->imageLarge))
		{
			$imageUrl = $article->imageLarge;
		}

		if (empty($imageUrl) && isset($article->imageXLarge))
		{
			$imageUrl = $article->imageXLarge;
		}

		if (empty($imageUrl) && isset($article->imageSmall))
		{
			$imageUrl = $article->imageSmall;
		}

		if (empty($imageUrl) && isset($article->imageXSmall))
		{
			$imageUrl = $article->imageXSmall;
		}

		$parsedRootUrl = parse_url(JUri::root());
		$parsedImageURL = '';

		if (!empty($imageUrl))
		{
			$parsedImageURL = str_replace($parsedRootUrl['path'], '', $imageUrl);
		}

		return $parsedImageURL;
	}

	/**
	 * fullTextImage
	 *
	 * @param   object  &$article  Param
	 *
	 * @return	string
	 */
	protected function fullTextImage(&$article)
	{
		$fullText = '';
		$contentImages = array();

		if (isset($article->fulltext))
		{
			$fullText = $article->fulltext;
			$contentImages = $this->imagesInTextContent($fullText);
		}

		if (empty($contentImages))
		{
			return;
		}

		$firstContentImage = $contentImages[0]['src'];

		return $firstContentImage;
	}

	/**
	 * imagesInTextContent
	 *
	 * @param   object  &$textContent  Param
	 *
	 * @return	string
	 */
	public function imagesInTextContent(&$textContent)
	{
		static $images = null;

		if ($images)
		{
			return $images;
		}

		$images = array();

		if ((empty($textContent)) || (!class_exists('DOMDocument')))
		{
			return $images;
		}

		$html = new DOMDocument;
		$html->recover = true;
		$html->strictErrorChecking = false;
		@$html->loadHTML($textContent);

		foreach ($html->getElementsByTagName('img') as $image)
		{
			$images[] = array(
				'src' => $image->getAttribute('src'),
				'class' => $image->getAttribute('class')
			);
		}

		return $images;
	}

	/**
	 * isPublished
	 *
	 * @param   object  &$article  Param
	 *
	 * @return	string
	 */
	public function isPublished(&$article)
	{
		$isPublished = false;

		if (isset($article->published))
		{
			$isPublished = $article->published;
		}

		if ($isPublished == false)
		{
			return false;
		}

		$publishUp = isset($article->publish_up) ? $article->publish_up : '';
		$publishDown = isset($article->publish_down) ? $article->publish_down : '';
		$date = JFactory::getDate();
		$currentDate = $date->toSql();

		if ($publishUp > $currentDate)
		{
			return false;
		}
		elseif ($publishDown < $currentDate && $publishDown != '0000-00-00 00:00:00' && !empty($publishDown))
		{
			return false;
		}
		else
		{
			return true;
		}

		return true;
	}

	/**
	 * categoryTitle
	 *
	 * @param   object  &$article  Param
	 *
	 * @return	string
	 */
	public function categoryTitle(&$article)
	{
		$category_title = '';

		if (isset($article->category->name))
		{
			$category_title = $article->category->name;
		}

		return $category_title;
	}

	/**
	 * url
	 *
	 * @param   object  &$article  Param
	 *
	 * @return	string
	 */
	public function url(&$article)
	{
	}
}
