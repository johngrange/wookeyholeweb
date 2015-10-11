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
 * AOGZooArticle class.
 *
 * @package     Extly.Plugins
 * @subpackage  autotweettwittercard
 * @since       1.0
 */
class OGZooArticle extends OGJArticleHelper
{
	/**
	 * __construct.
	 *
	 * @param   object  &$article  Params
	 */
	public function __construct(&$article)
	{
		parent::__construct($article);
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
		return;
	}
}
