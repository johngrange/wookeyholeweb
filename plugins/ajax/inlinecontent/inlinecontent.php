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
 error_reporting(E_ERROR & ~E_NOTICE);
 
class PlgAjaxInlineContent extends JPlugin
{

	public function onAjaxInlineContent()
	{

		$app = JFactory::getApplication();

		// JInput object
		$input = $app->input;
		$id = $input->get('id');
		$mode = $input->get('mode');
		$context = $input->get('context');
		$itemtype = $input->get('itemtype');
		$type = $input->get('type');
		$data = $input->get( 'data', '', 'raw');
		
		if($mode == 'get')
			return $this->_getData($id,$context);
		elseif($mode == 'process')
		{
			$html = base64_decode($data);
			return $this->_proccessSnapShot($html,$context,$itemtype);
		}
		elseif($mode == 'version')
		{
			return $this->_loadVersion($id,$type,$context,$itemtype);
		}			
		else
		{
			$data = $input->get('data',array(),'raw');
			$input->set('jform', array('version_note'=>''));
			return $this->_saveData($data,$id,$context,$itemtype,$type);
		}	
			
	}

    private function _getData($pk = null,$context = 'article')
	{
		if($pk == null)
			return array( 'title'=>'','data'=>'');	
		
		$item = null;
		
		if($context ==  'article')
		{
			
			$item = JTable::getInstance('content'); //Ideally would call content model but cannot call directly in this context
			$item->load($pk);
			$item->articletext = $item->introtext;

			if (!empty($item->fulltext))
			{
				$item->articletext .= '<hr id="system-readmore" />' . $item->fulltext;
			}
		}
		elseif($context == 'module')
		{
			$item = JTable::getInstance('module'); 
			$item->load($pk);
			$item->articletext = $item->content;
		}
		else		
		{
			$item = JTable::getInstance('category');
			$item->load($pk);
			$item->articletext = $item->description;
		}
		return array( 'title'=>$item->title,'data'=>$item->articletext);	
	}
	
	
	
	private function _proccessSnapShot($data,$context = 'article',$itemtype = 'article')
	{
		
		$item = new stdclass;
		
					
		if($context == 'article')
		{
			
			$text = '';
			
			if (isset($data))
			{
				$pattern = '#<hr\s+id=("|\')system-readmore("|\')\s*\/*>#i';
				$tagPos = preg_match($pattern, $data);
				
				if ($tagPos == 0)
				{
					
					$text = $data;
				}
				else
				{
					list ($text, $data) = preg_split($pattern, $data, 2);
					if($itemtype == $context)
						$text = $text.$data;
				}
			}

			$item->text = $text;
			$params = new JObject;
			$params->set('inline',false);
			$dispatcher	= JEventDispatcher::getInstance();
			JPluginHelper::importPlugin('content');
			$dispatcher->trigger('onContentPrepare', array ('com_content.category', &$item, &$params, 0));
				
		}
		elseif($context == 'module')
		{
			$item->text = '';
			if(isset($data))
			{
				$item->text = $data;
				$params = new JObject;
				$params->set('inline',false);
				$dispatcher	= JEventDispatcher::getInstance();
				JPluginHelper::importPlugin('content');
				$dispatcher->trigger('onContentPrepare', array ('mod_custom.content', &$item, &$params, 0));
			}	
		}
		else
		{
			$item->text = '';
			if(isset($data))
			{
				$item->text = $data;
				$params = new JObject;
				$params->set('inline',false);
				$dispatcher	= JEventDispatcher::getInstance();
				JPluginHelper::importPlugin('content');
				$dispatcher->trigger('onContentPrepare', array ('com_content.category', &$item, &$params, 0));
			}	
		}
		return array( 'data'=>$item->text);
	}
	
