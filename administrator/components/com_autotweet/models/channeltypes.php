<?php

/**
 * @package     Extly.Components
 * @subpackage  com_autotweet - A powerful social content platform to manage multiple social networks.
 *
 * @author      Prieco S.A. <support@extly.com>
 * @copyright   Copyright (C) 2007 - 2015 Prieco, S.A. All rights reserved.
 * @license     http://http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @link        http://www.extly.com http://support.extly.com
 */
// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * AutotweetModelChanneltypes
 *
 * @package     Extly.Components
 * @subpackage  com_autotweet
 * @since       1.0
 */
class AutotweetModelChanneltypes extends F0FModel
{
	const TYPE_FB_CHANNEL = 2;

	// Const TYPE_FBEVENT_CHANNEL = 4;

	const TYPE_FBLINK_CHANNEL = 7;
	const TYPE_FBPHOTO_CHANNEL = 8;

	// Const TYPE_FBVIDEO_CHANNEL = 9;

	const TYPE_LINK_CHANNEL = 5;
	const TYPE_LIGROUP_CHANNEL = 6;
	const TYPE_LICOMPANY_CHANNEL = 10;
	const TYPE_LIOAUTH2_CHANNEL = 20;
	const TYPE_LIOAUTH2COMPANY_CHANNEL = 21;

	const TYPE_MAIL_CHANNEL = 3;
	const TYPE_TW_CHANNEL = 1;
	const TYPE_VK_CHANNEL = 11;
	const TYPE_VKGROUP_CHANNEL = 12;
	const TYPE_GPLUS_CHANNEL = 13;

	// Joocial Channels
	const TYPE_SCOOPIT_CHANNEL = 14;
	const TYPE_XING_CHANNEL = 15;
	const TYPE_TUMBLR_CHANNEL = 16;
	Const TYPE_BLOGGER_CHANNEL = 17;
	const TYPE_JOMSOCIAL_CHANNEL = 18;
	const TYPE_EASYSOCIAL_CHANNEL = 19;

	/**
	 * getParamsForm
	 *
	 * @param   int  $channeltype_id  Param
	 *
	 * @return	string
	 */
	public static function getParamsForm($channeltype_id)
	{
		if ($channeltype_id == self::TYPE_BLOGGER_CHANNEL)
		{
			return 'bloggerchannel';
		}

		if (($channeltype_id == self::TYPE_FB_CHANNEL) || ($channeltype_id == self::TYPE_FBLINK_CHANNEL) || ($channeltype_id == self::TYPE_FBPHOTO_CHANNEL) )
		{
			return 'fbchannel';
		}

		if ($channeltype_id == self::TYPE_GPLUS_CHANNEL)
		{
			return 'gpluschannel';
		}

		if ($channeltype_id == self::TYPE_EASYSOCIAL_CHANNEL)
		{
			return 'easysocialchannel';
		}

		if ($channeltype_id == self::TYPE_JOMSOCIAL_CHANNEL)
		{
			return 'jomsocialchannel';
		}

		if ($channeltype_id == self::TYPE_LINK_CHANNEL)
		{
			return 'lichannel';
		}

		if ($channeltype_id == self::TYPE_LIGROUP_CHANNEL)
		{
			return 'ligroupchannel';
		}

		if ($channeltype_id == self::TYPE_LICOMPANY_CHANNEL)
		{
			return 'licompanychannel';
		}

		if ($channeltype_id == self::TYPE_LIOAUTH2_CHANNEL)
		{
			return 'lioauth2channel';
		}

		if ($channeltype_id == self::TYPE_LIOAUTH2COMPANY_CHANNEL)
		{
			return 'lioauth2companychannel';
		}

		if ($channeltype_id == self::TYPE_MAIL_CHANNEL)
		{
			return 'mailchannel';
		}

		if ($channeltype_id == self::TYPE_SCOOPIT_CHANNEL)
		{
			return 'scoopitchannel';
		}

		if ($channeltype_id == self::TYPE_TUMBLR_CHANNEL)
		{
			return 'tumblrchannel';
		}

		if ($channeltype_id == self::TYPE_TW_CHANNEL)
		{
			return 'twchannel';
		}

		if (($channeltype_id == self::TYPE_VK_CHANNEL) || ($channeltype_id == self::TYPE_VKGROUP_CHANNEL))
		{
			return 'vkchannel';
		}

		if ($channeltype_id == self::TYPE_XING_CHANNEL)
		{
			return 'xingchannel';
		}

		return null;
	}

