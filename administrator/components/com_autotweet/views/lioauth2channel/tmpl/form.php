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

$session = JFactory::getSession();
$session->set('channelId', $this->item->id);

?>
<!-- com_autotweet_OUTPUT_START -->
<p style="text-align:center;">
	<span class="loaderspinner">&nbsp;</span>
</p>

<legend><?php echo JText::_('COM_AUTOTWEET_VIEW_CHANNEL_ACCOUNTDATA_TITLE'); ?></legend>

<?php

	echo JText::_('COM_AUTOTWEET_CHANNEL_LIOAUTH2_DESC');

	$required = array('class' => 'required', 'required' => 'required');
	echo EHtml::textControl($this->item->xtform->get('consumer_key'), 'xtform[consumer_key]', 'COM_AUTOTWEET_CHANNEL_LIOAUTH2_CONSUMER_KEY', 'COM_AUTOTWEET_CHANNEL_LIOAUTH2_CONSUMER_KEY_DESC', 'consumer_key', 60, $required);
	echo EHtml::textControl($this->item->xtform->get('consumer_secret'), 'xtform[consumer_secret]', 'COM_AUTOTWEET_CHANNEL_LIOAUTH2_CONSUMER_SECRET', 'COM_AUTOTWEET_CHANNEL_LIOAUTH2_CONSUMER_SECRET_DESC', 'consumer_secret', 60, $required);

	$accessToken = null;
	$accessTokenSecret = null;
	$user = null;
	$userId = null;

	$authUrl = '#';
	$authUrlButtonStyle = 'disabled';
	$validationGroupStyle = 'hide';

		// New channel, not even saved
	if ($this->item->id == 0)
	{
		$message = JText::_('COM_AUTOTWEET_CHANNEL_LIOAUTH2_NEWCHANNEL_NOAUTHORIZATION');
		include_once 'auth_button.php';
	}
	else
	{
		$lioauth2ChannelHelper = new LiOAuth2ChannelHelper($this->item);
		$isAuth = $lioauth2ChannelHelper->isAuth();

		// New channel, but saved
		if (($isAuth) && (is_array($isAuth)) && (array_key_exists('user', $isAuth)))
		{
			// We have an access Token!
			$user = $isAuth['user'];
			$userId = $user->id;
			$this->item->xtform->set('social_url', $lioauth2ChannelHelper->getSocialUrl($user));

			$validationGroupStyle = null;

			$accessToken = $this->item->xtform->get('access_token');
			$accessTokenSecret = $this->item->xtform->get('access_secret');

			include_once 'validation_button.php';
		}
		else
		{
			$message = JText::_('COM_AUTOTWEET_CHANNEL_LIOAUTH2_NEWCHANNEL_AUTHORIZATION');
			$authUrl = $lioauth2ChannelHelper->getAuthorizationUrl();

			if (!empty($authUrl))
			{
				$authUrlButtonStyle = null;

				include_once 'auth_button.php';
				include_once 'validation_button.php';
			}
			else
			{
				$authUrl = '#';
				$message = JText::_('COM_AUTOTWEET_CHANNEL_LIOAUTH2_NEWCHANNEL_NOAUTHORIZATION');
				include_once 'auth_button.php';
			}
		}
	}
?>

<!-- com_autotweet_OUTPUT_START -->