	private function _proccessData($pk = null,$context = 'article',$itemtype = 'article')
	{
		if($pk == null)
			return array( 'title'=>'','data'=>'');	
		
		$item = null;
		
		if($context ==  'article')
		{
			JModelLegacy::addIncludePath(JPATH_SITE.'/components/com_content/models');
			$model = JModelLegacy::getInstance('article','ContentModel');
			$item = $model->getItem($pk);
				
			if($itemtype != $context)
			{
				$item->text = $item->introtext;
			}
			elseif ($item->params->get('show_intro', '1') == '1')
			{
				$item->text = $item->introtext.' '.$item->fulltext;
			}
			elseif ($item->fulltext)
			{
				$item->text = $item->fulltext;
			}
			else
			{
				$item->text = $item->introtext;
			}
			
			$dispatcher	= JEventDispatcher::getInstance();
			JPluginHelper::importPlugin('content');
			$item->params->set('inline',false);
			$dispatcher->trigger('onContentPrepare', array ('com_content.'.$itemtype, &$item, &$item->params, 0));

		}
		elseif($context == 'module')
		{
			$item = JTable::getInstance('module');
			$item->load($pk);
			$item->text = $item->content;
			$params = new JObject;
			$params->set('inline',false);
			$dispatcher	= JEventDispatcher::getInstance();
			JPluginHelper::importPlugin('content');
			$dispatcher->trigger('onContentPrepare', array ('mod_custom.content', &$item, &$params, 0));
		
		}
		else		
		{
			$item = JTable::getInstance('category');
			$item->load($pk);
			$item->text = $item->description;
			$params = new JObject;
			$params->set('inline',false);
			$dispatcher	= JEventDispatcher::getInstance();
			JPluginHelper::importPlugin('content');
			$dispatcher->trigger('onContentPrepare', array ('com_content.category', &$item, &$params, 0));
			
		}
		return array( 'title'=>$item->title,'data'=>$item->text);	
	}
	
	
	
	
	
	private function _saveData($data,$pk = null,$context = 'article',$itemtype = 'article',$type = 'body')
	{
		if($pk == null)
			return $this->_proccessData($pk,$context,$itemtype);
			
		if($context ==  'article')
		{
			
			$item = JTable::getInstance('content'); //Ideally would call content model but cannot call directly in this context
			$item->load($pk);
	
			if($type == 'title')
			{
				$data['title'] = strip_tags($data['title']); 
			}
			if(isset($data['articletext']))
				$data['articletext'] = base64_decode($data['articletext']);	
			
			$item->save($data);
		}
		elseif($context == 'module')		
		{
			$item = JTable::getInstance('module');
			if(isset($data['articletext']))
				$data['content'] = base64_decode($data['articletext']);
			else
				$data['title'] = strip_tags($data['title']); 
			$item->load($pk);
			$item->save($data);
		}
		else		
		{
			$item = JTable::getInstance('category');
			if(isset($data['articletext']))
				$data['description'] = base64_decode($data['articletext']);
			else
				$data['title'] = strip_tags($data['title']); 
			$item->load($pk);
			$item->save($data);
		}
							
		return $this->_proccessData($pk,$context,$itemtype);
	}
	
	private function _loadVersion($versionId,$type,$context = 'article',$itemtype = 'article')
	{
				
		$historyTable = JTable::getInstance('Contenthistory');
		$historyTable->load($versionId);
		$rowArray = JArrayHelper::fromObject(json_decode($historyTable->version_data));
		$item = null;
			
		if($context ==  'article')
			$item = JTable::getInstance('content');
		elseif($context == 'category')
			$item = JTable::getInstance('category');
		else
			$item = JTable::getInstance('module');
		$item->bind($rowArray);	
		if($type == 'title')
		{
			return array( 'data'=>$item->title);
		}
		$text = '';
		
		if($context ==  'article')
		{
			$text = $item->introtext;
			if (!empty($item->fulltext))
			{
				$text .= '<hr id="system-readmore" />' . $item->fulltext;
			}
		}
		elseif($context == 'category')
		{
			$text = $item->description;
		}
		else		
		{
			 $text = $item->content;
		}		
		return array( 'data'=>$text);
		
	}
	
}
