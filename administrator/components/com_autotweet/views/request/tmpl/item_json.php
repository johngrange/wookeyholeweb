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

if (AUTOTWEETNG_JOOCIAL)
{
	$params = AdvancedattrsHelper::getAdvancedAttrByReq($this->item->id);
	AutotweetBaseHelper::convertUTCLocalAgenda($params->agenda);

	if (!empty($params->image))
	{
		$this->item->image_url = $params->image;
		$params->image = null;
	}

	$this->item->autotweet_advanced_attrs = $params;
}

echo EJSON_START . json_encode($this->item) . EJSON_END;
