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
 * AutotweetControllerLiOAuth2Channels
 *
 * @package     Extly.Components
 * @subpackage  com_autotweet
 * @since       1.0
 */
class AutotweetControllerLiOAuth2Channels extends F0FController
{
	/**
	 * getCallbackUrl.
	 *
	 * @param   int     $channelId  Param
	 * @param   string  $callback   Param
	 *
	 * @return	string
	 */
	public static function getCallbackUrl($channelId, $callback = 'callback')
	{
		return JUri::base() . 'index.php?option=com_autotweet&_token=' . JFactory::getSession()->getFormToken();
	}

	/**
	 * callback.
	 *
	 * @return	void
	 */
	public function callback()
	{
		if ($this->csrfProtection)
		{
			$this->_csrfProtection();
		}

		try
		{
			// $channelId = $this->input->getUint('channelId');

			$session = JFactory::getSession();
			$channelId = $session->get('channelId');

			// Invalidating
			$session->set('channelId', false);

			$code = $this->input->getString('code');
			$state = $this->input->getString('state');

			if (!empty($code))
			{
				$channel = F0FTable::getAnInstance('Channel', 'AutoTweetTable');
				$result = $channel->load($channelId);

				if (!$result)
				{
					throw new Exception('LinkedIn Channel failed to load!');
				}

				$lioauth2ChannelHelper = new LiOAuth2ChannelHelper($channel);
				$lioauth2ChannelHelper->authenticate($code, $state);

				// Redirect
				$url = 'index.php?option=com_autotweet&view=channels&task=edit&id=' . $channelId;
				$this->setRedirect($url);
				$this->redirect();
			}
		}
		catch (Exception $e)
		{
			$logger = AutotweetLogger::getInstance();
			$logger->log(JLog::ERROR, $e->getMessage());

			throw $e;
		}
	}
}
