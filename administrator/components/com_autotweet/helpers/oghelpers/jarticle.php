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
 * OGJArticleHelper class.
 *
 * @package     Extly.Plugins
 * @subpackage  autotweettwittercard
 * @since       1.0
 */
class OGJArticleHelper
{
	protected $articleObj;

	/**
	 * __construct.
	 *
	 * @param   object  &$article  Params
	 */
	public function __construct(&$article)
	{
		$tempClass = new StdClass;

		foreach ($article as $property => $value)
		{
			$tempClass->$property = $value;
		}

		$tempClass->category_title = $this->categoryTitle($article);
		$tempClass->description = $this->description($article);
		$tempClass->isPublished = $this->isPublished($article);
		$tempClass->isPublic = $this->isPublic($article);
		$tempClass->image = $this->image($article);
		$tempClass->firstContentImage = $this->firstImageInContent($article);
		$tempClass->introImage = $this->introImage($article);
		$tempClass->fullTextImage = $this->fullTextImage($article);
		$tempClass->imageArray = $this->imagesInContent($article);
		$tempClass->url = $this->url($article);
		$tempClass->tags = $this->tags($article);
		$this->articleObj = $tempClass;
	}

	/**
	 * getArticleObj
	 *
	 * @return	string
	 */
	public function getArticle()
	{
		return $this->articleObj;
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
		return isset($article->category_title) ? $article->category_title : '';
	}

	/**
	 * image
	 *
	 * @param   object  &$article  Param
	 *
	 * @return	string
	 */
	public function image(&$article)
	{
		$image = $this->introImage($article);

		if (empty($image))
		{
			$image = $this->fullTextImage($article);
		}

		if (empty($image))
		{
			$image = $this->firstImageInContent($article);
		}

		return $image;
	}

