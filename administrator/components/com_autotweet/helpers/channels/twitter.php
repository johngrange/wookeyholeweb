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
 * AutoTweet Twitter channel.
**/

JLoader::import('channel', dirname(__FILE__));

/**
 * TwitterChannelHelper
 *
 * @package     Extly.Components
 * @subpackage  com_autotweet
 * @since       1.0
 */
class TwitterChannelHelper extends ChannelHelper
{
	protected $twitter = null;

	/**
	 * sendMessage
	 *
	 * @param   string  $message  Param
	 * @param   array   $data     Param
	 *
	 * @return	bool
	 */
	public function sendMessage($message, $data)
	{
		$imagefile = null;

		try
		{
			$image_url = $data->image_url;
			$media_mode = $this->getMediaMode();

			if (($media_mode != 'message') && !empty($image_url))
			{
				$imagefile = ImageUtil::getInstance()->downloadImage($image_url);

				if ($imagefile)
				{
					$result = $this->_sendTwitterMessageWithImage($message, $imagefile);
				}
				else
				{
					$result = $this->_sendTwitterMessage($message, null);
				}
			}
			else
			{
				$result = $this->_sendTwitterMessage($message, $image_url);
			}
		}
		catch (Exception $e)
		{
			$result = array(
				false,
				$e->getMessage()
			);
		}

		if ($imagefile)
		{
			ImageUtil::getInstance()->releaseImage($imagefile);
		}

		return $result;
	}

	/**
	 * Internal service functions
	 *
	 * @return	object
	 */
	protected function getApiInstance()
	{
		if (!$this->twitter)
		{
			require_once dirname(__FILE__) . '/tmhOAuth/tmhOAuth.php';

			$this->twitter = new tmhOAuth\tmhOAuth(
					array(
							'consumer_key' => $this->get('consumer_key'),
							'consumer_secret' => $this->get('consumer_secret'),
							'user_token' => $this->get('access_token'),
							'user_secret' => $this->get('access_token_secret')
				)
			);
		}

		return $this->twitter;
	}

	/**
	 * _sendTwitterMessage
	 *
	 * @param   string  $status_msg  Param
	 * @param   string  $image_url   Param
	 *
	 * @return	array
	 */
	private function _sendTwitterMessage($status_msg, $image_url)
	{
		$logger = AutotweetLogger::getInstance();
		$logger->log(JLog::INFO, '_sendTwitterMessage', $status_msg);

		$api = $this->getApiInstance();
		$code = $api->request('POST', $api->url('1.1/statuses/update'),
				array(
						'status' => $status_msg
			)
		);

		return $this->_processResponse($code, $api);
	}

	/**
	 * _sendTwitterMessageWithImage
	 *
	 * @param   string  $status_msg  Param
	 * @param   string  $imagefile   Param
	 *
	 * @return	array
	 */
	private function _sendTwitterMessageWithImage($status_msg, $imagefile)
	{
		$logger = AutotweetLogger::getInstance();
		$logger->log(JLog::INFO, '_sendTwitterMessageWithImage: ' . $status_msg . ' - ' . $imagefile);

		$api = $this->getApiInstance();

		if ($imagefile)
		{
			$basename = basename($imagefile);
			list($width, $height, $type, $attr) = getimagesize($imagefile);
			$mimetype = image_type_to_mime_type($type);

			$binaryimage_load = EParameter::getComponentParam(CAUTOTWEETNG, 'binaryimage_load');

			if ($binaryimage_load)
			{
				$handle = fopen($imagefile, "rb");
				$postimage = fread($handle, filesize($imagefile));
				fclose($handle);
			}
			else
			{
				$postimage = '@' . $imagefile;
			}

			$code = $api->request(
					'POST',
					$api->url('1.1/statuses/update_with_media'),
					array(
						'media[]' => "{$postimage};type={$mimetype};filename={$basename}",
						'status' => $status_msg
					),

					// Use auth
					true,

					// Multipart
					true
			);
		}
		else
		{
			$code = $api->request('POST', $api->url('1.1/statuses/update'), array(
							'status' => $status_msg
				)
			);
		}

		return $this->_processResponse($code, $api);
	}

