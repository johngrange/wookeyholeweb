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
 * AutotweetTableFeed
 *
 * @package     Extly.Components
 * @subpackage  com_autotweet
 * @since       1.0
 */
class AutotweetTableFeed extends F0FTable
{
	/**
	 * Instantiate the table object
	 *
	 * @param   string     $table  Param
	 * @param   string     $key    Param
	 * @param   JDatabase  &$db    The Joomla! database object
	 */
	public function __construct($table, $key, &$db)
	{
		parent::__construct('#__autotweet_feeds', 'id', $db);

		$this->_columnAlias = array(
						'enabled' => 'published',
						'created_on' => 'created',
						'modified_on' => 'modified',
						'locked_on' => 'checked_out_time',
						'locked_by' => 'checked_out'
		);
	}

	/**
	 * Checks the record for validity
	 *
	 * @return  int  True if the record is valid
	 */
	public function check()
	{
		$registry = new JRegistry;
		$registry->loadString($this->params);
		$url = $registry->get('url');

		if (filter_var($url, FILTER_VALIDATE_URL) === false)
		{
			$this->setError(JText::sprintf('COM_AUTOTWEET_FEED_INVALID_URL', $url));

			return false;
		}

		return true;
	}
}
