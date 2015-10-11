<?php
/*------------------------------------------------------------------------
# Copyright (C) 2014-2015 WebxSolution Ltd. All Rights Reserved.
# @license - GPLv2.0
# Author: WebxSolution Ltd
# Websites:  http://www.webxsolution.com
# Terms of Use: An extension that is derived from the JoomlaCK editor will only be allowed under the following conditions: http://joomlackeditor.com/terms-of-use
# ------------------------------------------------------------------------*/ 

defined('_JEXEC') or die;

/**
 *Ark Editor Plugin
 *
 * @package     Joomla.Plugin
 * @subpackage  ArkEditor.SaveContent
 */
class PlgArkEditorSaveContent extends JPlugin
{
	public function onBeforeInstanceLoaded(&$params){
	
	 return "
		 editor.on('instanceReady', function()
		 {
			var editable = this.editable();
			var saveCmd = '".(JFactory::getApplication()->isAdmin() ? 'article.apply' : 'article.save')."' ;
			editable.setCustomData('saveCmd',saveCmd); 
		 });
		";
	}
	
	public function onInstanceLoaded(&$params) {}
}
