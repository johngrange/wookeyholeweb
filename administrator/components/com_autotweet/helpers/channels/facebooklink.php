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
 * AutotweetFacebookLinkChannel class.
 * AutoTweet Facebook channel for shared links (different way to post messages...).
 * Posts to the wall of profiles, groups, pages.
 *
 * @package     Extly.Components
 * @subpackage  com_autotweet
 * @since       1.0
 */
class FacebookLinkChannelHelper extends FacebookBaseChannelHelper
{
	/**
	 * sendMessage.
	 *
	 * @param   string  $message  Params
	 * @param   object  $data     Params
	 *
	 * @return	boolean
	 */
	public function sendMessage($message, $data)
	{
		if (($this->channel->params->get('open_graph_features')) && ($this->isUserProfile()))
		{
			return $this->sendFacebookOG($message, $data->title, $data->fulltext, $data->url, $data->org_url, $data->image_url, $this->getMediaMode(), $data);
		}

		$title = $data->title;
		$text = $data->fulltext;
		$url = $data->url;

		if (empty($url))
		{
			$url = $data->org_url;
		}

		if (empty($url))
		{
			$url = RouteHelp::getInstance()->getRoot();
		}

		$org_url = $data->org_url;

		if (empty($org_url))
		{
			$org_url = $url;
		}

		$image_url = $data->image_url;
		$media_mode = $this->getMediaMode();

		$url_comps = parse_url($org_url);
		$actionlink_text = $url_comps['host'];

		$actions = array();
		$actions['name'] = $actionlink_text;
		$actions['link'] = $org_url;

		$logger = AutotweetLogger::getInstance();
		$logger->log(JLog::INFO, 'sendFacebookMessage', $message);

		$fb_id = $this->getFbChannelId();
		$fb_token = $this->get('fbchannel_access_token');

		// Media mode is not used !!

		$result = null;

		$status_type = array(
			'value'     => 'wall_post'
		);

		$title = TextUtil::truncString($title, self::MAX_CHARS_NAME);

		// Link object: /user/links
		$arguments = array(
			'message' => $message,
			'link' => $url,
			'name' => $title,
			'caption' => $actionlink_text,
			'actions' => json_encode($actions),

			'access_token' => $fb_token,
			'type' => 'link',
			'created_time' => JFactory::getDate()->toISO8601(),
			'status_type' => json_encode($status_type)
		);

		$isUserProfile = $this->isUserProfile();

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

		$target_id = $data->xtform->get('target_id');

		if ((EParameter::getComponentParam(CAUTOTWEETNG, 'targeting', false)) && ($target_id))
		{
			$this->addTargetArguments($arguments, $target_id);
		}

		try
		{
			// Simulated
			if ($this->channel->params->get('use_own_api') == 0)
			{
				$this->getApiInstance()->api("/me/permissions");
				$result = array(
					true,
					JText::_('COM_AUTOTWEET_VIEW_SIMULATED_OK')
				);
			}
			else
			{
				$result = $this->getApiInstance()->api("/{$fb_id}/links", 'post', $arguments);
				$msg = 'Facebook id: ' . $result['id'];
				$result = array(
								true,
								$msg
				);
			}
		}
		catch (Exception $e)
		{
			$result = array(
							false,
							$e->getCode() . ' - ' . $e->getMessage()
			);
		}

		return $result;
	}
}
