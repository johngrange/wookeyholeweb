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
 * AutotweetModelAdvancedattrs
 *
 * @package     Extly.Components
 * @subpackage  com_autotweet
 * @since       1.0
 */
class AutotweetModelAdvancedattrs extends F0FModel
{
	/**
	 * buildQuery
	 *
	 * @param   bool  $overrideLimits  Param
	 *
	 * @return	F0FQuery
	 */
	public function buildQuery($overrideLimits = false)
	{
		$db = $this->getDbo();
		$browse = $this->getState('browse', false);

		$query = F0FQueryAbstract::getNew($db)
			->from($db->quoteName('#__autotweet_advanced_attrs') . '  a');

		if ($browse)
		{
			$query->select('r.*, a.client_id, a.option, a.id as attr_id, a.ref_id attr_ref_id');
		}
		else
		{
			$query->select('a.*');
		}

		$fltOption = $this->getState('option-filter', null, 'cmd');

		if ($fltOption)
		{
			if ($fltOption == 'com_flexicontent')
			{
				$fltOption = 'com_content';
			}

			$query->where($db->qn('a.option') . ' = ' . $db->q($fltOption));
		}

		$fltRefId = $this->getState('ref_id');

		if ($fltRefId)
		{
			$query->where($db->qn('a.ref_id') . ' = ' . $db->q($fltRefId));
		}

		$fltRequestId = $this->getState('request_id', null, 'int');

		if ($fltRequestId)
		{
			$query->where($db->qn('a.request_id') . ' = ' . $db->q($fltRequestId));
		}

		$fltEvergreen = $this->getState('evergreentype_id');

		if ($fltEvergreen)
		{
			$query->where($db->qn('a.evergreentype_id') . ' = ' . $db->q($fltEvergreen));

			if ($fltEvergreen == PlgAutotweetBase::POSTTHIS_YES)
			{
				if ($browse)
				{
					$query->leftJoin($db->quoteName('#__autotweet_requests') . ' r ON (a.request_id = r.id)');
				}
				else
				{
					$query->from($db->quoteName('#__autotweet_requests') . ' r');
					$query->where('a.request_id = r.id');
					$query->where('r.published = 1');
				}
			}
		}

		$fltNextseq = $this->getState('nextseq');

		if ($fltNextseq)
		{
			$query->where($db->qn('a.id') . ' < ' . $db->q($fltNextseq));
		}

		$fltPublishup = $this->getState('publish_up', null, 'date');

		if ($fltPublishup)
		{
			$fltPublishup = $fltPublishup . '%';
			$query->where($db->qn('r.publish_up') . ' LIKE ' . $db->q($fltPublishup));
		}

		$search = $this->getState('search', null);

		if ($search)
		{
			$search = '%' . $search . '%';
			$query->where('(' . $db->qn('r.id') . ' LIKE ' . $db->quote($search)
					. ' OR ' . $db->qn('r.ref_id') . ' LIKE ' . $db->quote($search)
					. ' OR ' . $db->qn('r.description') . ' LIKE ' . $db->quote($search)
					. ' OR ' . $db->qn('r.url') . ' LIKE ' . $db->quote($search) . ')');
		}

		$fltPlugin = $this->getState('plugin', null, 'string');

		if ($fltPlugin)
		{
			$query->where($db->qn('r.plugin') . ' = ' . $db->q($fltPlugin));
		}

		$order = $this->getState('filter_order', 'a.id', 'cmd');
		$dir = $this->getState('filter_order_Dir', 'DESC', 'cmd');
		$query->order($order . ' ' . $dir);

		return $query;
	}

	/**
	 * This method runs before the $data is saved to the $table. Return false to
	 * stop saving.
	 *
	 * @param   array   &$data   Param
	 * @param   JTable  &$table  Param
	 *
	 * @return bool
	 */
	protected function onBeforeSave(&$data, &$table)
	{
		EForm::onBeforeSaveWithParams($data);

		return parent::onBeforeSave($data, $table);
	}
}
