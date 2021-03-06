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

// Check for PHP4
if (defined('PHP_VERSION'))
{
	$version = PHP_VERSION;
}
elseif (function_exists('phpversion'))
{
	$version = phpversion();
}
else
{
	// No version info. I'll lie and hope for the best.
	$version = '5.0.0';
}

// Old PHP version detected. EJECT! EJECT! EJECT!
if (!version_compare($version, '5.3.0', '>='))
{
	return JError::raise(
			E_ERROR,
			500,
			'PHP versions 4.x, 5.0, 5.1 and 5.2 are no longer supported by AutoTweetNG.',
			'The version of PHP used on your site is obsolete and contains known security vulenrabilities.
			Moreover, it is missing features required by AutoTweetNG to work properly or at all.
			Please ask your host to upgrade your server to the latest PHP 5.3/5.4 stable release. Thank you!');
}

if (!defined('AUTOTWEET_API'))
{
	include_once JPATH_ADMINISTRATOR . '/components/com_autotweet/api/autotweetapi.php';
}

$base_url = EParameter::getComponentParam(CAUTOTWEETNG, 'base_url');

if ( (defined('AUTOTWEET_CRONJOB_RUNNING')) && (AUTOTWEET_CRONJOB_RUNNING) && (!filter_var($base_url, FILTER_VALIDATE_URL)) )
{
	throw new Exception('AUTOTWEET_CRONJOB: Url base not set.');
}

$config = array();

$controller = null;

// If we are processing Gplus, redirect to controller
$session = JFactory::getSession();
$channelId = $session->get('channelId');

if (!empty($channelId))
{
	$input = new F0FInput;

	// Google+ and Blogger
	$code = $input->getString('code');

	// ScoopIt
	$oauth_token = $input->getString('oauth_token');
	$oauth_verifier = $input->getString('oauth_verifier');

	// LinkedIn
	// $code = $input->getString('code');
	$state = $input->getString('state');

	if ( ((!empty($oauth_token)) && (!empty($oauth_verifier)))
		|| ( (!empty($code)) && (!empty($state)) )
		|| (!empty($code)) )
	{
		$controller = F0FModel::getTmpInstance('Channeltypes', 'AutoTweetModel')->getAuthCallback($channelId);
		$config['input'] = array('task' => 'callback');
	}
	else
	{
		$session->set('channelId', false);
	}
}

// F0F app
F0FDispatcher::getTmpInstance('com_autotweet', $controller, $config)->dispatch();
