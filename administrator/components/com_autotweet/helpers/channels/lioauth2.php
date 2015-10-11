<?php

/**
 * @package     Extly.Components
 * @subpackage  com_autotweet - AutoTweetNG posts content to social channels (LiOAuth2, Facebook, LinkedIn, etc).
 *
 * @author      Prieco S.A. <support@extly.com>
 * @copyright   Copyright (C) 2007 - 2015 Prieco, S.A. All rights reserved.
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @link        http://www.extly.com http://support.extly.com
 */
// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * AutoTweet LiOAuth2 channel.
**/

JLoader::import('channel', dirname(__FILE__));

/**
 * LiOAuth2ChannelHelper
 *
 * @package     Extly.Components
 * @subpackage  com_autotweet
 * @since       1.0
 */
class LiOAuth2ChannelHelper extends ChannelHelper
{
	// 200 * 90% - To be sure
	const MAX_CHARS_TITLE = 180;

	// Max - 10% safety
	const MAX_CHARS_TEXT = 360;

	const MAX_CHARS_DESC = 256;

	protected $lioauth2Client = null;

	protected $lioauth2Callback = null;

	protected $consumer_key = null;

	protected $consumer_secret = null;

	protected $access_token = null;

	protected $access_secret = null;

	protected $expires_in = null;

	protected $is_auth = null;

	protected $me = null;

	/**
	 * ChannelHelper.
	 *
	 * @param   object  $channel  Params.
	 */
	public function __construct($channel)
	{
		parent::__construct($channel);

		require_once dirname(__FILE__) . '/OAuth/OAuth.php';
		require_once dirname(__FILE__) . '/Simple-LinkedIn/linkedin_3.2.0.class.php';

		if ($channel->id)
		{
			$this->consumer_key = $this->channel->params->get('consumer_key');
			$this->consumer_secret = $this->channel->params->get('consumer_secret');
			$this->access_token = $this->channel->params->get('access_token');
			$this->access_secret = $this->channel->params->get('access_secret');
		}
	}

	/**
	 * Internal service functions
	 *
	 * @param   string  $callbackUrl  Param
	 *
	 * @return	object
	 */
	protected function getApiInstance($callbackUrl = null)
	{
		if ((!$this->lioauth2Client) || ($this->lioauth2Callback != $callbackUrl))
		{
			$this->lioauth2Callback = $callbackUrl;

			$API_CONFIG = array(
					'appKey' => $this->consumer_key,
					'appSecret' => $this->consumer_secret,
					'callbackUrl' => $callbackUrl
			);

			$this->lioauth2Client = new SimpleLinkedIn\LinkedIn($API_CONFIG);

			if ($this->access_token)
			{
				$ACCESS_TOKEN = array(
						'oauth_token' => $this->access_token,
						'oauth_token_secret' => $this->access_secret
				);
				$this->lioauth2Client->setTokenAccess($ACCESS_TOKEN);
			}
		}

		return $this->lioauth2Client;
	}

	/**
	 * isAuth()
	 *
	 * @return	mixed
	 */
	public function isAuth()
	{
		if (empty($this->access_token))
		{
			$this->access_token = null;

			return false;
		}

		$result = false;

		try
		{
			$response = $this->getApiInstance()->profile2('~:(id,first-name,last-name,headline,public-profile-url)');

			if ($response['success'] === true)
			{
				$xml = $response['linkedin'];
				$user = simplexml_load_string($xml);
				$user = json_decode(json_encode($user));
				$url = $user->{'public-profile-url'};

				$result = array(
						'status' => true,
						'error_message' => 'Ok!',
						'user' => $user,
						'url' => $url
				);
			}
			else
			{
				$msg = $response['error'] . ' '
					. $response['info']['http_code'] . ' '
					. JText::_('COM_AUTOTWEET_HTTP_ERR_' . $response['info']['http_code']);

				$result = array(
						'status' => false,
						'error_message' => $msg
				);

				$logger = AutotweetLogger::getInstance();
				$logger->log(JLog::ERROR, $msg);
			}
		}
		catch (Exception $e)
		{
			$logger = AutotweetLogger::getInstance();
			$logger->log(JLog::ERROR, $e->getMessage());

			// Invalidating access_token
			$ch->setToken($this->channel->id, 'access_token', '');
		}

		return $result;
	}

