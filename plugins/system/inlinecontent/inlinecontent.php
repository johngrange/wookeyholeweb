<?php
/*------------------------------------------------------------------------
# Copyright (C) 2012-2015 WebxSolution Ltd. All Rights Reserved.
# @license - GPLv2.0
# Author: WebxSolution Ltd
# Websites:  http://www.webxsolution.com
# Terms of Use: An extension that is derived from the JoomlaCK editor will only be allowed under the following conditions: http://joomlackeditor.com/terms-of-use
# ------------------------------------------------------------------------*/ 

defined('_JEXEC') or die;

/**
 *Ark inline content  System Plugin
 *
 * @package     Joomla.Plugin
 * @subpackage  System.inlineContent
 */
 
require_once(JPATH_PLUGINS.'/system/inlinecontent/inlinemode.php');
 
class PlgSystemInlineContent extends JPlugin
{

	private $inline_allowed_views = array ('featured','article','category','categories');
	private $isEnabled = true;
	public $app;

	public function onAfterDispatch()
	{

		//Inline editing is only enabled for frontend editing	
		if ($this->app->isAdmin())
		{
			$this->isEnabled = false;
			return;
		}

        
        $user = JFactory::getUser();
                
        //if user is guest lets bail
		if($user->get('guest'))
		{
			$this->isEnabled = false;
			return;
		}
		

        if(!$user->authorise('core.create', 'com_content') && !$user->authorise('core.edit', 'com_content')) 
		{
			$this->isEnabled = false;		
			return;
	    }     	
        
		if(!JComponentHelper::isEnabled('com_arkeditor'))
		{	$this->isEnabled = false;
			return;
		}
		
		$cParams = JComponentHelper::getParams('com_arkeditor');
		if(!$cParams->get('enable_inline',true))
		{
			$this->isEnabled = false;
			return;
		}	
		
		if(!JPluginHelper::isEnabled('editors','arkeditor'))
        {
           $this->isEnabled = false;
           return;
        }	

		$plugin = JPluginHelper::getPlugin('editors','arkeditor');	
	
				
		$view = $this->app->input->get('view');
		
	   
		$option = $this->app->input->get('option');
		
		if($option == 'com_ajax') // bail out
			return;
		
		if($option == 'com_content' && $this->app->input->get('tmpl',false)) //bailout if in a modal or print view 
			return;
		
		
		if(isset($plugin->inlineMode) && $plugin->inlineMode  == ArkInlineMode::REPLACE)
			return;
			
			
				
		if($view && in_array($view,$this->inline_allowed_views) )    
			$plugin->inlineMode = ArkInlineMode::INLINE;
				
	
		//Use reflection to get loaded method for JModuleHelper
		
		if($this->app->input->get('Itemid',0)) //We are assigned to a menu
		{
			jimport('joomla.filesystem.folder');
			if(JFolder::exists(JPATH_ROOT.'/modules/mod_inlinecustom'))
			{
				$method = '_load';
				
				if(method_exists('JModuleHelper','load'))
					$method = 'load';
				
				$invokeLoad = new ReflectionMethod('JModuleHelper', $method);
				$invokeLoad->setAccessible(true);
				
				$modules = $invokeLoad->invoke(null);
							
				for($i = 0; $i < count($modules); $i++)
				{
					if($modules[$i]->module == "mod_custom")
					{
						if(!isset($plugin->inlineMode) || isset($plugin->inlineMode) && $plugin->inlineMode != ArkInlineMode::INLINE)
							$plugin->inlineMode = ArkInlineMode::INLINE;
						$modules[$i]->module = "mod_inlinecustom";						
					}	
				}
			}
		}
			
		if(!isset($plugin->inlineMode) || isset($plugin->inlineMode) && $plugin->inlineMode != ArkInlineMode::INLINE)
			$this->isEnabled = false;
        
		if(isset($plugin->inlineMode) && $plugin->inlineMode == ArkInlineMode::INLINE)
        {
			$editor = JEditor::getInstance('arkeditor');
			$return = $editor->display('',false,'', '', '', '');
			$document = JFactory::getDocument();
			$document->addCustomTag($return);
			$this->isEnabled = true;
	    }

	}


    public function onAfterRender()
	{
	
		$option = $this->app->input->get('option');
		
		if($option == 'com_ajax') // bail out
			return;
		
	
		if ($this->app->isSite() && $this->isEnabled)
		{
            $user = JFactory::getUser();
            
            //if user is guest lets bail
		    if($user->get('guest'))
		    {
			    return;
		    }
      						
            // Get the response body
			$body = $this->app->getBody();
			
			$body = preg_replace('/="([^"]*?){div class=__ARKQUOTE__editable__ARKQUOTE__ data-id=__ARKQUOTE__(\d*)__ARKQUOTE__ data-context=__ARKQUOTE__(?:module|article)__ARKQUOTE__ data-type=__ARKQUOTE__title__ARKQUOTE__ data-itemtype=__ARKQUOTE__(?:module|category|featured)__ARKQUOTE__ contenteditable=__ARKQUOTE__true__ARKQUOTE__}([^"]*?){\/div}/',
						'="$1$3',$body);
				
		    $body =  str_replace(array('__ARKQUOTE__','{div class="editable"','contenteditable="true"}','{/div}'),
			array('"','<div class="editable"','contenteditable="true">','</div>'),$body);
			
			$data = JFactory::getDocument()->getHeadData();
			$title = str_replace(array('__ARKQUOTE__','{div class="editable"','contenteditable="true"}','{/div}'),
			array('"','<div class="editable"','contenteditable="true">','</div>'),$data['title']);
			$title = strip_tags($title);
			$data['title'] = $title;
			
			$body = preg_replace('/<title><div.*?<\/div>.*?<\/title>/i','<title>'.$title.'</title>',$body);
		  
			$this->app->setBody($body);

        
		}
	}

}