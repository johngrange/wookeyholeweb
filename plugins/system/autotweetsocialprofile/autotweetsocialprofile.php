<?php

/**
 * @package     Extly.Components
 * @subpackage  PlgSystemAutotweetSocialProfile - AutoTweet Social Profile Links for Google
 *
 * @author      Prieco S.A. <support@extly.com>
 * @copyright   Copyright (C) 2007 - 2015 Prieco, S.A. All rights reserved.
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @link        http://www.extly.com http://support.extly.com
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * AutoTweet Social Profile Links for Google
 *
 * @package     Extly.Components
 * @subpackage  com_autotweet
 * @since       1.0
 */
class PlgSystemAutotweetSocialProfile extends JPlugin
{
	/**
	 * onBeforeRender
	 *
	 * @return array A four element array of (article_id, article_title, category_id, object)
	 */
	public function onBeforeRender()
	{
		// Use only for front-end site.
		if (JFactory::getApplication()->isAdmin())
		{
			return;
		}

		$type = $this->params->get('type', 'Organization');
		$name = $this->params->get('name', JFactory::getConfig()->get('sitename'));
		$url = $this->params->get('url', JUri::root());

		$sameAsFacebook = $this->params->get('sameAsFacebook');
		$sameAsTwitter = $this->params->get('sameAsTwitter');
		$sameAsGPlus = $this->params->get('sameAsGPlus');
		$sameAsInstagram = $this->params->get('sameAsInstagram');
		$sameAsYoutube = $this->params->get('sameAsYoutube');
		$sameAsLinkedIn = $this->params->get('sameAsLinkedIn');
		$sameAsMyspace = $this->params->get('sameAsMyspace');

		$structured_markup = array();
		$sameAs = array();

		$structured_markup['type'] = $type;
		$structured_markup['name'] = $name;
		$structured_markup['url'] = $url;

		if ($sameAsFacebook)
		{
			$sameAs[] = $sameAsFacebook;
		}

		if ($sameAsTwitter)
		{
			$sameAs[] = $sameAsTwitter;
		}

		if ($sameAsGPlus)
		{
			$sameAs[] = $sameAsGPlus;
		}

		if ($sameAsInstagram)
		{
			$sameAs[] = $sameAsInstagram;
		}

		if ($sameAsYoutube)
		{
			$sameAs[] = $sameAsYoutube;
		}

		if ($sameAsLinkedIn)
		{
			$sameAs[] = $sameAsLinkedIn;
		}

		if ($sameAsMyspace)
		{
			$sameAs[] = $sameAsMyspace;
		}

		if (!empty($sameAs))
		{
			$structured_markup['sameAs'] = $sameAs;
		}

		JFactory::getDocument()->addScriptDeclaration(json_encode($structured_markup), 'application/ld+json');

		return;
	}
}
