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
 * ChannelHelper class.
 *
 * Base class for AutoTweet channels like Twitter of Facebook.
 * Works also as interface for usage.
 *
 * @package     Extly.Components
 * @subpackage  com_autotweet
 * @since       1.0
 */
abstract class ChannelHelper
{
	protected $channel;

	protected $cached_max_chars = null;

	protected $has_tmp_file = false;

	/**
	 * sendMessage.
	 *
	 * @param   string  $message  Params
	 * @param   object  $data     Params
	 *
	 * @return  boolean
	 */
	abstract public function sendMessage($message, $data);

	/**
	 * ChannelHelper.
	 *
	 * @param   object  &$ch  Params
	 */
	public function __construct(&$ch)
	{
		$channel = clone $ch;

		if ( (property_exists($channel, 'params')) && (is_string($channel->params)) )
		{
			$params = $channel->params;
			unset($channel->params);

			// Convert the params field to an array.
			$registry = new JRegistry;
			$registry->loadString($params);
			$channel->params = $registry;
		}
		elseif ( (isset($channel->xtform)) && (is_object($channel->xtform)) )
		{
			$params = $channel->xtform;
			unset($channel->xtform);

			$channel->params = $params;
		}
		else
		{
			throw new Exception('Invalid channel!');
		}

		$this->channel = $channel;
	}

	/**
	 * getChannelId
	 *
	 * @return  int
	 */
	public function getChannelId()
	{
		return $this->channel->id;
	}

	/**
	 * getChannelType
	 *
	 * @return  string
	 */
	public function getChannelType()
	{
		return $this->channel->channeltype_id;
	}

	/**
	 * getChannelType
	 *
	 * @return  string
	 */
	public function getChannelName()
	{
		return $this->channel->name;
	}

	/**
	 * getChannelDesc
	 *
	 * @return  string
	 */
	public function getChannelDesc()
	{
		return $this->channel->description;
	}

	/**
	 * isAutopublish
	 *
	 * @return  bool
	 */
	public function isAutopublish()
	{
		return $this->channel->autopublish;
	}

	/**
	 * isPublished
	 *
	 * @return  bool
	 */
	public function isPublished()
	{
		return $this->channel->published;
	}

	/**
	 * showUrl
	 *
	 * @return  int
	 */
	public function showUrl()
	{
		$showUrl = $this->channel->params->get('show_url');

		return ($showUrl != 'selected') ? $showUrl : null;
	}

	/**
	 * getMediaMode
	 *
	 * @return  int
	 */
	public function getMediaMode()
	{
		return $this->channel->media_mode;
	}

	/**
	 * get
	 *
	 * @param   string  $property  Params.
	 * @param   mixed   $default   Params.
	 *
	 * @return  mixed
	 */
	public function get($property, $default = null)
	{
		return $this->channel->params->get($property, $default);
	}

	/**
	 * getField
	 *
	 * @param   string  $property  Params.
	 * @param   mixed   $default   Params.
	 *
	 * @return  mixed
	 */
	public function getField($property, $default = null)
	{
		if (method_exists($this->channel, 'get'))
		{
			return $this->channel->get($property, $default);
		}

		if (isset($this->channel->$property))
		{
			return $this->channel->$property;
		}
		else
		{
			return $default;
		}
	}

	/**
	 * getMaxChars
	 *
	 * @return  int
	 */
	public function getMaxChars()
	{
		if ($this->cached_max_chars)
		{
			return $this->cached_max_chars;
		}
		else
		{
			$channeltype = F0FModel::getTmpInstance('Channeltypes', 'AutoTweetModel')->getTable();
			$channeltype->reset();
			$channeltype->load($this->channel->channeltype_id);
			$this->cached_max_chars = $channeltype->max_chars;

			return $channeltype->max_chars;
		}
	}

	/**
	 * hasWeight
	 *
	 * @return	bool
	 */
	public function hasWeight()
	{
		return false;
	}

	/**
	 * getChannelId
	 *
	 * @return  int
	 */
	public function getTargetId()
	{
		return $this->channel->params->get('target_id');
	}

	/**
	 * includeHashTags
	 *
	 * @return  bool
	 */
	public function includeHashTags()
	{
		return $this->channel->params->get('hashtags', false);
	}

	/**
	 * renderPost
	 *
	 * @param   int     $channelid    Param
	 * @param   string  $channeltype  Param
	 * @param   string  $message      Param
	 * @param   string  $data         Param
	 *
	 * @return  string
	 */
	public function renderPost($channelid, $channeltype, $message, $data)
	{
		$input = array(
						'task' => 'read',
						'format' => 'raw',

						// Just to invalidate the parameter
						'cid' => array(),

						'id' => $channelid,
						'message' => $message,
						'title' => $data->title,
						'fulltext' => $data->fulltext,
						'url' => $data->url,
						'org_url' => $data->org_url,
						'image_url' => $data->image_url,
						'media_mode' => $this->getMediaMode()
		);

		$config = array(
						'option'	=> 'com_autotweet',
						'view'		=> 'channel',
						'layout'	=> $channeltype . '-post',
						'input'		=> $input
		);

		ob_start();
		F0FDispatcher::getTmpInstance(
				'com_autotweet',
				'channels',
				$config
			)->dispatch();
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}
}
