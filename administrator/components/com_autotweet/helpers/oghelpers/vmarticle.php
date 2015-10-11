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
 * OGVMArticle class.
 *
 * @package     Extly.Plugins
 * @subpackage  autotweettwittercard
 * @since       1.0
 */
class OGVMArticle extends OGJArticleHelper
{
	protected $productImage;

	/**
	 * __construct.
	 *
	 * @param   object  &$article  Params
	 */
	public function __construct(&$article)
	{
		parent::__construct($article);
		$this->articleObj->modified = $article->modified_on;
		$this->articleObj->created = $article->created_on;
		$this->articleObj->title = $article->product_name;
		$this->articleObj->catid = $article->virtuemart_category_id;

		if (!empty($article->virtuemart_product_id))
		{
			$this->articleObj->id = $article->virtuemart_product_id;
		}
		elseif (!empty($article->virtuemart_product_id))
		{
			$this->articleObj->id = $article->product_id;
		}
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
		$category_title = isset($article->category_name) ? $article->category_name : '';

		return $category_title;
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
		$descText = !empty($article->product_desc) ? $article->product_desc : '';

		if (isset($article->product_s_desc) && !empty($article->product_s_desc))
		{
			$descText = $article->product_s_desc;
		}

		$descNeedles = array(
						"\n",
						"\r",
						"\"",
						"'"
		);
		$descText = str_replace($descNeedles, " ", $descText);
		$description = substr(htmlspecialchars(strip_tags($descText), ENT_COMPAT, 'UTF-8'), 0, 250);

		return $description;
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
		if ($article->published == '1')
		{
			return true;
		}

		return false;
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
		return true;
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
		if (!empty($this->productImage))
		{
			return $this->productImage;
		}

		$db = JFactory::getDBO();
		$query = 'SELECT medias.file_url_thumb' . ' FROM ' . $db->quoteName('#__virtuemart_product_medias') . ' AS ' . $db->quoteName('xref') . ' LEFT JOIN ' . $db->quoteName('#__virtuemart_medias') . ' AS ' . $db->quoteName('medias') . ' ON medias.virtuemart_media_id = xref.virtuemart_media_id' . ' WHERE xref.virtuemart_product_id = ' . $db->quote($article->virtuemart_product_id);
		$db->setQuery($query);
		$imagePath = $db->loadResult();
		$this->productImage = ((!empty($imagePath)) ? $imagePath : '');

		return $this->productImage;
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
		return $this->image($article);
	}

	/**
	 * introImage
	 *
	 * @param   object  &$article  Param
	 *
	 * @return	string
	 */
	public function introImage(&$article)
	{
		return $this->image($article);
	}

	/**
	 * fullTextImage
	 *
	 * @param   object  &$article  Param
	 *
	 * @return	string
	 */
	public function fullTextImage(&$article)
	{
		return $this->image($article);
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
		$images = array(
						$this->image($article)
		);

		return $images;
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
