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

JLoader::import('facebookbase', dirname(__FILE__));

/**
 * FacebookChannelHelper class.
 * AutoTweet Facebook channel for wall posts.
 * Posts to the wall of profiles, groups, pages, events
 *
 * @package     Extly.Components
 * @subpackage  com_autotweet
 * @since       1.0
 */
class FacebookChannelHelper extends FacebookBaseChannelHelper
{
	/**
	 * sendMessage.
	 *
	 * @param   string  $message  Params
	 * @param   object  $data     Params
	 *
	 * @return	booleans
	 */
	public function sendMessage($message, $data)
	{
		$isUserProfile = $this->isUserProfile();

		if (($this->channel->params->get('open_graph_features')) && ($isUserProfile))
		{
			return $this->sendFacebookOG($message, $data->title, $data->fulltext, $data->url, $data->org_url, $data->image_url, $this->getMediaMode(), $data);
		}

		$title = $data->title;
		$text = $data->fulltext;
		$url = $data->url;
		$org_url = $data->org_url;
		$image_url = $data->image_url;
		$media_mode = $this->getMediaMode();

		$logger = AutotweetLogger::getInstance();
		$logger->log(JLog::INFO, 'sendFacebookMessage', $message);

		$fb_id = $this->getFbChannelId();
		$fb_token = $this->get('fbchannel_access_token');

		// Includes a workaround for Facebook ?ref=nf url extension problem and short urls
		// if API bug is fixed, replace all org_url variables by url
		$result = null;

		// Post message and/or attachment
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

		if (empty($org_url))
		{
			$post_attach = false;
		}

		if ((empty($text)) && (empty($image_url)))
		{
			$post_attach = false;
		}

		// Attachment: do also not post when text and image are empty
		if ($post_attach)
		{
			// Extract data for action link
			$url_comps = parse_url($org_url);
			$actionlink_text = $url_comps['host'];

			$actions = array();
			$actions['name'] = $actionlink_text;
			$actions['link'] = $org_url;

			$status_type = array(
							'value'     => 'wall_post'
			);

			$title = TextUtil::truncString($title, self::MAX_CHARS_NAME);

			$arguments = array(
							'link' => $org_url,
							'name' => $title,
							'caption' => $actionlink_text,
							'description' => $text,
							'actions' => json_encode($actions),
							'access_token' => $fb_token,
							'status_type' => json_encode($status_type)
			);

			if ($isUserProfile)
			{
				$privacy = $this->get('sharedwith', 'EVERYONE');
				$privacy = array('value' => $privacy);
				$arguments['privacy'] = json_encode($privacy);
			}

			// Include image tag only, when image url is not empty to avoid error "... must have a valid src..."
			if (!empty($image_url))
			{
				$arguments['picture'] = $image_url;
			}

			// Message
			if ($post_msg)
			{
				$arguments['message'] = $message;
			}
		}
		else
		{
			$arguments = array(
							'message' => $message,
							'access_token' => $fb_token
			);
		}

		$target_id = $data->xtform->get('target_id');

		if ((EParameter::getComponentParam(CAUTOTWEETNG, 'targeting', false)) && ($target_id))
		{
			$this->addTargetArguments($arguments, $target_id);
		}

		try
		{
			$fbapi = $this->getApiInstance();

			// Simulated
			if ($this->channel->params->get('use_own_api') == 0)
			{
				$fbapi->api("/me/permissions");
				$result = array(
								true,
								JText::_('COM_AUTOTWEET_VIEW_SIMULATED_OK')
				);
			}
			else
			{
				$result = $fbapi->api("/{$fb_id}/feed", 'post', $arguments);
				$msg = 'Facebook id: ' . $result['id'];
				$result = array(
								true,
								$msg
				);
			}
		}
		catch (Exception $e)
		{
			$code = $e->getCode();
			$msg = $code . ' - ' . $e->getMessage();

			$donot_fberror02 = EParameter::getComponentParam(CAUTOTWEETNG, 'donot_fberror02', 0);
			$donottrack_error = (($donot_fberror02) && (($code == 0) || ($code == 2)));

			if ($donottrack_error)
			{
				$logger = AutotweetLogger::getInstance();
				$logger->log(JLog::ERROR, 'DONOT_FBERROR02: ' . $msg);

				$result = array(
								true,
								$msg
				);
			}
			else
			{
				$result = array(
								false,
								$msg
				);
			}
		}

		return $result;
	}
}