	/**
	 * imagesInContent
	 *
	 * @param   object  &$article  Param
	 *
	 * @return	string
	 */
	public function imagesInContent(&$article)
	{
		static $images = null;

		if ($images)
		{
			return $images;
		}

		$content = $article->text;
		$content = empty($content) ? $article->fulltext : $content;
		$content = empty($content) ? $article->introtext : $content;

		$images = array();

		if ((empty($content)) || (!class_exists('DOMDocument')))
		{
			return $images;
		}

		$html = new DOMDocument;
		$html->recover = true;
		$html->strictErrorChecking = false;
		@$html->loadHTML($content);

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
	 * firstImageInContent
	 *
	 * @param   object  &$article  Param
	 *
	 * @return	string
	 */
	public function firstImageInContent(&$article)
	{
		$content = $article->text;
		$content = empty($content) ? $article->fulltext : $content;
		$content = empty($content) ? $article->introtext : $content;

		if (empty($content))
		{
			return;
		}

		$images = $this->imagesInContent($article);
		$image = (isset($images[0]['src'])) ? $images[0]['src'] : '';

		if (!empty($image))
		{
			$image = RouteHelp::getInstance()->getAbsoluteUrl($image, true);
		}

		return $image;
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
		$images = (isset($article->images)) ? $this->articleImages($article->images) : '';
		$introImage = (isset($images['intro'])) ? $images['intro'] : '';

		if (!empty($introImage))
		{
			$introImage = RouteHelp::getInstance()->getAbsoluteUrl($introImage, true);
		}

		return $introImage;
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
		$images = (isset($article->images)) ? $this->articleImages($article->images) : '';
		$fullTextImage = (isset($images['full'])) ? $images['full'] : '';

		if (!empty($fullTextImage))
		{
			$fullTextImage = RouteHelp::getInstance()->getAbsoluteUrl($fullTextImage, true);
		}

		return $fullTextImage;
	}

	/**
	 * articleImages
	 *
	 * @param   object  &$imageJSON  Param
	 *
	 * @return	string
	 */
	protected function articleImages(&$imageJSON)
	{
		$obj = json_decode($imageJSON);
		$introImage = (isset($obj->{'image_intro'})) ? $obj->{'image_intro'} : '';
		$fullImage = (isset($obj->{'image_fulltext'})) ? $obj->{'image_fulltext'} : '';
		$images = array(
						'intro' => $introImage,
						'full' => $fullImage
		);

		return $images;
	}

	/**
	 * description
	 *
	 * @param   object  &$article  Param
	 *
	 * @return	string
	 */
	public function description(&$article)
	{
		$descText = !empty($article->text) ? $article->text : '';
		$description = !empty($article->text) ? $article->text : '';

		if (isset($article->introtext) && (!empty($article->introtext)))
		{
			$descText = $article->introtext;
		}
		elseif (isset($article->metadesc) && !empty($article->metadesc))
		{
			$descText = $article->metadesc;
		}

		$descNeedles = array(
						"\n",
						"\r",
						"\"",
						"'"
		);

		str_replace($descNeedles, " ", $description);
		$description = substr(htmlspecialchars(strip_tags($descText), ENT_COMPAT, 'UTF-8'), 0, 250);

		return $description;
	}

	/**
	 * tags
	 *
	 * @param   object  &$article  Param
	 *
	 * @return	string
	 */
	private function tags(&$article)
	{
		$metatagString = isset($article->metakey) ? $article->metakey : '';

		if (empty($metatagString))
		{
			return;
		}

		$tags = explode(",", $metatagString);

		foreach ($tags as $key => $value)
		{
			$tagsArray[] = trim(str_replace(" ", "", $value));
		}

		return $tagsArray;
	}

	/**
	 * isExtensionInstalled
	 *
	 * @param   string  $option  Param
	 *
	 * @return	string
	 */
	protected function isExtensionInstalled($option)
	{
		$db = JFactory::getDbo();
		$query = 'SELECT extension_id AS id, element AS "option", params, enabled FROM ' . $db->quoteName('#__extensions') . ' WHERE ' . $db->quoteName('type') . '= ' . $db->quote('component') . ' AND ' . $db->quoteName('element') . '= ' . $db->quote($option);
		$db->setQuery($query);
		$result = $db->loadObject();

		if (!$result)
		{
			return false;
		}

		return true;
	}

	/**
	 * articleSlug
	 *
	 * @param   object  &$article  Param
	 *
	 * @return	string
	 */
	private function articleSlug(&$article)
	{
		$slug = $article->id . ':' . $this->articleAlias($article);

		return $slug;
	}

	/**
	 * articleAlias
	 *
	 * @param   object  &$article  Param
	 *
	 * @return	string
	 */
	private function articleAlias(&$article)
	{
		jimport('joomla.filter.output');
		$alias = $article->alias;

		if (empty($alias))
		{
			$db = JFactory::getDBO();
			$query = 'SELECT a.alias FROM ' . $db->quoteName('#__content') . ' AS ' . $db->quoteName('a') . ' WHERE a.id=' . $db->quote($article->id);
			$db->setQuery($query);
			$result = $db->loadObject();
			$alias = empty($result->alias) ? $article->title : $result->alias;
		}

		$alias = TextUtil::convertUrlSafe($alias);

		return $alias;
	}

	/**
	 * categoryAlias
	 *
	 * @param   object  &$article  Param
	 *
	 * @return	string
	 */
	private function categoryAlias(&$article)
	{
		jimport('joomla.filter.output');
		$db = JFactory::getDBO();
		$query = 'SELECT c.alias FROM ' . $db->quoteName('#__categories') . ' AS ' . $db->quoteName('c') . ' WHERE c.id=' . $db->quote($article->catid);
		$db->setQuery($query);
		$result = $db->loadObject();
		$alias = $result->alias;
		$alias = TextUtil::convertUrlSafe($alias);

		return $alias;
	}

	/**
	 * isPublic
	 *
	 * @param   object  &$article  Param
	 *
	 * @return	string
	 */
	public function isPublic(&$article)
	{
		if (!isset($article->access))
		{
			return false;
		}

		$access = $article->access;
		$isPublic = $access == '1' ? true : false;

		return $isPublic;
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
		$isPublState = $article->state == '1' ? true : false;

		if (!$isPublState)
		{
			return false;
		}

		$publishUp = isset($article->publish_up) ? $article->publish_up : '';
		$publishDown = isset($article->publish_down) ? $article->publish_down : '';

		if (empty($publishUp))
		{
			return false;
		}

		$date = JFactory::getDate();
		$currentDate = $date->toSql();

		if (($publishUp > $currentDate))
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
	}

	/**
	 * isArticle
	 *
	 * @return	bool
	 */
	public function isArticle()
	{
		$hasID = isset($this->articleObj->id) ? true : false;
		$hasTitle = isset($this->articleObj->title) ? true : false;

		if ($hasID && $hasTitle)
		{
			return true;
		}

		return false;
	}

	/**
	 * __get
	 *
	 * @param   string  $name  Param
	 *
	 * @return	bool
	 */
	public function __get($name)
	{
		return $this->$name;
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
		JLoader::register('JTableCategory', JPATH_PLATFORM . '/joomla/database/table/category.php');

		$cats = plgAutotweetBase::getContentCategories($article->catid);
		$cat_ids = $cats[0];
		$catNames = $cats[1];
		$catAlias = $cats[2];

		// Use main category for article url
		$cat_slug = $article->catid . ':' . TextUtil::convertUrlSafe($catAlias[0]);
		$id_slug = $article->id . ':' . TextUtil::convertUrlSafe($article->alias);

		// Create internal url for Joomla core content article
		JLoader::import('components.com_content.helpers.route', JPATH_ROOT);
		$url = ContentHelperRoute::getArticleRoute($id_slug, $cat_slug);
		$routeHelp = RouteHelp::getInstance();
		$url = $routeHelp->getAbsoluteUrl($url);

		return $url;
	}
}
