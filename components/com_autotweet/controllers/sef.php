<?php

/**
 * @package     Extly.Components
 * @subpackage  com_autotweet - A powerful social content platform to manage multiple social networks.
 *
 * @author      Prieco S.A. <support@extly.com>
 * @copyright   Copyright (C) 2007 - 2015 Prieco, S.A. All rights reserved.
 * @license     http://http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @link        http://www.extly.com http://support.extly.com
 */
// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * AutotweetControllerSef
 *
 * @package     Extly.Components
 * @subpackage  com_autotweet
 * @since       1.0
 */
class AutotweetControllerSef extends F0FController
{
	/**
	 * route.
	 *
	 * @return	void
	 */
	public function route()
	{
		header('Content-type: text/plain');

		$url = base64_decode($this->input->get('url', 'index.php', 'BASE64'));

		@ob_end_clean();

		$routed_url = JRoute::_($url, false);

		if (RouteHelp::isMultilingual())
		{
			$routed_url = str_replace('/component/autotweet/', '/', $routed_url);
		}

		echo base64_encode($routed_url);
		flush();

		$logger = AutotweetLogger::getInstance();
		$logger->log(JLog::INFO, 'AutotweetControllerSef route: ' . $url . ' = ' . $routed_url);

		JFactory::getApplication()->close();
	}
}
