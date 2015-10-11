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
 * FbAppHelper class.
 *
 * @package     Extly.Components
 * @subpackage  com_autotweet
 * @since       1.0
 */
class FbAppHelper
{
	const TESTING_APP_ID = 'TXktQXBwLUlE';

	const PAGE_LIMIT = 256;

	private $_facebook = null;

	protected $app_id;

	protected $secret;

	// Facebook long-lived oauth token for user
	protected $access_token;

	/**
	 * FbAppHelper.
	 *
	 * @param   string  $app_id        Params.
	 * @param   string  $secret        Params.
	 * @param   string  $access_token  Params.
	 */
	public function __construct($app_id, $secret, $access_token)
	{
		require_once dirname(__FILE__) . '/facebook-php-sdk/facebook.php';

		$this->app_id = $app_id;
		$this->secret = $secret;
		$this->access_token = $access_token;
	}

	/**
	 * login.
	 *
	 * @return	object
	 */
	public function login()
	{
		if (!$this->_facebook)
		{
			$this->_facebook = new facebookphpsdk\Facebook(
					array(
							'appId' => $this->app_id,
							'secret' => $this->secret,
							'cookie' => true
				)
			);
			$this->_facebook->setAccessToken($this->access_token);
		}

		return $this->_facebook;
	}

	/**
	 * verify.
	 *
	 * @return	boolean
	 */
	public function verify()
	{
		$result = null;

		try
		{
			if (empty($this->access_token))
			{
				$result = array(
								false,
								'Facebook Token not entered.'
				);
			}
			else
			{
				$perm_result = $this->_facebook->api("/me/permissions",
						array(
								'access_token' => $this->access_token
					)
				);

				$permissions = $perm_result['data'];
				$granted_perms = array();

				foreach ($permissions as $perm)
				{
					if ($perm['status'] == 'granted')
					{
						$granted_perms[$perm['permission']] = true;
					}
				}

				$needed_perms = array(
								'public_profile' => true,
								'publish_actions' => true,
								'publish_pages' => true,
								'manage_pages' => true,
								'user_managed_groups' => true,
								'user_photos' => true
				);

				$result_check = array();

				foreach ($needed_perms as $permKey => $permValue)
				{
					if (!array_key_exists($permKey, $granted_perms))
					{
						$result_check[] = $permKey;
					}
				}

				if (!empty($result_check))
				{
					$result_string = implode(', ', $result_check);
					$result = array(
									false,
									'Permissions ' . $result_string . ' not granted.'
					);
				}
				else
				{
					$result = array(
									true,
									'OK'
					);
				}
			}
		}
		catch (facebookphpsdk\FacebookApiException $e)
		{
			// There is no permission check for pages, groups etc. at the moment (for profile only)
			if (false === strpos($e->getMessage(), 'GraphMethodException: Unsupported get request'))
			{
				$result = array(
								false,
								$e->getMessage()
				);
			}
			else
			{
				$result = array(
								true,
								'OK'
				);
			}
		}

		return $result;
	}

	/**
	 * getUser.
	 *
	 * @param   string  $user_id  Params.
	 *
	 * @return	object
	 */
	public function getUser($user_id = null)
	{
		$arguments = array(
			'fields' => 'id,name,link'
		);

		if (is_numeric($user_id))
		{
			$user = $this->_facebook->api('/' . $user_id, $arguments);
		}
		else
		{
			$user = $this->_facebook->api('/me', $arguments);
		}

		return $user;
	}

	/**
	 * _getPagesAsChannel.
	 *
	 * @return	array
	 */
	private function _getPagesAsChannel()
	{
		$result = array();

		try
		{
			$arguments = array(
				'limit' => self::PAGE_LIMIT,
				'fields' => 'id,name,link,access_token'
			);

			$pages = $this->_facebook->api("/me/accounts", $arguments);

			foreach ($pages['data'] as $page)
			{
				$result[] = array(
								'type' => 'Page',
								'id' => $page['id'],
								'name' => $page['name'],
								'url' => $page['link'],
								'access_token' => $page['access_token']
				);
			}
		}
		catch (facebookphpsdk\FacebookApiException $e)
		{
			$result[] = array(
							'type' => 'Error',
							'id' => '0',
							'name' => 'Pages ' . $e->getMessage(),
							'token' => ''
			);
		}

		return $result;
	}

	/**
	 * _getGroupsAsChannel.
	 *
	 * @return	array
	 */
	private function _getGroupsAsChannel()
	{
		$result = array();

		try
		{
			$arguments = array(
				'limit' => self::PAGE_LIMIT,
				'fields' => 'id,name,link'
			);

			$groups = $this->_facebook->api("/me/groups", $arguments);

			foreach ($groups['data'] as $group)
			{
				if (array_key_exists('link', $group))
				{
					$url = $group['link'];
				}
				else
				{
					$url = 'https://www.facebook.com/' . $group['id'];
				}

				$result[] = array(
								'type' => 'Group',
								'id' => $group['id'],
								'name' => $group['name'],
								'url' => $url,
								'access_token' => (isset($group['access_token']) ? $group['access_token'] : $this->access_token)
				);
			}
		}
		catch (facebookphpsdk\FacebookApiException $e)
		{
			$result[] = array(
							'type' => 'Error',
							'id' => '0',
							'name' => 'Groups ' . $e->getMessage(),
							'token' => ''
			);
		}

		return $result;
	}

