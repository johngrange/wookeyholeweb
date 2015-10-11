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
 * OGArticleFactory class.
 *
 * @package     Extly.Plugins
 * @subpackage  autotweettwittercard
 * @since       1.0
 */
class OGArticleFactory
{
	private static $_classNames = array(
		'com_content.article' => 'OGJArticleHelper',
		/*
		'com_k2.item' => 'OGK2Article'
		'com_virtuemart.productdetails' => 'OGVMArticle',
		'com_zoo.element.textarea' => 'OGZooArticle'
		*/
	);

	/**
	 * getHelper
	 *
	 * @param   string  $option    Param
	 * @param   string  $context   Param
	 * @param   object  &$article  Param
	 *
	 * @return	string
	 */
	public static function getHelper($option, $context, &$article)
	{
		if (array_key_exists($context, self::$_classNames))
		{
			JLoader::register('OGJArticleHelper', dirname(__FILE__) . '/jarticle.php');
			JLoader::register('OGK2Article', dirname(__FILE__) . '/k2article.php');
			JLoader::register('OGVMArticle', dirname(__FILE__) . '/vmarticle.php');
			JLoader::register('OGZooArticle', dirname(__FILE__) . '/zooarticle.php');

			$className = self::$_classNames[$context];
			$helper = new $className($article);

			return $helper;
		}

		return null;
	}
}
