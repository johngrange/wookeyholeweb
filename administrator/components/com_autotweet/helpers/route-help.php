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
 * RouteHelp
 *
 * @package     Extly.Components
 * @subpackage  com_autotweet
 * @since       1.0
 */
class RouteHelp
{
	// Routing always by Backend-Frontend Query
	const ROUTING_MODE_COMPATIBILITY = 0;

	// Routing by Backend-Frontend Query or just JRoute on Front-end
	//  (some components may return edition page, e.g. SobiPro or EasyBlog)
	const ROUTING_MODE_PERFORMANCE = 1;

	// Routing by Site Application
	const ROUTING_MODE_EXPERIMENTAL = 2;

	// Language management
	const LANGMGMT_REMOVELANG = 1;
	const LANGMGMT_REPLACELANG = 2;
	const LANGMGMT_REPLACECONTENTLANG = 3;
	const LANGMGMT_SEF_VAR = '&lang=';

	protected $langmgmt_enabled = 0;

	protected $langmgmt_default_language = '';

	protected $langmgmt_content_language = null;

	protected $routing_mode = 0;

	// Disable URL routing when wrong URLs are returned by Joomla
	protected $urlrouting_enabled = 1;

	protected $validate_url = 1;

	protected $root_url = '';

	protected $root_url_path = '';

	private static $_instance = null;

	/**
	 * RouteHelp
	 *
	 */
	protected function __construct()
	{
		$this->langmgmt_enabled = EParameter::getComponentParam(CAUTOTWEETNG, 'langmgmt_enabled', 0);
		$this->langmgmt_default_language = EParameter::getComponentParam(CAUTOTWEETNG, 'langmgmt_default_language', '');

		$this->routing_mode = EParameter::getComponentParam(CAUTOTWEETNG, 'routing_mode', 0);

		// Root url overwrite
		$this->root_url = EParameter::getComponentParam(CAUTOTWEETNG, 'base_url', '');

		// Legacy invalid base_url initialization
		if ($this->root_url == 'http://')
		{
			$this->root_url = '';
		}

		$this->root_url = $this->_processRoot($this->root_url);

		// Root Url Check
		if (!empty($this->root_url))
		{
			$this->root_url = $this->validateUrl($this->root_url);
		}

		$this->root_url_path = $this->_getPath();

		// Disable URL routing when wrong URLs are returned by Joomla
		$this->urlrouting_enabled = EParameter::getComponentParam(CAUTOTWEETNG, 'urlrouting_enabled', 1);

		$this->validate_url = EParameter::getComponentParam(CAUTOTWEETNG, 'validate_url', 1);
	}

	/**
	 * getInstance
	 *
	 * @return	Instance
	 */
	public static function &getInstance()
	{
		if (!self::$_instance)
		{
			self::$_instance = new RouteHelp;
		}

		return self::$_instance;
	}

	/**
	 * getRoot.
	 *
	 * @return	string
	 */
	public function getRoot()
	{
		return $this->root_url;
	}

	/**
	 * getAbsoluteUrl
	 *
	 * @param   string  $url       Param
	 * @param   string  $is_image  Param
	 *
	 * @return	string
	 */
	public function getAbsoluteUrl($url, $is_image = false)
	{
		static $cache = array();

		$key = md5($url);

		if (array_key_exists($key, $cache))
		{
			return $cache[$key];
		}

		if (!$this->isAbsoluteUrl($url))
		{
			if ($is_image)
			{
				$url = $this->routeImageUrl($url);
			}
			else
			{
				$url = $this->routeUrl($url);
			}
		}

		if (!$is_image)
		{
			$url = $this->_addUtm($url);
		}

		$cache[$key] = $url;

		return $url;
	}

	/**
	 * _addUtm
	 *
	 * @param   string  $url  Param
	 *
	 * @return	string
	 */
	private function _addUtm($url)
	{
		if (empty($url))
		{
			return $url;
		}

		$utm_source = EParameter::getComponentParam(CAUTOTWEETNG, 'utm_source');
		$utm_medium = EParameter::getComponentParam(CAUTOTWEETNG, 'utm_medium');
		$utm_term = EParameter::getComponentParam(CAUTOTWEETNG, 'utm_term');
		$utm_content = EParameter::getComponentParam(CAUTOTWEETNG, 'utm_content');
		$utm_campaign = EParameter::getComponentParam(CAUTOTWEETNG, 'utm_campaign');

		if (($utm_source) || ($utm_medium) || ($utm_term) || ($utm_content) || ($utm_campaign))
		{
			$uri = JUri::getInstance($url);

			if ($utm_source)
			{
				$uri->setVar('utm_source', $utm_source);
			}

			if ($utm_medium)
			{
				$uri->setVar('utm_medium', $utm_medium);
			}

			if ($utm_term)
			{
				$uri->setVar('utm_term', $utm_term);
			}

			if ($utm_content)
			{
				$uri->setVar('utm_content', $utm_content);
			}

			if ($utm_campaign)
			{
				$uri->setVar('utm_campaign', $utm_campaign);
			}

			$url = $uri->toString();
		}

		return $url;
	}