	/**
	 * getUserChannel.
	 *
	 * @return	array
	 */
	public function getUserChannel()
	{
		$channel = $this->_getUserAsChannel();

		if (count($channel) == 1)
		{
			return $channel[0];
		}

		return null;
	}

	/**
	 * _getUserAsChannel.
	 *
	 * @return	array
	 */
	private function _getUserAsChannel()
	{
		$result = array();

		try
		{
			$user = $this->getUser();

			if ($user)
			{
				$result[] = array(
								'type' => 'User',
								'id' => $user['id'],
								'name' => $user['name'],
								'url' => $user['link'],
								'access_token' => (isset($user['access_token']) ? $user['access_token'] : $this->access_token)
				);
			}
		}
		catch (facebookphpsdk\FacebookApiException $e)
		{
			$result[] = array(
							'type' => 'Error',
							'id' => '0',
							'name' => 'User ' . $e->getMessage(),
							'token' => ''
			);
		}
		return $result;
	}

	/**
	 * getChannels.
	 *
	 * @return	array
	 */
	public function getChannels()
	{
		$user = $this->_getUserAsChannel();
		$pages = $this->_getPagesAsChannel();
		$groups = $this->_getGroupsAsChannel();

		return array_merge($user, $pages, $groups);
	}

	/**
	 * getAlbums.
	 *
	 * @param   string  $channelid  Params
	 *
	 * @return	array
	 */
	public function getAlbums($channelid)
	{
		$result = array();

		try
		{
			$arguments = array(
				'limit' => self::PAGE_LIMIT,
				'fields' => 'id,name,can_upload'
			);

			$albums = $this->_facebook->api("/{$channelid}/albums", $arguments);

			$result[] = array(
							'type' => 'Album',
							'id' => 0,
							'name' => ' - Default - '
			);

			$albums_data = $albums['data'];

			foreach ($albums_data as $album)
			{
				if ($album['can_upload'])
				{
					$a = array(
						'type' => 'Album',
						'id' => $album['id'],
						'name' => $album['name']
					);
					$result[] = $a;
				}
			}
		}
		catch (facebookphpsdk\FacebookApiException $e)
		{
			$result[] = array(
							'type' => 'Error',
							'id' => '0',
							'name' => 'Album ' . $e->getMessage()
			);
		}

		return $result;
	}

	/**
	 * getItems.
	 *
	 * @param   string  $keywords  Params
	 * @param   string  $type      Params
	 * @param   string  $model     Params
	 *
	 * @return	array
	 */
	public function getItems($keywords, $type = 'adcity', $model = 'City')
	{
		$result = array();

		try
		{
			$query = '/search?type=' . $type . '&q=' . urlencode($keywords);
			$items = $this->_facebook->api($query);

			foreach ($items['data'] as $item)
			{
				$key = null;

				if (key_exists('key', $item))
				{
					$key = $item['key'];
				}
				elseif (key_exists('country_code', $item))
				{
					$key = $item['country_code'];
				}
				else
				{
					$key = $item['name'];
				}

				$result[] = array(
								'name' => $model,
								'id' => $key,
								'name' => $item['name']
				);
			}
		}
		catch (facebookphpsdk\FacebookApiException $e)
		{
			$result[] = array(
							'type' => 'Error',
							'id' => '0',
							'name' => $model . ' ' . $e->getMessage()
			);
		}

		return $result;
	}

	/**
	 * getDebugToken.
	 *
	 * @param   string  $fbchannel_access_token  Params
	 *
	 * @return	array
	 */
	public function getDebugToken($fbchannel_access_token = null)
	{
		if ($this->app_id == self::TESTING_APP_ID)
		{
			$response = array();
			$response['issued_at'] = '---';
			$response['expires_at'] = '---';

			return $response;
		}

		if (!$fbchannel_access_token)
		{
			$fbchannel_access_token = $this->access_token;
		}

		try
		{
			$response = $this->_facebook->api("/debug_token",
				array(
					'input_token' => $fbchannel_access_token
				)
			);

			if (array_key_exists('data', $response))
			{
				$data = $response['data'];
				$response['issued_at'] = JHtml::_('date', $data['issued_at'], JText::_('COM_AUTOTWEET_DATE_FORMAT'));

				if ($data['expires_at'])
				{
					$response['expires_at'] = JHtml::_('date', $data['expires_at'], JText::_('COM_AUTOTWEET_DATE_FORMAT'));
				}
				else
				{
					$response['expires_at'] = JText::_('COM_AUTOTWEET_VIEW_FBWACCOUNT_NEVER');
				}
			}
		}
		catch (Exception $e)
		{
			$response = array();
			$response['issued_at'] = '------';
			$response['expires_at'] = '------';
		}

		return $response;
	}

	/**
	 * getExtendedAccessToken.
	 *
	 * @return	string
	 */
	public function getExtendedAccessToken()
	{
		$extended_token = null;
		$status = $this->_facebook->setExtendedAccessToken();

		if ($status)
		{
			$extended_token = $this->_facebook->getAccessToken();
		}

		return $extended_token;
	}
}
