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
 * Ark Inline content editing Plugin
 *
 * @package     Joomla.Plugin
 * @subpackage  Content.ArkContent
 */
class PlgContentArkContent extends JPlugin
{

	private $inline_allowed_contexts = array ('com_content.article','com_content.category','com_content.featured');


	public function onContentPrepare($context, &$article, &$params, $page = 0)
	{
		$app = JFactory::getApplication();

		//Inline editing is only enabled for frontend editing	
		if ($app->isAdmin())
		{
			return;
		}
		
		
		$user = JFactory::getUser();
		
		//if user is guest lets bail
		if($user->get('guest'))
		{
			return;
		}

        $cParams = JComponentHelper::getParams('com_arkeditor');
		if(empty($cParams) ||!$cParams->get('enable_inline',true))
		{
			$this->isEnabled = false;
			return;
		}	

        
        if(!JPluginHelper::isEnabled('editors','arkeditor'))
            return;	
		
        if(!JPluginHelper::isEnabled('system','inlinecontent'))
            return;	
			
		if(!empty($params) && $params->get('inline') === false )
		{
			return;
		}
		
			//Are we allowed to edit articles in this view 
		if(!in_array($context,$this->inline_allowed_contexts) )    
		{
			return;
		}	
		
		
	
		
		if(!isset($article->id) && $context == 'com_content.category') 
		{
			$id = $app->input->get('id',0);
			if($id)
			{	
				$article->id = $id;
			}
		}
				
		$user = JFactory::getUser();
			
				
		if(!$user->authorise('core.create','com_content.article.'.$article->id) && !$user->authorise('core.edit','com_content.article.'.$article->id))
			return;
		
		/*filter article to see if it is being used to load a module if so skip it
		[widgetkit]
		{loadmodule}
		{loadposition}
		{module}
		{modulepos}
		*/
		$test = preg_match('/\{(?:loadmodule|loadposition|module|modulepos)\s+(.*?)\}/i',$article->text);
		if(!$test)
			$test = preg_match('/\[widgetkit\s+(.*?)\]/i',$article->text);

		if($test)
		{	
			return;
		}
		
		$asset = 'com_content.article.' . $article->id;
		
		$dataContext = 'article';
		$dataItemType = str_replace('com_content.','',$context);
		
		//check to see if this is actually a category
		if(!isset($article->introtext))
		{
			$dataContext  = 'category';
			$asset = 'com_cateogories.category.' . $article->id;
		}
		
		//can user edit item if not then bail
		if (!$user->authorise('core.edit', $asset))
		{
			return;
		}
			

		$view = $app->input->get('view');
		
		$option = $app->input->get('option');
				
		if(isset($article->id)) 
		{
			//title
			if(isset($article->params))
			{
				//$article->params->set('link_titles',$this->params->get('link_titles',0,'int')); removed as we now do this via JavaScript
				$article->params->set('show_readmore_title',0);
			}
			
			$temp = JComponentHelper::getParams('com_arkeditor');
			$temp->merge($this->params);

			if(isset($article->title) && $temp->get('enable_editable_titles',1))
			{
                $article->title = '{div class=__ARKQUOTE__editable__ARKQUOTE__ data-id=__ARKQUOTE__'.$article->id.'__ARKQUOTE__ data-context=__ARKQUOTE__'.$dataContext.'__ARKQUOTE__ data-type=__ARKQUOTE__title__ARKQUOTE__ data-itemtype=__ARKQUOTE__'.$dataItemType.'__ARKQUOTE__ contenteditable=__ARKQUOTE__true__ARKQUOTE__}'.$article->title.'{/div}'; 
			}	
		    //body
			$article->text = '<div class="editable" data-id="'.$article->id.'" data-context="'.$dataContext.'" data-type="body"  data-itemtype="'.$dataItemType.'" contenteditable="true">'.$article->text.'</div>';
		}
	}
}