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
 * AutotweetViewFeed
 *
 * @package     Extly.Components
 * @subpackage  com_autotweet
 * @since       1.0
 */
class AutotweetViewFeed extends AutotweetDefaultView
{
	/**
	 * onAdd.
	 *
	 * @param   string  $tpl  Param
	 *
	 * @return	void
	 */
	public function onAdd($tpl = null)
	{
		$jlang = JFactory::getLanguage();
		$jlang->load('com_content');

		Extly::loadAwesome();

		$file = EHtml::getRelativeFile('js', 'com_autotweet/feed.min.js');

		if ($file)
		{
			$dependencies = array();
			$paths = array();

			$ajax_import = EParameter::getComponentParam(CAUTOTWEETNG, 'ajax_import', true);
			$this->assignRef('ajax_import', $ajax_import);

			if ($ajax_import)
			{
				$paths['import'] = 'media/com_autotweet/js/import.min';
			}

			Extly::initApp(CAUTOTWEETNG_VERSION, $file, $dependencies, $paths);
		}

		return parent::onAdd($tpl);
	}
}