	/**
	 * isAbsoluteUrl
	 *
	 * @param   string  $url  Param
	 *
	 * @return	bool
	 */
	public function isAbsoluteUrl($url)
	{
		// (preg_match('|^(http(s)?:)?//|', $url))

		return (JString::substr($url, 0, 4) == 'http');
	}

	/**
	 * isLocalUrl
	 *
	 * @param   string  $url  Param
	 *
	 * @return	bool
	 */
	public function isLocalUrl($url)
	{
		return (strpos($url, $this->root_url) !== false);
	}

	/**
	 * setContentLanguage
	 *
	 * @param   string  $lang  Param
	 *
	 * @return	void
	 */
	public function setContentLanguage($lang)
	{
		$this->langmgmt_content_language = $lang;
	}

	/**
	 * getLanguageSef.
	 *
	 * @param   string  $tag  Param
	 *
	 * @return	string
	 */
	public function getLanguageSef($tag = null)
	{
		if (!$tag)
		{
			$tag = JFactory::getLanguage()->getTag();
		}

		$languages = JLanguageHelper::getLanguages('lang_code');

		return $languages[$tag]->sef;
	}

	/**
	 * Routes the URL.
	 * This is a substitute for the original Joomla route function JRoute::_
	 * because JRoute::_ does work from frontend only and has some special behavoir
	 * with image URLs.
	 *
	 * @param   string  $url  Param
	 *
	 * @return	String
	 */
	private function routeUrl($url)
	{
		$logger = AutotweetLogger::getInstance();

		$logger->log(JLog::INFO, 'internal url = ' . $url);

		if (!empty($url))
		{
			// Get (sef) url for frontend and backend
			if ($this->urlrouting_enabled)
			{
				$url = $this->build($url);
				$logger->log(JLog::INFO, 'routeURL: routed url = ' . $url);
			}
			else
			{
				$logger->log(JLog::WARNING, 'routeURL: url routing disabled');
			}

			// Check for language management mode and correct url language if needed
			if ($this->langmgmt_enabled)
			{
				$url = $this->correctUrlLang($url);
				$logger->log(JLog::INFO, 'routeURL: language corrected url = ' . $url);
			}

			$url = $this->createAbsoluteUrl($url);
			$url = $this->validateUrl($url);
		}

		return $url;
	}

	/**
	 * Routes the Image.
	 *
	 * @param   string  $filename  Param
	 *
	 * @return	String
	 */
	private function routeImageUrl($filename)
	{
		$logger = AutotweetLogger::getInstance();
		$logger->log(JLog::INFO, 'routeImageUrl filename = ' . $filename);

		$url = '';

		if (!empty($filename))
		{
			$url = implode("/", array_map("rawurlencode", explode("/", $filename)));
			$url = $this->createAbsoluteUrl($url);
			$url = $this->validateUrl($url);
		}

		return $url;
	}

	/**
	 * build
	 *
	 * Route/build the URL.
	 * This is a substitute for the original Joomla route function JRoute::_
	 * because JRoute::_ does work from frontend only for SEF urls.
	 * Works also for JoomSEF and sh404sef.
	 *
	 * @param   string  $url  Param
	 *
	 * @return	object
	 */
	private function build($url)
	{
		$url = $this->_forceRelativeUrl($url);

		if (strpos($url, 'index.php?') === false)
		{
			return $url;
		}

		// Multilanguage support
		$url = $this->defineMultilingualTag($url);

		if ($this->routing_mode == self::ROUTING_MODE_COMPATIBILITY)
		{
			return $this->_frontSiteSefQuery($url);
		}
		elseif ($this->routing_mode == self::ROUTING_MODE_PERFORMANCE)
		{
			if ((JFactory::getApplication()->isAdmin()) || (defined('AUTOTWEET_CRONJOB_RUNNING')))
			{
				return $this->_frontSiteSefQuery($url);
			}
			else
			{
				return JRoute::_($url, false);
			}
		}
		elseif ($this->routing_mode == self::ROUTING_MODE_EXPERIMENTAL)
		{
			return $this->_siteApplicationSefQuery($url);
		}

		return $url;
	}

