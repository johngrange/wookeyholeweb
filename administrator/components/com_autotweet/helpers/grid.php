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
 * GridHelper
 *
 * @package     Extly.Components
 * @subpackage  com_autotweet
 * @since       1.0
 */
class GridHelper
{
	/**
	 * Method to create a clickable icon to change the state of an item
	 *
	 * @param   mixed    $value     Either the scalar value or an object (for backward compatibility, deprecated)
	 * @param   integer  $i         The index
	 * @param   bool     $isModule  Param
	 *
	 * @return  string
	 */
	public static function pubstates($value, $i, $isModule = false)
	{
		if (is_object($value))
		{
			$value = $value->pubstate;
		}

		return SelectControlHelper::getTextForEnum($value, true, $isModule);
	}

	/**
	 * loadComponentInfo
	 *
	 * @param   object  $view  Param
	 *
	 * @return  void
	 */
	public static function loadComponentInfo($view)
	{
		// Load the model
		$info = F0FModel::getTmpInstance('Update', 'AutoTweetModel');

		$view->assign('comp', $info->getComponentInfo());
		$view->assign('plugins', $info->getPluginInfo());
		$view->assign('thirdparty', $info->getThirdpartyInfo());
		$view->assign('sysinfo', $info->getSystemInfo());
	}

	/**
	 * loadStats
	 *
	 * @param   object  $view  Param
	 *
	 * @return  void
	 */
	public static function loadStats($view)
	{
		// 30 days = 30 * 24 * 60 * 60
		$time_intval = 2592000;

		// Calculate date for interval
		$now = JFactory::getDate();
		$check_date = $now->toUnix();
		$check_date = $check_date - $time_intval;
		$check_date = JFactory::getDate($check_date);

		$postsModel = F0FModel::getTmpInstance('Posts', 'AutoTweetModel');
		$postsModel->set('after_date', $check_date->toSql());
		$stats = $postsModel->getStatsTotal();

		$success = isset($stats['success']->count) ? $stats['success']->count : 0;
		$error = isset($stats['error']->count) ? $stats['error']->count : 0;
		$approve = isset($stats['approve']->count) ? $stats['approve']->count : 0;
		$cronjob = isset($stats['cronjob']->count) ? $stats['cronjob']->count : 0;
		$cancelled = isset($stats['cancelled']->count) ? $stats['cancelled']->count : 0;

		$postsTotal = $success +
			$error +
			$approve +
			$cronjob +
			$cancelled;

		$view->assign('success', $success);
		$view->assign('error', $error);
		$view->assign('approve', $approve);
		$view->assign('cronjob', $cronjob);
		$view->assign('cancelled', $cancelled);
		$view->assign('total', $postsTotal);

		if ($postsTotal)
		{
			$view->assign('p_success', round($success / $postsTotal * 100));
			$view->assign('p_error', round($error / $postsTotal * 100));
			$view->assign('p_approve', round($approve / $postsTotal * 100));
			$view->assign('p_cronjob', round($cronjob / $postsTotal * 100));
			$view->assign('p_cancelled', round($cancelled / $postsTotal * 100));
			$view->assign('p_total', $postsTotal);
		}
		else
		{
			$view->assign('p_success', 0);
			$view->assign('p_error', 0);
			$view->assign('p_approve', 0);
			$view->assign('p_cronjob', 0);
			$view->assign('p_cancelled', 0);
			$view->assign('p_total', 0);
		}

		$requestModel = F0FModel::getTmpInstance('Requests', 'AutoTweetModel');
		$requestModel->savestate(false);
		$requestModel->set('after_date', $check_date->toSql());
		$requestsTotal = $requestModel->getTotal();

		$total = $postsTotal + $requestsTotal;

		$view->assign('requests', $requestsTotal);
		$view->assign('posts', $postsTotal);

		if ($total)
		{
			$view->assign('p_requests', round($requestsTotal / $total * 100));
			$view->assign('p_posts', round($postsTotal / $total * 100));
		}
		else
		{
			$view->assign('p_requests', 0);
			$view->assign('p_posts', 0);
		}
	}

	/**
	 * loadStatsTimeline
	 *
	 * @param   object  $view  Param
	 *
	 * @return  void
	 */
	public static function loadStatsTimeline($view)
	{
		// 30 days = 30 * 24 * 60 * 60
		$time_intval = 2592000;

		// Calculate date for interval
		$now = JFactory::getDate();
		$check_date = $now->toUnix();
		$check_date = $check_date - $time_intval;
		$check_date = JFactory::getDate($check_date);

		$postsModel = F0FModel::getTmpInstance('Posts', 'AutoTweetModel');
		$postsModel->set('after_date', $check_date->toSql());
		$stats = $postsModel->getStatsTimeline();

		$view->assign('timeline', $stats);
	}
}