	/**
	 * getAuthorizationUrl
	 *
	 * @return	string
	 */
	public function getAuthorizationUrl()
	{
		if (empty($this->consumer_key))
		{
			return null;
		}

		require_once dirname(__FILE__) . '/../../controllers/lioauth2channels.php';

		$redirectUri = AutotweetControllerLiOAuth2Channels::getCallbackUrl($this->channel->id);
		$this->getApiInstance($redirectUri);

		return $this->lioauth2Client->getAuthorizationUrl($this->generateState());
	}

	/**
	 * authenticate
	 *
	 * @param   string  $code   Param
	 * @param   string  $state  Param
	 *
	 * @return	bool
	 */
	public function authenticate($code, $state)
	{
		if ($state != $this->generateState())
		{
			throw new Exception('LinkedIn Channel - invalid state.');
		}

		$redirectUri = AutotweetControllerLiOAuth2Channels::getCallbackUrl($this->channel->id);
		$this->getApiInstance($redirectUri);
		$access_token = $this->lioauth2Client->getAccessToken($code, $state);

		$ch = F0FTable::getAnInstance('Channel', 'AutoTweetTable');

		if ( ($access_token) && (isset($access_token->access_token)) && (isset($access_token->expires_in)) )
		{
			$ch->setToken($this->channel->id, 'access_token', $access_token->access_token);
			$ch->setToken($this->channel->id, 'access_secret', '');
			$ch->setToken($this->channel->id, 'expires_in', $access_token->expires_in);

			$now = JFactory::getDate()->toUnix() + $access_token->expires_in;
			$now = JFactory::getDate($now)->toSql();
			$ch->setToken($this->channel->id, 'expires_date', $now);

			return true;
		}
		else
		{
			JFactory::getApplication()->enqueueMessage('Unable to retrieve access token.', 'error');

			if ( ($access_token) && (isset($access_token->error)) && (isset($access_token->error_description)))
			{
				JFactory::getApplication()->enqueueMessage($access_token->error . ' - ' . $access_token->error_description, 'error');
			}

			// Invalidating access_token
			$ch->setToken($this->channel->id, 'access_token', '');
			$ch->setToken($this->channel->id, 'access_secret', '');
			$ch->setToken($this->channel->id, 'expires_in', '');
			$ch->setToken($this->channel->id, 'user_id', '');
		}

		return false;
	}

	/**
	 * includeHashTags
	 *
	 * @return  bool
	 */
	public function includeHashTags()
	{
		return $this->channel->params->get('hashtags', true);
	}

	/**
	 * getSocialUrl
	 *
	 * @param   object  $user  Param
	 *
	 * @return	string
	 */
	public function getSocialUrl($user)
	{
		return $user->{'public-profile-url'};
	}

	/**
	 * generateState
	 *
	 * @return	string
	 */
	protected function generateState()
	{
		return MD5($this->channel->modified . JFactory::getSession()->getToken());
	}

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
		$logger->log(JLog::INFO, 'LiOAuth2ChannelHelper sendMessage', $message);

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

		if (!empty($title))
		{
			$title = TextUtil::truncString($title, self::MAX_CHARS_TITLE);
			$content['title'] = $title;
		}

		if (!empty($text))
		{
			$text = TextUtil::truncString($text, self::MAX_CHARS_TEXT);
			$content['description'] = $text;
		}

		if (!empty($url))
		{
			$content['submitted-url'] = $url;
		}

		if ( ($post_attach) && (!empty($image_url)) )
		{
			$content['submitted-image-url'] = $image_url;
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
			$response = $this->getApiInstance()->share2('new', $content, $private);
			$result = $this->processResponse($response);
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

	/**
	 * _processResponse
	 *
	 * @param   array  $response  Params
	 *
	 * @return	array
	 */
	protected function processResponse($response)
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
