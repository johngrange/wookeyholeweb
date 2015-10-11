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
 * Helper for posts form AutoTweet to channels (twitter, Facebook, ...)
 *
 * @package     AutoTweetNG.CLI
 * @subpackage  com_autotweet
 * @since       2.5
 */
class CronjobHelper
{
	protected static $instance = null;

	protected $cron_enabled = 0;

	// Posts per job
	protected $max_posts = 1;

	/**
	 * Run the job
	 *
	 */
	protected function __construct()
	{
		JLoader::register('PostHelper', JPATH_AUTOTWEET_HELPERS . '/post.php');

		// Cronjob params
		$this->cron_enabled = EParameter::getComponentParam(CAUTOTWEETNG, 'cron_enabled', false);
		$this->max_posts = EParameter::getComponentParam(CAUTOTWEETNG, 'max_posts', 1);

		// Wrapper for mbstrings
		// include_once dirname(__FILE__) . '/mbstringwrapper/mbstringwrapper.php';
	}

	/**
	 * getInstance
	 *
	 * @return  object
	 */
	public static function getInstance()
	{
		if (!self::$instance)
		{
			self::$instance = new CronjobHelper;
		}

		return self::$instance;
	}

	/**
	 * postMessages
	 *
	 * @return  void
	 */
	public function publishPosts()
	{
		if ($this->cron_enabled)
		{
			if (AUTOTWEETNG_JOOCIAL)
			{
				$now = JFactory::getDate();

				if (VirtualManager::getInstance()->isWorking($now))
				{
					PostHelper::publishCronjobPosts($this->max_posts);
				}
				else
				{
					$logger = AutotweetLogger::getInstance();
					$logger->log(JLog::INFO, 'CronjobHelper::publishPosts - VM not working now ' . $now->toISO8601(true));
				}
			}
			else
			{
				PostHelper::publishCronjobPosts($this->max_posts);
			}
		}
	}

	/**
	 * contentPolling
	 *
	 * @return  void
	 */
	public function contentPolling()
	{
		$this->_executePlgContentPolling('autotweetcontent');
		$this->_executePlgContentPolling('autotweetk2');
		$this->_executePlgContentPolling('autotweetohanah');
	}

	/**
	 * _executePlgContentPolling
	 *
	 * @param   string  $pluginName  Param
	 *
	 * @return  void
	 */
	private function _executePlgContentPolling($pluginName)
	{
		$plugin = JPluginHelper::getPlugin('system', $pluginName);

		if (empty($plugin))
		{
			return;
		}

		JPluginHelper::importPlugin('system', $plugin->name);
		$className = 'PlgSystem' . $plugin->name;

		if (class_exists($className))
		{
			$dispatcher = JDispatcher::getInstance();
			$plugin = new $className($dispatcher, (array) $plugin);
			$plugin->onContentPolling();
		}
	}
}
