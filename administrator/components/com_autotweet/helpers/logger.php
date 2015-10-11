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

JLoader::import('extly.extlyframework');

/**
 * AutotweetLogger
 *
 * @package     Extly.Components
 * @subpackage  com_autotweet
 * @since       1.0
 */
class AutotweetLogger
{
	private static $_instance = null;

	/**
	 * getInstance
	 *
	 * @return	object
	 */
	public static function &getInstance()
	{
		if (!self::$_instance)
		{
			$log_level = EParameter::getComponentParam(CAUTOTWEETNG, 'log_level', JLog::ERROR);
			$log_mode = EParameter::getComponentParam(CAUTOTWEETNG, 'log_mode', ELog::LOG_MODE_SCREEN);

			self::$_instance = new ELog($log_level, $log_mode);
		}

		return self::$_instance;
	}
}
