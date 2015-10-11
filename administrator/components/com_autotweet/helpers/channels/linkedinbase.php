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

JLoader::import('channel', dirname(__FILE__));

require_once dirname(__FILE__) . '/OAuth/OAuth.php';
JLoader::register('LinkedIn', dirname(__FILE__) . '/Simple-LinkedIn/linkedin_3.2.0.class.php');

/**
 * LinkedinBaseChannelHelper - AutoTweet LinkedIn channel base class.
 *
 * @package     Extly.Components
 * @subpackage  com_autotweet
 * @since       1.0
 */
abstract class LinkedinBaseChannelHelper extends ChannelHelper
{
	// 200 * 90% - To be sure
	const MAX_CHARS_TITLE = 180;

	// Max - 10% safety
	const MAX_CHARS_TEXT = 360;

	const MAX_CHARS_DESC = 256;

	protected $linkedin = null;

	/**
	 * getApiInstance
	 *
	 * @return	object
	 */
	protected function getApiInstance()
	{
		if (!$this->linkedin)
		{
			$API_CONFIG = array(
							'appKey' => $this->get('api_key'),
							'appSecret' => $this->get('secret_key'),
							'callbackUrl' => null
			);

			JLoader::load('LinkedIn');
			$this->linkedin = new SimpleLinkedIn\LinkedIn($API_CONFIG);

			$ACCESS_TOKEN = array(
							'oauth_token' => $this->get('oauth_user_token'),
							'oauth_token_secret' => $this->get('oauth_user_secret')
			);
			$this->linkedin->setTokenAccess($ACCESS_TOKEN);
		}

		return $this->linkedin;
	}

	/**
	 * _processResponse
	 *
	 * @param   array  $response  Params
	 *
	 * @return	array
	 */
	protected function _processResponse($response)
	{
		$result = array(
			false,
			'Unknown'
		);

		if ($response['success'] === true)
		{
			$url = null;

			if (isset($response['info']['url']))
			{
				$url = ' - ' . $response['info']['url'];
			}

			$result = array(
				true,
				'OK' . $url
			);

			return $result;
		}

		$http_code = $response['info']['http_code'];

		if ($http_code == 202)
		{
			$result = array(
							true,
							'202 - Accepted / waiting for approval'
			);

			return $result;
		}

		$linkedin = null;

		if ((is_array($response)) && (isset($response['linkedin'])))
		{
			$linkedin = SimpleLinkedIn\LinkedIn::xmlToArray($response['linkedin']);
		}

		if ((is_array($linkedin)) && (isset($linkedin['error']['children']['message']['content'])))
		{
			$msg = $linkedin['error']['children']['message']['content'];
			$msg = $http_code . ' - ' . $msg;
		}
		else
		{
			$msg = $http_code . ' - ' . JText::_('COM_AUTOTWEET_HTTP_ERR_' . $response['info']['http_code']);
		}

		$result = array(
			false,
			$msg
		);

		return $result;
	}
}
