<?php
/**
* @version		$Id: remember.php 14401 2010-01-26 14:10:00Z louis $
* @package		Joomla
* @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

/**
 * Joomla! System Remember Me Plugin
 *
 * @package		Joomla
 * @subpackage	System
 */
class plgSystemSefBase extends JPlugin
{
	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for plugins
	 * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
	 * This causes problems with cross-referencing necessary for the observer design pattern.
	 *
	 * @access	protected
	 * @param	object	$subject The object to observe
	 * @param 	array   $config  An array that holds the plugin configuration
	 * @since	1.0
	 */
	

	function onAfterDispatch()
	{
		$app = JFactory::getApplication();
		
		
		if(!JComponentHelper::isEnabled('com_arkeditor'))
			return;
		
		$params = JComponentHelper::getParams('com_arkeditor');
		$params->merge($this->params);
		
		if(!$params->get('enable_sefbasepath',true))
			return;
			
		// No remember me for admin
		$router =  $app->getRouter();
		if($router->getMode() == JROUTER_MODE_SEF) {
			$document = JFactory::getDocument();
			$document->setBase(htmlspecialchars(JURI::root())); //lets set this correctly
		}	
		
	}
}