	/**
	 * _processResponse
	 *
	 * @param   int     $code  Param
	 * @param   object  &$api  Param
	 *
	 * @return	array
	 */
	public static function _processResponse($code, &$api)
	{
		$code = trim($code);
		$msg = 'Unknown error / Re-Validate channel.';

		if ((!isset($api->response)) || (!isset($api->response['response'])))
		{
			return array(
							false,
							$code . ' - Unknown error / No response'
			);
		}

		// Process response-response
		$response = json_decode($api->response['response']);

		if (empty($response))
		{
			return self::_processErrors($api);
		}

		if ($code == 200)
		{
			// Message delivered
			if (isset($response->id_str))
			{
				$postUrl = $response->id_str;

				if (isset($response->user->screen_name))
				{
					$postUrl = 'https://twitter.com/' . $response->user->screen_name . '/status/' . $postUrl;
				}

				$msg = 'OK - ' . $postUrl;

				return array(
								true,
								$msg
				);
			}
		}
		elseif ($code == 0)
		{
			return self::_processErrors($api);
		}
		else
		{
			// Double array
			if (is_array($response))
			{
				foreach ($response as $resp)
				{
					foreach ($resp as $r)
					{
						$msg = $code . ' - ' . $r->message;
						break;
					}
				}
			}

			// Object with Errors Array
			elseif (is_object($response) && isset($response->errors) && is_array($response->errors) && count($response->errors) > 0)
			{
				$error = $response->errors[0];
				$msg = $error->code . ' - ' . $error->message;
			}
			else
			{
				$msg = print_r($response, true);
			}
		}

		return array(
						false,
						$msg
		);
	}

	/**
	 * _processResponse
	 *
	 * @param   int     $code  Param
	 * @param   object  &$api  Param
	 *
	 * @return	array
	 */
	public static function processJsonResponse($code, &$api)
	{
		$code = trim($code);
		$msg = 'Unknown error / Re-Validate channel.';

		if ((!isset($api->response)) || (!isset($api->response['response'])))
		{
			return array(
							false,
							$code . ' - Unknown error / No response'
			);
		}

		// Process response-response
		$response = json_decode($api->response['response']);

		if (empty($response))
		{
			return self::_processErrors($api);
		}

		if ($code == 200)
		{
			return array(
							true,
							$response
			);
		}
		elseif ($code == 0)
		{
			return self::_processErrors($api);
		}
		else
		{
			// Double array
			if (is_array($response))
			{
				foreach ($response as $resp)
				{
					foreach ($resp as $r)
					{
						$msg = $code . ' - ' . $r->message;
						break;
					}
				}
			}

			// Object with Errors Array
			elseif (is_object($response) && isset($response->errors) && is_array($response->errors) && count($response->errors) > 0)
			{
				$error = $response->errors[0];
				$msg = $error->code . ' - ' . $error->message;
			}
			else
			{
				$msg = print_r($response, true);
			}
		}

		return array(
						false,
						$msg
		);
	}

	/**
	 * _processHeaders
	 *
	 * @param   int     $code  Param
	 * @param   object  &$api  Param
	 *
	 * @return	array
	 */
	public static function _processHeaders($code, &$api)
	{
		$msg = $code . ' - ' . JText::_('COM_AUTOTWEET_HTTP_ERR_' . $code);

		if (array_key_exists('headers', $api->response))
		{
			$headers = $api->response['headers'];

			if (array_key_exists('x-access-level', $headers))
			{
				$accesslevel = $headers['x-access-level'];
			}
			elseif (array_key_exists('X-Access-Level', $headers))
			{
				$accesslevel = $headers['X-Access-Level'];
			}
			else
			{
				$accesslevel = 'Not detected';
			}

			$msg = $msg . ' (access level: ' . $accesslevel . ')';

			// Show rates
			if (($accesslevel == 'read-write') || ($accesslevel == 'read-write-directmessages'))
			{
				$msg = $msg . ' (rate limit: ' . $headers['x-rate-limit-remaining'] . '/' . $headers['x-rate-limit-limit'] . ')';
				$response = json_decode($api->response['response']);

				return array(
								true,
								$msg,
								$response
				);
			}
			else
			{
				return array(
								false,
								$msg
				);
			}
		}

		return array(
						false,
						$msg
		);
	}

	/**
	 * _processErrors
	 *
	 * @param   object  &$api  Param
	 *
	 * @return	array
	 */
	public static function _processErrors(&$api)
	{
		if (array_key_exists('errno', $api->response))
		{
			$errno = $api->response['errno'];
		}
		else
		{
			$errno = 'Unknown 0 code';
		}

		if (array_key_exists('error', $api->response))
		{
			$error = $api->response['error'];
		}
		else
		{
			$error = 'Unknown error';
		}

		return array(
						false,
						$errno . ' - ' . $error
		);
	}

	/**
	 * hasWeight
	 *
	 * @return	bool
	 */
	public function hasWeight()
	{
		return true;
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
}