	/**
	 * getIcon
	 *
	 * @param   int  $channeltype_id  Param
	 *
	 * @return	string
	 */
	public static function getIcon($channeltype_id)
	{
		if ($channeltype_id == self::TYPE_BLOGGER_CHANNEL)
		{
			return '<i class=\'xticon xticon-google\'></i>';
		}

		if (($channeltype_id == self::TYPE_FB_CHANNEL) || ($channeltype_id == self::TYPE_FBLINK_CHANNEL) || ($channeltype_id == self::TYPE_FBPHOTO_CHANNEL) )
		{
			return '<i class=\'xticon xticon-facebook\'></i>';
		}

		if ($channeltype_id == self::TYPE_GPLUS_CHANNEL)
		{
			return '<i class=\'xticon xticon-google-plus\'></i>';
		}

		if ($channeltype_id == self::TYPE_EASYSOCIAL_CHANNEL)
		{
			return '<i class=\'xticon xticon-group\'></i>';
		}

		if ($channeltype_id == self::TYPE_JOMSOCIAL_CHANNEL)
		{
			return '<i class=\'xticon xticon-group\'></i>';
		}

		if (($channeltype_id == self::TYPE_LINK_CHANNEL)
			|| ($channeltype_id == self::TYPE_LIGROUP_CHANNEL)
			|| ($channeltype_id == self::TYPE_LICOMPANY_CHANNEL)
			|| ($channeltype_id == self::TYPE_LIOAUTH2_CHANNEL)
			|| ($channeltype_id == self::TYPE_LIOAUTH2COMPANY_CHANNEL))
		{
			return '<i class=\'xticon xticon-linkedin\'></i>';
		}

		if ($channeltype_id == self::TYPE_MAIL_CHANNEL)
		{
			return '<i class=\'xticon xticon-envelope\'></i>';
		}

		if ($channeltype_id == self::TYPE_SCOOPIT_CHANNEL)
		{
			return '<i class=\'xticon xticon-exclamation\'></i>';
		}

		if ($channeltype_id == self::TYPE_TUMBLR_CHANNEL)
		{
			return '<i class=\'xticon xticon-tumblr\'></i>';
		}

		if ($channeltype_id == self::TYPE_TW_CHANNEL)
		{
			return '<i class=\'xticon xticon-twitter\'></i>';
		}

		if ($channeltype_id == self::TYPE_VK_CHANNEL)
		{
			return '<i class=\'xticon xticon-vk\'></i>';
		}

		if ($channeltype_id == self::TYPE_VKGROUP_CHANNEL)
		{
			return '<i class=\'xticon xticon-vk\'></i>';
		}

		if ($channeltype_id == self::TYPE_XING_CHANNEL)
		{
			return '<i class=\'xticon xticon-xing\'></i>';
		}

		return null;
	}

	/**
	 * getChannelClass
	 *
	 * @param   int  $channeltype_id  Param
	 *
	 * @return	string
	 */
	public static function getChannelClass($channeltype_id)
	{
		switch ($channeltype_id)
		{
			case self::TYPE_FB_CHANNEL:
				return 'FacebookChannelHelper';

			case self::TYPE_FBLINK_CHANNEL:
				return 'FacebookLinkChannelHelper';

			case self::TYPE_FBPHOTO_CHANNEL:
				return 'FacebookPhotoChannelHelper';

			case self::TYPE_LINK_CHANNEL:
				return 'LinkedinChannelHelper';

			case self::TYPE_LIGROUP_CHANNEL:
				return 'LinkedinGroupChannelHelper';

			case self::TYPE_LICOMPANY_CHANNEL:
				return 'LinkedinCompanyChannelHelper';

			case self::TYPE_LIOAUTH2_CHANNEL:
				return 'LiOAuth2ChannelHelper';

			case self::TYPE_LIOAUTH2COMPANY_CHANNEL:
				return 'LiOAuth2CompanyChannelHelper';

			case self::TYPE_MAIL_CHANNEL:
				return 'MailChannelHelper';

			case self::TYPE_TW_CHANNEL:
				return 'TwitterChannelHelper';

			case self::TYPE_VK_CHANNEL:
				return 'VkChannelHelper';

			case self::TYPE_VKGROUP_CHANNEL:
				return 'VkGroupChannelHelper';

			case self::TYPE_GPLUS_CHANNEL:
				return 'GplusChannelHelper';

			case self::TYPE_SCOOPIT_CHANNEL:
				return 'ScoopitChannelHelper';

			case self::TYPE_TUMBLR_CHANNEL:
				return 'TumblrChannelHelper';

			case self::TYPE_EASYSOCIAL_CHANNEL:
				return 'EasySocialChannelHelper';

			case self::TYPE_JOMSOCIAL_CHANNEL:
				return 'JomSocialChannelHelper';

			case self::TYPE_XING_CHANNEL:
				return 'XingChannelHelper';

			case self::TYPE_BLOGGER_CHANNEL:
				return 'BloggerChannelHelper';
		}

		return null;
	}

