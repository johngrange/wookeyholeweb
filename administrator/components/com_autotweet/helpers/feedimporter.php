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
 * FeedImporterHelper class.
 *
 * @package     Extly.Components
 * @subpackage  com_autotweet
 * @since       1.0
 */
class FeedImporterHelper
{
	const ALWAYS_EXPRESSION = '* * * * *';

	/**
	 * import
	 *
	 * @param   object  &$feed  Params
	 *
	 * @return	void
	 */
	public function import(&$feed)
	{
		if (isset($feed->params))
		{
			$feed->xtform = EForm::paramsToRegistry($feed);
		}

		$import_frequency = $feed->xtform->get('import_frequency', self::ALWAYS_EXPRESSION);

		if ($import_frequency != self::ALWAYS_EXPRESSION)
		{
			$automators = F0FModel::getTmpInstance('Automators', 'AutoTweetModel');
			$key = 'feed-' . $feed->id;
			$lastexec = $automators->lastRun($key);

			if ($lastexec->toUnix() < JFactory::getDate()->toUnix())
			{
				$lastexec = 'now';
			}

			$next = TextUtil::nextScheduledDate($import_frequency, $lastexec);

			$logger = AutotweetLogger::getInstance();
			$logger->log(JLog::INFO, "Feed import: lastRunCheck {$lastexec} ({$key}, 0, {$next})");

			if (!$automators->lastRunCheck($key, 0, $next))
			{
				$logger->log(JLog::INFO, "Feed import: lastRunCheck skipped!");

				return;
			}
		}

		$result = new StdClass;
		$result->added_items = 0;

		$simplePie = $this->_createSimplePie($feed);

		if ($simplePie->get_type() & SIMPLEPIE_TYPE_NONE)
		{
			throw new Exception(JText::sprintf('COM_AUTOTWEET_FEED_UNABLE_TO_PROCESS', $feed->xtform->get('title') . ' (' . $feed->xtform->get('feed') . ')'));
		}
		elseif ($simplePie->error)
		{
			throw new Exception("SimplePie error (ID={$feed->id}): " . $simplePie->error . ' for ' . $feed->xtform->get('title') . ' (' . $feed->xtform->get('feed') . ')');
		}

		$title = $simplePie->get_title();

		$c = (int) $feed->xtform->get('import_limit');
		$items = $simplePie->get_items(0, $c);

		$result->title = $title;
		$result->items = $items;

		$simplePie->__destruct();
		unset($items, $simplePie);

		// End SimplePie processing

		return $result;
	}

	/**
	 * _createSimplePie
	 *
	 * @param   string  &$feed  Params
	 *
	 * @return	object
	 */
	private function _createSimplePie(&$feed)
	{
		include_once JPATH_AUTOTWEET . '/libs/SimplePie_autoloader.php';

		// Process the feed with SimplePie
		$simplePie = new SimplePie;
		$simplePie->set_feed_url($feed->xtform->get('url'));
		$simplePie->set_stupidly_fast(true);
		$simplePie->enable_order_by_date(true);

		if ($feed->xtform->get('encoding', 'utf-8'))
		{
			$simplePie->set_input_encoding($feed->xtform->get('encoding'));
		}

		if ($feed->xtform->get('force_fsockopen'))
		{
			$simplePie->force_fsockopen(true);
		}

		$use_sp_cache = EParameter::getComponentParam(CAUTOTWEETNG, 'use_sp_cache', true);

		if (($use_sp_cache) && (is_writable(JPATH_CACHE)))
		{
			$simplePie->set_cache_location(JPATH_CACHE);
			$simplePie->enable_cache(true);
		}
		else
		{
			$simplePie->enable_cache(false);
		}

		$set_sp_timeout = EParameter::getComponentParam(CAUTOTWEETNG, 'set_sp_timeout', 10);

		if ($set_sp_timeout)
		{
			$simplePie->set_timeout((int) $set_sp_timeout);
		}

		$simplePie->init();

		return $simplePie;
	}

	/**
	 * loadAjaxImporter
	 *
	 * @param   object  $view  Param
	 *
	 * @return	void
	 */
	public static function loadAjaxImporter($view)
	{
		$ajax_import = EParameter::getComponentParam(CAUTOTWEETNG, 'ajax_import', true);
		$view->assignRef('ajax_import', $ajax_import);

		if ($ajax_import)
		{
			$file = EHtml::getRelativeFile('js', 'com_autotweet/import.min.js');

			if ($file)
			{
				$dependencies = array();
				$dependencies['import'] = array('extlycore');

				Extly::initApp(CAUTOTWEETNG_VERSION, $file, $dependencies);
			}
		}
		else
		{
			Extly::initApp(CAUTOTWEETNG_VERSION);
		}
	}
}
