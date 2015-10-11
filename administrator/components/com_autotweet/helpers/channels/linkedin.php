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
 * LinkedinChannelHelper class.
 *
 * @package     Extly.Components
 * @subpackage  com_autotweet
 * @since       1.0
 */
class LinkedinChannelHelper extends LinkedinBaseChannelHelper
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
		$text = $data->fulltext;
		$url = $data->url;
		$image_url = $data->image_url;
		$media_mode = $this->getMediaMode();

		$logger = AutotweetLogger::getInstance();
		$logger->log(JLog::INFO, 'sendLinkedinMessage', $message);

		$result = null;
		$content = array();

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
				break;
			case 'message':
			default:
				$post_msg = true;
				$post_attach = false;
		}

		// Media: do also not post when text and image are empty
		if ($post_attach && !empty($title) && !empty($url))
		{
			$title = TextUtil::truncString($title, self::MAX_CHARS_TITLE);
			$text = TextUtil::truncString($text, self::MAX_CHARS_TEXT);

			// Prepare content
			$content['title'] = $title;
			$content['submitted-url'] = $url;
			$content['submitted-image-url'] = $image_url;
			$content['description'] = $text;
		}

		// Message
		if ($post_msg)
		{
			$content['comment'] = $message;
		}

		// Default for visibility
		$private = false;

		try
		{
			$api = $this->getApiInstance();
			$response = $api->share('new', $content, $private);
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