	/**
	 * _frontSiteSefQuery
	 *
	 * @param   string  $url  Param
	 *
	 * @return	string
	 */
	private function _frontSiteSefQuery($url)
	{
		$logger = AutotweetLogger::getInstance();

		$url_as_param = urlencode(base64_encode($url));
		$callsef = $this->root_url . 'index.php?option=com_autotweet&view=sef&task=route&url=' . $url_as_param;

		// Get the url
		$c = curl_init();
		curl_setopt($c, CURLOPT_URL, $callsef);
		curl_setopt($c, CURLOPT_HEADER, 0);
		curl_setopt($c, CURLOPT_NOBODY, 0);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 20);
		curl_setopt($c, CURLOPT_TIMEOUT, 40);
		curl_setopt($c, CURLOPT_FOLLOWLOCATION, 1);

		$logger->log(JLog::INFO, 'Calling SEF Router: ' . $callsef);

		$sefurl = curl_exec($c);
		$sefurl = base64_decode($sefurl);
		$result_code = curl_getinfo($c);

		$logger->log(JLog::INFO, '--> result: ' . $sefurl);

		// REDIRECT Case: Ok, one more chance
		if (((int) $result_code['http_code'] >= 300)
			&& ((int) $result_code['http_code'] < 400)
			&& (array_key_exists('redirect_url', $result_code)))
		{
			$redirect_url = $result_code['redirect_url'];
			$callsef = $redirect_url;
			curl_setopt($c, CURLOPT_URL, $callsef);

			$sefurl = curl_exec($c);
			$sefurl = base64_decode($sefurl);

			$result_code = curl_getinfo($c);

			$logger->log(JLog::INFO, 'REDIRECT Calling SEF Router: ' . $redirect_url);
			$logger->log(JLog::INFO, '--> result: ' . $sefurl);
		}

		// Error handling
		if (curl_errno($c))
		{
			$sefurl = JRoute::_($url, false);

			$logger = AutotweetLogger::getInstance();
			$logger->log(JLog::WARNING, 'Error routing SEF URL via frontend request - curl_error: ' . curl_errno($c) . ' ' . curl_error($c));
		}
		elseif (((int) $result_code['http_code'] < 200) ||
				((int) $result_code['http_code'] >= 300))
		{
			// Non-200 http_code cases
			$logger = AutotweetLogger::getInstance();
			$logger->log(JLog::WARNING, 'Error routing SEF URL via frontend request - http error: ' . $result_code['http_code'] . ' - callurl = ' . $url . ' - return url = ' . $sefurl);
			$sefurl = JRoute::_($url, false);
		}
		else
		{
			// In backend we need to remove some parts from the url
			$sefurl = str_replace('/components/com_autotweet/', '/', $sefurl);
		}

		// Something odd has happened
		if (empty($sefurl))
		{
			$logger->log(JLog::WARNING, 'Error routing SEF URL via frontend request - http error: ' . $result_code['http_code'], $result_code);
		}

		curl_close($c);

