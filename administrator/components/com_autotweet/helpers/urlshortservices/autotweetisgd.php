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

// AutoTweet is.gd url short service.

JLoader::import('autotweetshortservice', dirname(__FILE__));

/**
 * AutotweetIsgdService
 *
 * @package     Extly.Components
 * @subpackage  com_autotweet
 * @since       1.0
 */
class AutotweetIsgdService extends AutotweetShortservice
{
	/**
	 * Construct
	 *
	 * @param   array  $data  Param
	 */
	public function __construct($data)
	{
		parent::__construct($data);
	}

	/**
	 * getShortURL
	 *
	 * @param   string  $long_url  Param.
	 *
	 * @return	string
	 */
	public function getShortUrl($long_url)
	{
		return $this->callSimpleService('http://is.gd/api.php?longurl=', $long_url);
	}
}