	/**
	 * isFrontendEnabled
	 *
	 * @param   int  $channeltype_id  Param
	 *
	 * @return	string
	 */
	public static function isFrontendEnabled($channeltype_id)
	{
		switch ($channeltype_id)
		{
			case self::TYPE_FB_CHANNEL:
				return true;

			case self::TYPE_FBLINK_CHANNEL:
				return true;

			case self::TYPE_FBPHOTO_CHANNEL:
				return false;

			case self::TYPE_LINK_CHANNEL:
				return true;

			case self::TYPE_LIGROUP_CHANNEL:
				return true;

			case self::TYPE_LICOMPANY_CHANNEL:
				return false;

			case self::TYPE_MAIL_CHANNEL:
				return true;

			case self::TYPE_TW_CHANNEL:
				return true;

			case self::TYPE_VK_CHANNEL:
				return false;

			case self::TYPE_VKGROUP_CHANNEL:
				return false;

			case self::TYPE_GPLUS_CHANNEL:
				return false;

			case self::TYPE_SCOOPIT_CHANNEL:
				return false;

			case self::TYPE_TUMBLR_CHANNEL:
				return false;

			case self::TYPE_EASYSOCIAL_CHANNEL:
				return false;

			case self::TYPE_JOMSOCIAL_CHANNEL:
				return false;

			case self::TYPE_XING_CHANNEL:
				return false;

			case self::TYPE_BLOGGER_CHANNEL:
				return false;
		}

		return false;
	}

	/**
	 * buildQuery
	 *
	 * @param   bool  $overrideLimits  Param
	 *
	 * @return	F0FQuery
	 */
	public function buildQuery($overrideLimits = false)
	{
		$db = $this->getDBO();
		$query = parent::buildQuery($overrideLimits);
		$query->order($db->qn('name'));

		return $query;
	}

	/**
	 * formatUrl
	 *
	 * @param   int     $channeltype_id  Param
	 * @param   string  $socialUrl       Param
	 *
	 * @return	string
	 */
	public static function formatUrl($channeltype_id, $socialUrl)
	{
		$socialIcon = self::getIcon($channeltype_id);

		return '<p><a href="' . $socialUrl . '" target="_blank">' . $socialIcon . ' ' . $socialUrl . '</a></p>';
	}

	/**
	 * getAuthCallback
	 *
	 * @param   int  $channelId  Param
	 *
	 * @return	string
	 */
	public static function getAuthCallback($channelId)
	{
		$channeltype_id = F0FModel::getTmpInstance('Channels', 'AutoTweetModel')
			->getItem($channelId)
			->get('channeltype_id');

		switch ($channeltype_id)
		{
			case self::TYPE_BLOGGER_CHANNEL:
				return 'bloggerchannels';
			case self::TYPE_GPLUS_CHANNEL:
				return 'gpluschannels';
			case self::TYPE_SCOOPIT_CHANNEL:
				return 'scoopitchannels';
			case self::TYPE_TUMBLR_CHANNEL:
				return 'tumblrchannels';
			case self::TYPE_XING_CHANNEL:
				return 'xingchannels';
			case self::TYPE_LIOAUTH2_CHANNEL:
				return 'lioauth2channels';
			case self::TYPE_LIOAUTH2COMPANY_CHANNEL:
				return 'lioauth2channels';
		}

		return null;
	}
}
