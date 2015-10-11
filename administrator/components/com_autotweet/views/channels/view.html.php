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
 * AutotweetViewChannels
 *
 * @package     Extly.Components
 * @subpackage  com_autotweet
 * @since       1.0
 */
class AutotweetViewChannels extends AutoTweetDefaultView
{
	const CHANNELS_PAGE_LOAD_LIMIT = 7;

	/**
	 * Runs before rendering the view template, echoing HTML to put before the
	 * view template's generated HTML
	 *
	 * @return void
	 */
	protected function preRender()
	{
		parent::preRender();

		if (!AUTOTWEETNG_FREE)
		{
			$cron_enabled = EParameter::getComponentParam(CAUTOTWEETNG, 'cron_enabled');

			if ((!$cron_enabled) && (F0FModel::getTmpInstance('Channels', 'AutoTweetModel')->getTotal() > self::CHANNELS_PAGE_LOAD_LIMIT))
			{
				echo JText::_('COM_AUTOTWEET_LBL_CHANNELS_TOOMANY_NOTCRON');
			}
		}
	}

	/**
	 * onBrowse.
	 *
	 * @param   string  $tpl  Param
	 *
	 * @return	void
	 */
	protected function onBrowse($tpl = null)
	{
		Extly::initApp(CAUTOTWEETNG_VERSION);
		Extly::loadAwesome();

		return parent::onBrowse($tpl);
	}
}
