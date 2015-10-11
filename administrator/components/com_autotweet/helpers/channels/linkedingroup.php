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

JLoader::import('linkedinbase', dirname(__FILE__));

/**
 * LinkedinGroupChannelHelper - AutoTweet LinkedIn channel for posts to groups.
 *
 * @package     Extly.Components
 * @subpackage  com_autotweet
 * @since       1.0
 */
class LinkedinGroupChannelHelper extends LinkedinBaseChannelHelper
{
	/**
	 * sendMessage.
	 *
	 * @param   string  $message  Params
	 * @param   object  $data     Params
	 *
	 * @return  boolean
	 */
	public function sendMessage($message, $data)
	{
		$title = $data->title;

		// Strlen shorter than JString::strlen for UTF-8  - 2 char languages E.g. Hebrew
		$text = TextUtil::truncString($data->fulltext, self::MAX_CHARS_TEXT);

		$url = $data->url;
		$image_url = $data->image_url;
		$media_mode = $this->getMediaMode();

		$logger = AutotweetLogger::getInstance();
		$logger->log(JLog::INFO, 'sendLinkedinMessage', $message);

		$result = null;

		// Post message and/or media
		switch ($media_mode)
		{
			case 'attachment':
				$post_attach = true;
				$post_msg = false;
				break;
			case 'both':
				$post_msg = true;
				$post_attach = true;
			case 'message':
			default:
				$post_msg = true;
				$post_attach = false;
		}

		try
		{
			$api = $this->getApiInstance();

			if (empty($text))
			{
				$text = JFactory::getConfig()->get('MetaDesc');
			}

			if (empty($text))
			{
				$text = JFactory::getConfig()->get('sitename');
			}

			if (empty($text))
			{
				$text = $title;
			}

			if ($post_attach)
			{
				$response = $api->createPost($this->get('group_id'), $title, $text, $url);
			}
			else
			{
				$response = $api->createPost($this->get('group_id'), $title, $text, $url, $image_url);
			}

			$result = $this->_processResponse($response);
		}
		catch (Exception $e)
		{
			$result = array(
							false,
							$e->getMessage()
			);
		}

		return $result;
	}
}
