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
 * AutoTweet LiOAuth2Company channel.
**/

JLoader::import('channel', dirname(__FILE__));

/**
 * LiOAuth2CompanyChannelHelper
 *
 * @package     Extly.Components
 * @subpackage  com_autotweet
 * @since       1.0
 */
class LiOAuth2CompanyChannelHelper extends LiOAuth2ChannelHelper
{
	/**
	 * getMyCompanies.
	 *
	 * @return	object
	 */
	public function getMyCompanies()
	{
		$result = null;

		try
		{
			$response = $this->getApiInstance()->company2('?is-company-admin=true');

			if ($response['success'] === true)
			{
				$xml = $response['linkedin'];
				$companies = simplexml_load_string($xml);
				$companies = json_decode(json_encode($companies));

				$result = array();

				// One or more companies
				if (isset($companies->company))
				{
					$result = $companies->company;

					// We have an array
					if (is_array($result))
					{
						// Building Urls
						$companies = array();

						foreach ($result as $c)
						{
							$url = 'https://www.linkedin.com/company/' . $c->id;
							$c->url = $url;
							$companies[] = $c;
						}

						return $result;
					}
					else
					{
						// One Company
						// It's an object wrapped in an array

						$url = 'https://www.linkedin.com/company/' . $result->id;
						$result->url = $url;

						return array($result);
					}
				}
			}
			else
			{
				$msg = $response['info']['http_code'] . ' ' . JText::_('COM_AUTOTWEET_HTTP_ERR_' . $response['info']['http_code']);
				$result = array(false, $msg);
			}
		}
		catch (LinkedInException $e)
		{
			$result = array('id' => false, 'name' => $e->getMessage());
		}

		return $result;
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
			// Prepare content
			$content['title'] = TextUtil::truncString($title, self::MAX_CHARS_TITLE);
			$content['submitted-url'] = $url;
			$content['submitted-image-url'] = $image_url;

			// Strlen shorter than JString::strlen for UTF-8  - 2 char languages E.g. Hebrew
			$text = TextUtil::truncString($text, self::MAX_CHARS_DESC);
			$content['description'] = $text;
		}

		// Message
		if ($post_msg)
		{
			$content['comment'] = $title;
		}

		// Default for visibility
		$private = false;

		try
		{
			$response = $this->getApiInstance()->companyShare2($this->get('company_id'), 'new', $content, $private);
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
}
