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

F0FModel::getTmpInstance('Plugins', 'AutoTweetModel');

$result = array();

if ($count = count($this->items))
{
	foreach ($this->items as $item)
	{
		$native_object = json_decode($item->native_object);
		$has_error = ((isset($native_object->error)) && ($native_object->error));
		$description = TextUtil::truncString($item->description, AutotweetDefaultView::MAX_CHARS_TITLE_SCREEN, true);

		$elem = array(
			'id' => $item->id,
			'title' => $description,
			'start' => JHtml::_('date', $item->publish_up, JText::_('COM_AUTOTWEET_DATE_FORMAT')),
			'className' => ($item->published ?
					($has_error ?  'req-error' : 'req-success') :
					($has_error ? 'req-warning' : 'req-info')),

			'checked_out' => ($item->checked_out != 0),
			'publish_up' => JHtml::_('date', $item->publish_up, JText::_('COM_AUTOTWEET_DATE_FORMAT')),
			'description' => $description,
			'plugin' => AutoTweetModelPlugins::getSimpleName($item->plugin),
			'published' => $item->published
		);

		if (!empty($item->url))
		{
			$elem['url'] = TextUtil::renderUrl($item->url);
		}

		if (!empty($item->image_url))
		{
			$elem['image_url'] = TextUtil::renderUrl($item->image_url);
		}

		$result[] = $elem;
	}
}

echo EJSON_START . json_encode($result) . EJSON_END;