		return $sefurl;
	}

	/**
	 * _siteApplicationSefQuery
	 *
	 * @param   string  $url  Param
	 *
	 * @return	string
	 */
	private function _siteApplicationSefQuery($url)
	{
		// In the back end we need to set the application to the site app instead
		JFactory::$application = JApplication::getInstance('site');
		JFactory::$application->initialise();

		$sefurl = JRoute::_($url, false);

		// Set the appilcation back to the administartor app
		JFactory::$application = JApplication::getInstance('administrator');

		return $sefurl;
	}

	/**
	 * Helps with the Joomla url hell and creates corect url savely for frontend, backend and images.
	 *
	 * @param   string  $site_url  Param
	 *
	 * @return	string
	 */
	private function createAbsoluteUrl($site_url)
	{
		$site_url = $this->_forceRelativeUrl($site_url);
		$url = $this->root_url . $site_url;

		return $url;
	}

	/**
	 * _forceRelativeUrl
	 *
	 * @param   string  $site_url  Param
	 *
	 * @return	string
	 */
	private function _forceRelativeUrl($site_url)
	{
		$pattern = '//' . JFactory::getUri($this->root_url)->getHost();

		// If starts with '//qqq.com', avoid '//qqq.com/qqq'
		if (JString::strpos($site_url, $pattern) === 0)
		{
			$site_url = str_replace($pattern, '', $site_url);
		}

		if ($this->hasPath($site_url))
		{
			$path = $this->root_url_path;
			$l = JString::strlen($path);
			$site_url = JString::substr($site_url, $l);
		}

		// Just in case
		if (JString::strpos($site_url, '/administrator') === 0)
		{
			$site_url = JString::substr($site_url, 14);
		}

		// If starts with '//', avoid 'qqq.com///qqq'
		if (JString::substr($site_url, 0, 2) == '//')
		{
			$site_url = JString::substr($site_url, 2);
		}

		// If starts with '/', avoid 'qqq.com//qqq'
		if (JString::substr($site_url, 0, 1) == '/')
		{
			$site_url = JString::substr($site_url, 1);
		}

		return $site_url;
	}

	/**
	 * _processRoot.
	 *
	 * @param   string  $url  param
	 *
	 * @return	string
	 */
	private function _processRoot($url)
	{
		if (empty($url))
		{
			try
			{
				$url = JUri::root();
			}
			catch (Exception $e)
			{
				$url = 'http://undefined-domain.com/';
			}
		}

		// Forced front-end SSL
		if ((JFactory::getConfig()->get('force_ssl') == 2)
			&& (strpos($url, 'http:') === 0))
		{
			$url = str_replace('http:', 'https:', $url);
		}

		// Always end with '/'
		if (JString::substr($url, -1) != '/')
		{
			$url .= '/';
		}

		return $url;
	}

	/**
	 * getHost.
	 *
	 * @return	string
	 */
	private function getHost()
	{
		$uri = JUri::getInstance();

		if ($uri->parse($this->root_url))
		{
			$host = $uri->toString(
					array(
							'scheme',
							'host',
							'port'
				)
			);

			return $host;
		}

		return null;
	}

	/**
	 * getPath.
	 *
	 * @return	string
	 */
	private function _getPath()
	{
		$uri = JUri::getInstance();

		if ($uri->parse($this->root_url))
		{
			$path = $uri->toString(
					array(
							'path'
				)
			);

			return $path;
		}

		return null;
	}

	/**
	 * hasPath.
	 *
	 * @param   string  $url  Param
	 *
	 * @return	string
	 */
	private function hasPath($url)
	{
		$path = $this->root_url_path;
		$l = JString::strlen($path);

		// At least /a/
		return (($l >= 3) && (JString::substr($url, 0, $l) == $path));
	}

	/**
	 * validateUrl.
	 *
	 * @param   string  $url  Param
	 *
	 * @return	string
	 */
	private function validateUrl($url)
	{
		if (!$this->validate_url)
		{
			return $url;
		}

		$logger = AutotweetLogger::getInstance();

		if (filter_var($url, FILTER_VALIDATE_URL) !== false)
		{
			$logger->log(JLog::INFO, 'ValidateUrl: OK url = ' . $url);

			return $url;
		}

		// Second chance
		$c = curl_init();
		curl_setopt($c, CURLOPT_URL, $url);

		// Get the header
		curl_setopt($c, CURLOPT_HEADER, 1);

		// And *only* get the header
		curl_setopt($c, CURLOPT_NOBODY, 1);

		// Get the response as a string from curl_exec(), rather than echoing it
		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);

		// Don't use a cached version of the url
		curl_setopt($c, CURLOPT_FRESH_CONNECT, 1);

		if (!curl_exec($c))
		{
			$logger->log(JLog::ERROR, 'ValidateUrl: invalid url = ' . $url);
			JFactory::getApplication()->enqueueMessage(JText::_('COM_AUTOTWEET_COMPARAM_VALIDATE_URL_ERROR'), 'error');

			return null;
		}

		$logger->log(JLog::INFO, 'ValidateUrl: OK - Second chance - url = ' . $url);

		return $url;
	}

	/**
	 * defineMultilingualTag
	 *
	 * @param   string  $url  Param
	 *
	 * @return	string
	 */
	private function defineMultilingualTag($url)
	{
		if (!$this->isMultilingual())
		{
			return $url;
		}

		$uri = JUri::getInstance();

		if ((!$uri->parse($url)) || ($uri->hasVar('lang')))
		{
			return $url;
		}

		$logger = AutotweetLogger::getInstance();
		$logger->log(JLog::INFO, 'defineMultilingualTag: Url ' . $url);

		$languages = JLanguageHelper::getLanguages('lang_code');

		if ((self::LANGMGMT_REPLACECONTENTLANG == $this->langmgmt_enabled)
			&& (!empty($this->langmgmt_content_language)))
		{
			$langCode = $languages[$this->langmgmt_content_language]->sef;
		}
		else
		{
			$tag = JFactory::getLanguage()->getTag();
			$langCode = $languages[$tag]->sef;
		}

		$uri->setVar('lang', $langCode);
		$url = $uri->toString();

		$logger->log(JLog::INFO, 'defineMultilingualTag: Lang-Url ' . $url);

		return $url;
	}

	/**
	 * isMultilingual
	 *
	 * @return	bool
	 */
	public static function isMultilingual()
	{
		static $isMultilingual = null;

		if (is_null($isMultilingual))
		{
			$isMultilingual = (JPluginHelper::isEnabled('system', 'languagefilter'));
		}

		return $isMultilingual;
	}

	/**
	 * correctUrlLang.
	 *
	 * @param   string  $url  Param
	 *
	 * @return	string
	 */
	private function correctUrlLang($url)
	{
		$language = null;
		$logger = AutotweetLogger::getInstance();

		if ((self::LANGMGMT_REPLACECONTENTLANG == $this->langmgmt_enabled)
			&& (!empty($this->langmgmt_content_language)))
		{
			$language = $this->langmgmt_content_language;
			$logger->log(JLog::INFO, "correctUrlLang LANGMGMT_REPLACECONTENTLANG " . $language);

			if ($language == '*')
			{
				$logger->log(JLog::WARNING, "correctUrlLang: language * nothing to do.");

				return $url;
			}

			if (empty($language))
			{
				$logger->log(JLog::WARNING, 'correctUrlLang: no language definition. Mode: ' . self::LANGMGMT_REPLACECONTENTLANG);

				return $url;
			}

			$langSefValue = $this->getLanguageSef($language);

			return $this->correctUrlLangReplace($url, $langSefValue);
		}

		if ((self::LANGMGMT_REPLACELANG == $this->langmgmt_enabled)
			&& (!empty($this->langmgmt_default_language)))
		{
			$language = $this->langmgmt_default_language;
			$logger->log(JLog::INFO, "correctUrlLang LANGMGMT_REPLACELANG " . $language);

			if (empty($language))
			{
				$logger->log(JLog::WARNING, 'correctUrlLang: no language definition. Mode: ' . self::LANGMGMT_REPLACELANG);

				return $url;
			}

			$langSefValue = $this->getLanguageSef($language);

			return $this->correctUrlLangReplace($url, $langSefValue);
		}

		if (self::LANGMGMT_REMOVELANG == $this->langmgmt_enabled)
		{
			$logger->log(JLog::INFO, "correctUrlLang LANGMGMT_REMOVELANG");

			return $this->correctUrlLangReplace($url, '');
		}

		return $url;
	}

	/**
	 * correctUrlLangReplace
	 *
	 * @param   string  $url           Param
	 * @param   string  $langSefValue  Param
	 *
	 * @return	string
	 */
	private function correctUrlLangReplace($url, $langSefValue)
	{
		$langSefValues = $this->getLanguageSefs();
		$searchs = array();

		// Url: http://blabla.com/index.php?option=com_content&view=article&id=999&Itemid=42&lang=en
		if (JString::strpos($url, self::LANGMGMT_SEF_VAR) !== false)
		{
			foreach ($langSefValues as $l)
			{
				// Nothing to replace
				if ($l == $langSefValue)
				{
					continue;
				}

				$searchs[] = '#' . self::LANGMGMT_SEF_VAR . $l . '#';
			}

			// Case 1: Replace lang=en
			if (empty($langSefValue))
			{
				// Remove language from URL
				$replace = '';
			}
			else
			{
				// Replace language tag with default language
				$replace = self::LANGMGMT_SEF_VAR . $langSefValue;
			}
		}
		else
		{
			foreach ($langSefValues as $l)
			{
				// Nothing to replace
				if ($l == $langSefValue)
				{
					continue;
				}

				$searchs[] = '#/' . $l . '/#';
			}

			// Case 2: check for lang tag in SEF url - http://blabla.com/en/extensions-for-joomla
			if (empty($langSefValue))
			{
				// Remove language from URL
				$replace = '/';
			}
			else
			{
				// Replace language tag with default language
				$replace = '/' . $langSefValue . '/';
			}
		}

		$url = preg_replace($searchs, $replace, $url);

		return $url;
	}

	/**
	 * getLanguageSefs.
	 *
	 * @return	array
	 */
	private function getLanguageSefs()
	{
		$languages = JLanguageHelper::getLanguages('lang_code');
		$tags = array();

		foreach ($languages as $language)
		{
			$tags[] = $language->sef;
		}

		return $tags;
	}
}
