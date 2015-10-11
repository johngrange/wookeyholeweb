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

/**
 * AutoTweet e-mail channel.
 *
 * @package     Extly.Components
 * @subpackage  com_autotweet
 * @since       1.0
 */
class MailChannelHelper extends ChannelHelper
{
	const MAX_CHARS_SUBJECT = 76;

	protected $mailer;

	/**
	 * AutotweetMailChannel
	 *
	 * @param   object  &$channel  Param
	 */
	public function __construct(&$channel)
	{
		parent::__construct($channel);

		$this->mailer = JFactory::getMailer();
		$config = JFactory::getConfig();
		$sender = array(
						$config->get($this->get('mail_sender_email')),
						$config->get($this->get('mail_sender_name'))
		);
		$this->mailer->setSender($sender);
	}

	/**
	 * sendMessage
	 *
	 * @param   string  $message  Param
	 * @param   string  $data     Param
	 *
	 * @return	bool
	 */
	public function sendMessage($message, $data)
	{
		$sender_mail = $this->get('mail_sender_email');
		$sender_name = $this->get('mail_sender_name');
		$recipient_mail = $this->get('mail_recipient_email');

		$logger = AutotweetLogger::getInstance();
		$logger->log(JLog::INFO, 'sendMailMessage', $message);

		$result = null;

		$this->mailer->isHtml(true);
		$this->mailer->SetFrom($sender_mail, $sender_name);
		$this->mailer->AddAddress($recipient_mail);
		$this->mailer->Subject = TextUtil::truncString($data->title, self::MAX_CHARS_SUBJECT);
		$this->mailer->Body = $this->renderPost($this->channel->id, 'mailchannel', $message, $data);

		if (!$this->mailer->Send())
		{
			$result = array(
							false,
							'error sending mail'
			);
		}
		else
		{
			$result = array(
							true,
							'successfully sent'
			);
		}

		return $result;
	}
}
