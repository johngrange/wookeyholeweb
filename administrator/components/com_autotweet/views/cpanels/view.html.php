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
 * AutotweetViewCpanels
 *
 * @package     Extly.Components
 * @subpackage  com_autotweet
 * @since       1.0
 */
class AutotweetViewCpanels extends AutotweetDefaultView
{
	/**
	 * onBrowse
	 *
	 * @param   string  $tpl  Param
	 *
	 * @return	bool
	 */
	protected function onBrowse($tpl = null)
	{
		Extly::loadAwesome();

		GridHelper::loadComponentInfo($this);
		GridHelper::loadStats($this);
		GridHelper::loadStatsTimeline($this);

		$document = JFactory::getDocument();
		$document->addScript('//cdnjs.cloudflare.com/ajax/libs/d3/3.5.5/d3.min.js');
		$document->addScript('//cdnjs.cloudflare.com/ajax/libs/nvd3/1.7.0/nv.d3.min.js');
		$document->addStyleSheet('//cdnjs.cloudflare.com/ajax/libs/nvd3/1.7.0/nv.d3.min.css');

		// Get component parameter - Offline mode
		$version_check = EParameter::getComponentParam(CAUTOTWEETNG, 'version_check', 1);
		$this->assign('version_check', $version_check);
		
		$platform = F0FPlatform::getInstance();

		if ( ($version_check) && ($platform->isBackend()) )
		{
			$file = EHtml::getRelativeFile('js', 'com_autotweet/liveupdate.min.js');

			if ($file)
			{
				$dependencies = array();
				$dependencies['liveupdate'] = array('extlycore');
				Extly::initApp(CAUTOTWEETNG_VERSION, $file, $dependencies);
			}
		}

		parent::onBrowse($tpl);
	}

	/**
	 * generateTimeline
	 *
	 * @return	string
	 */
	protected function generateTimeline()
	{
		$timeline = $this->get('timeline');
		$values = array();
		$states = array('success', 'cronjob', 'approve', 'cancelled', 'error');

		foreach ($states as $state)
		{
			$values[$state] = array();
		}

		foreach ($timeline as $row)
		{
			$date = $row->postdate;
			$pubstate = $row->pubstate;
			$counter = $row->counter;

			$values[$pubstate][$date] = $counter;

			$others = array_diff($states, array($pubstate));

			foreach ($others as $state)
			{
				if ( (!isset($values[$state][$date])) )
				{
					$values[$state][$date] = 0;
				}
			}
		}

		$state_success = new StdClass;
		$state_success->key = SelectControlHelper::getTextForEnum('success');
		$state_success->values = $this->_listOfObjects($values['success']);

		$state_cronjob = new StdClass;
		$state_cronjob->key = SelectControlHelper::getTextForEnum('cronjob');
		$state_cronjob->values = $this->_listOfObjects($values['cronjob']);

		$state_approve = new StdClass;
		$state_approve->key = SelectControlHelper::getTextForEnum('approve');
		$state_approve->values = $this->_listOfObjects($values['approve']);

		$state_cancelled = new StdClass;
		$state_cancelled->key = SelectControlHelper::getTextForEnum('cancelled');
		$state_cancelled->values = $this->_listOfObjects($values['cancelled']);

		$state_error = new StdClass;
		$state_error->key = SelectControlHelper::getTextForEnum('error');
		$state_error->values = $this->_listOfObjects($values['error']);

		$result = array($state_success, $state_cronjob, $state_approve, $state_cancelled, $state_error);

		return $result;
	}

	/**
	 * _listOfObjects
	 *
	 * @param   array  $values  Param
	 *
	 * @return	array
	 */
	private function _listOfObjects($values)
	{
		$result = array();

		foreach ($values as $key => $value)
		{
			$o = array(
				'x' => (int) JFactory::getDate($key)->toUnix(),
				'y' => (int) $value
			);

			$result[] = (object) $o;
		}

		return $result;
	}
}
