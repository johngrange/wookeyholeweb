<?php

/* ---------------------------------------------------------------------------------------------------------------------
 * Bang2Joom Social Plugin for Joomla! 2.5+
 * ---------------------------------------------------------------------------------------------------------------------
 * Copyright (C) 2011-2012 Bang2Joom. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: Bang2Joom
 * Website: http://www.bang2joom.com
  ----------------------------------------------------------------------------------------------------------------------
 */

defined('_JEXEC') or die;
jimport('joomla.plugin.plugin');
$doc = JFactory::getDocument();
$doc->addStyleSheet(JURI::root() . 'plugins/content/b2jsocial/css/b2jsocial.css');
require_once(JPATH_SITE . '/components/com_mailto/helpers/mailto.php');

class plgContentB2JSocial extends JPlugin
{
	public $types = '';
    
    // On After display product // redShop
    public function onAfterDisplayProductTitle($tmpl, $var, $product)
    {
			$position = $this->params->get('position', 'after');
			$content = $this->params->get('content', 'all');

      if (($position == 'both' || $position == 'after') && (JRequest::getString("option") == "com_redshop" && JRequest::getString("view") == "product")) 
			{
				if($content == "all" || $content == "rs")
				{
					$image_url = JURI::root().'components/com_redshop/assets/images/product/'.$product->product_full_image;
					$item_link = JURI::current();
					return $this->render($product->product_name, $image_url, $item_link, 'redShop');
				}
      }
    }
    
    // On Before display product // redShop
    public function onBeforeDisplayProduct($tmpl, $var, $product)
    {
			$position = $this->params->get('position', 'after');
			$content = $this->params->get('content', 'all');

			if (($position == 'both' || $position == 'before') && (JRequest::getString("option") == "com_redshop" && JRequest::getString("view") == "product"))
			{
				if($content == "all" || $content == "rs")
				{
					$image_url = JURI::root().'components/com_redshop/assets/images/product/'.$product->product_full_image;
					$item_link = JURI::current();
					return $this->render($product->product_name, $image_url, $item_link, 'redShop');
				}
			}
    }
    
    // On Before display Content // k2, articles
	public function onContentBeforeDisplay($context, &$article, &$params, $limitstart = 0){
		if ($this->params->get('include_in_categories', 0)){
			$this->types = array('article'=>array('com_content.article', 'com_content.category'), 'k2' => array('com_k2.item', 'com_k2.itemlist'), 'all' => array('com_content.article', 'com_k2.item', 'com_k2.itemlist', 'com_content.category'));
		} else {
			$this->types = array('article'=>array('com_content.article'), 'k2' => array('com_k2.item'), 'all' => array('com_content.article', 'com_k2.item'));
		}
		$position = $this->params->get('position', 'after');
		$content = $this->params->get('content', 'all');
		$k2_categories = $this->params->get('k2_categories', array());
		$joomla_categories = $this->params->get('joomla_categories', array());
		$k2_filter = $this->params->get('k2_filter', 1);
		$article_filter = $this->params->get('article_filter', 1);
        
		$satisfied_context = false;
		for ($i = 0; $i < count($this->types[$content]); $i++){
			if ($this->types[$content][$i] == $context)
			$satisfied_context = true;
		}
                
		if (($position == 'both' || $position == 'before') && $satisfied_context) { 
			if($content == "k2"){
				if($k2_filter || in_array($article->catid, $k2_categories)){
					$image_array = explode('/', $article->imageXLarge);
					$image_url = JURI::root().'media/k2/items/cache/'.$image_array[count($image_array)-1];
					$item_link = $article->link;
					return $this->render($article->title, $image_url, $item_link, $context);
				}
			}
			if($content == "article"){
				if($article_filter || in_array($article->catid, $joomla_categories)){
					$item_link = JRoute::_(ContentHelperRoute::getArticleRoute($article->slug, $article->catid));
					return $this->render($article->title, '', $item_link, $context);
				}
			}
			if($content == "all"){
					
				if((in_array($context, $this->types['article']) and (in_array($article->catid, $joomla_categories) || $article_filter)) or 
					(in_array($context, $this->types['k2']) and (in_array($article->catid, $k2_categories) || $k2_filter))){
					
						if(in_array($context, $this->types['article']) and (in_array($article->catid, $joomla_categories) || $article_filter)){
							$image_url = '';
							$item_link = JRoute::_(ContentHelperRoute::getArticleRoute($article->slug, $article->catid));
						}
						
						if(in_array($context, $this->types['k2']) and (in_array($article->catid, $k2_categories) || $k2_filter)){
							$image_array = explode('/', $article->imageXLarge);
							$image_url = JURI::root().'media/k2/items/cache/'.$image_array[count($image_array)-1];
							$item_link = $article->link;
						}
						return $this->render($article->title, $image_url, $item_link, $context);
				}
			}
		}
	}
    // On After display Content // k2, articles
	public function onContentAfterDisplay($context, &$article, &$params, $limitstart = 0){
		if ($this->params->get('include_in_categories', 0)){
			$this->types = array('article'=>array('com_content.article', 'com_content.category'), 'k2' => array('com_k2.item', 'com_k2.itemlist'), 'all' => array('com_content.article', 'com_k2.item', 'com_k2.itemlist', 'com_content.category'));
		} else {
			$this->types = array('article'=>array('com_content.article'), 'k2' => array('com_k2.item'), 'all' => array('com_content.article', 'com_k2.item'));
		}
		$position = $this->params->get('position', 'after');
		$content = $this->params->get('content', 'all');
		$k2_categories = $this->params->get('k2_categories', array());
		$joomla_categories = $this->params->get('joomla_categories', array());
		$k2_filter = $this->params->get('k2_filter', 1);
		$article_filter = $this->params->get('article_filter', 1);
		
		$satisfied_context = false;
		for ($i = 0; $i < count($this->types[$content]); $i++){
			if ($this->types[$content][$i] == $context)
			$satisfied_context = true;
		}
		if (($position == 'both' || $position == 'after') && $satisfied_context) {
			if($content == "k2"){
				if($k2_filter || in_array($article->catid, $k2_categories)){
					$image_array = explode('/', $article->imageXLarge);
					$image_url = JURI::root().'media/k2/items/cache/'.$image_array[count($image_array)-1];
					$item_link = $article->link;
					return $this->render($article->title, $image_url, $item_link, $context);
				}
			}
			if($content == "article"){
				if($article_filter || in_array($article->catid, $joomla_categories)){
					$item_link = JRoute::_(ContentHelperRoute::getArticleRoute($article->slug, $article->catid));
					return $this->render($article->title, '', $item_link, $context);
				}
			}
			if($content == "all"){
					
					if((in_array($context, $this->types['article']) and (in_array($article->catid, $joomla_categories) || $article_filter)) or 
						(in_array($context, $this->types['k2']) and (in_array($article->catid, $k2_categories) || $k2_filter))){
						
						if(in_array($context, $this->types['article']) and (in_array($article->catid, $joomla_categories) || $article_filter)){
							$image_url = '';
							$item_link = JRoute::_(ContentHelperRoute::getArticleRoute($article->slug, $article->catid));
						}
						
						if(in_array($context, $this->types['k2']) and (in_array($article->catid, $k2_categories) || $k2_filter)){
							$image_array = explode('/', $article->imageXLarge);
							$image_url = JURI::root().'media/k2/items/cache/'.$image_array[count($image_array)-1];
							
							$item_link = $article->link;
						}
						return $this->render($article->title, $image_url, $item_link, $context);
					}
			}
		}
	}
	
	function getTweetCount($url){
			$url = urlencode($url);
			$endpoint = "http://urls.api.twitter.com/1/urls/count.json?url=".$url;
			$fileData = file_get_contents($endpoint);
			$json = json_decode($fileData);
			unset($fileData);
			if (!is_null($json) and isset($json->count)){
				return $json->count;;
			} else {
				return '-';
			}
	}
	function getFBShareCount($url){
			$url = urlencode($url);
			$endpoint="https://api.facebook.com/method/links.getStats?urls=".$url."&format=json";
			$fileData=file_get_contents($endpoint);
			$json = json_decode($fileData);
			unset($fileData);
			if (!is_null($json) and isset($json[0]->share_count)){
				return $json[0]->share_count;
			} else {
				return '-';
			}
	}
	function getLinkedInCount($url){
			$url = urlencode($url);
			$endpoint = "http://www.linkedin.com/countserv/count/share?url=".$url."&format=json";
			$fileData=file_get_contents($endpoint);
			$json = json_decode($fileData);
			unset($fileData);
			if (!is_null($json) and isset($json->count)){
				return $json->count;;
			} else {
				return '-';
			}
	}
	function get_plusones($url) {
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, "https://clients6.google.com/rpc");
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"' . $url . '","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]');
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
			$curl_results = curl_exec ($curl);
			curl_close ($curl);
			$json = json_decode($curl_results, true);
			return intval( $json[0]['result']['metadata']['globalCounts']['count'] );
	}
	function get_pins($url) {
			$url = urlencode($url);
			$endpoint = "http://api.pinterest.com/v1/urls/count.json?url=".$url;
			$fileData=file_get_contents($endpoint);
			$fileData_filtered = preg_replace('/^receiveCount\((.*)\)$/', "\\1", $fileData);
			$json = json_decode($fileData_filtered);
			unset($fileData);
			if (!is_null($json) and isset($json->count)){
				return $json->count;
			} else {
				return '-';
			}
	}
	
	function shorten_counts($count){
		if ($count < 1000){
			return $count;
		} else if ($count > 999 && $count < 1000000){
			return round($count/1000)."k";
		} else if ($count > 999999){
			return round($count/1000000)."m";
		} else {
			return $count;
		}
	}

	
	public function render($title, $image_url, $item_link, $context){
            
    $twitter_title = str_replace("|", "", $title);
		JPlugin::loadLanguage('plg_content_b2jsocial', JPATH_ADMINISTRATOR);
    $doc = JFactory::getDocument();
		$preset = $this->params->get('preset', 1);
		$share_counts = $this->params->get('share_counts', 0);
		$count_color = $this->params->get('count_color', '#666666');
		$include_font = $this->params->get('include_font', 1);
		if($include_font){
			$doc->addStyleSheet('http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css');
		}
		$background_color = $this->params->get('background_color', '#999999');
		$icon_color = $this->params->get('icon_color', '#ffffff');
		$icon_font_size = $this->params->get('icon_font_size', '24');
		$buttons = $this->params->get('buttons', 'icons');
		$icon_size = $this->params->get('icon_size', '32');
		$align = $this->params->get('align', 'left');
		$f_pos = $this->params->get('f_pos', 1);
		$t_pos = $this->params->get('t_pos', 2);
		$g_pos = $this->params->get('g_pos', 3);
		$l_pos = $this->params->get('l_pos', 4);
		$p_pos = $this->params->get('p_pos', 5);
		$e_pos = $this->params->get('e_pos', 6);
		$f = $this->params->get('f', 1);
		$t = $this->params->get('t', 1);
		$g = $this->params->get('g', 1);
		$l = $this->params->get('l', 1);
		$p = $this->params->get('p', 1);
		$e = $this->params->get('e', 1);
		$t_name = $this->params->get('t_name', 'bang2joom');
    $f_og = $this->params->get('f_og', 1);
		if ($share_counts){
			$buttons = 'icons';
		}
        
		if($f_og){
				$doc->addCustomTag('<meta property="og:title" content="'.$title.'" />');
				$doc->addCustomTag('<meta property="og:type" content="product" />');
				if($image_url != ''){
					$doc->addCustomTag('<meta property="og:image" content="'.$image_url.'" />');
				}
				$doc->addCustomTag('<meta property="og:url" content="'.JFactory::getURI()->toString().'" />');
		}
        
	 $preset_name = "preset1";
	 $offset = 0;
		if($preset == 2){
				$preset_name = "preset2";
				$offset = $icon_size;
		}
		if($preset == 3){
				$preset_name = "preset3";
				$offset = 2*$icon_size;
		}
		if($preset == 4){
			$preset_name = "preset4";
			$offset = 3*$icon_size;
		}
		if($preset == 5){
			$preset_name = "preset5";
			$offset = 4*$icon_size;
		}
		if($preset == 6){
			$preset_name = "preset6";
			$offset = 6*$icon_size;
		}
		if($preset == 7){
			$preset_name = "preset7";
		}
		if($preset == 8){
			$preset_name = "preset8";
			$offset = 7*$icon_size;
		}
		if($preset == 9){
			$preset_name = "preset9";
			$offset = 8*$icon_size;
		}
		if($preset == 10){
			$preset_name = "preset10";
			$offset = 0;
		}
		
    $js='';
		$css='';
		 
		$css .= 'div.b2jsocial_parent {';
		$css .= 'text-align:'.$align.';';
		$css .= '}';

    if($preset != 7 && $preset != 10){
    		if ($buttons == 'both'){
    			
    			$css .= 'ul.b2jsocial li {';
    			$css .= 'width:'.($icon_size+65).'px;';
    			$css .= 'height:'.($icon_size+10).'px;';
    			$css .= '}';
    			
    			$css .= 'ul.b2jsocial li a{';
    			$css .= 'width:'.($icon_size+65).'px;';
    			$css .= 'height:'.($icon_size+10).'px;';
    			$css .= '}';
    			
    			$css .= 'ul.b2jsocial li span.text{';
    			$css .= 'display:inline;';
    			$css .= 'margin-left:15px !important;';
    			$css .= '}';
                
                //--//
    			
    			$css .= 'ul.b2jsocial li.f span.background {';
    			$css .= 'width:'.$icon_size.'px;';
    			$css .= 'height:'.$icon_size.'px;';
					$css .= 'transition: all 0.3s ease 0s;';
					$css .= '-webkit-transition: all 0.3s ease 0s;';
    			$css .= 'background:url("'.JURI::root().'plugins/content/b2jsocial/images/icons'.$icon_size.'.png") -'.$icon_size.'px -'.$offset.'px  no-repeat';
    			$css .= '}';
                
					$css .= 'ul.b2jsocial li.f.preset5 span.background:hover {';
					$css .= 'background-position:-'.$icon_size.'px -'.($offset + $icon_size).'px;';
					$css .= 'transition: all 0.3s ease 0s;';
					$css .= '-webkit-transition: all 0.3s ease 0s;';
					$css .= '}';
                
                //--//
    			
    			$css .= 'ul.b2jsocial li.t span.background {';
    			$css .= 'width:'.$icon_size.'px;';
    			$css .= 'height:'.$icon_size.'px;';
					$css .= 'transition: all 0.3s ease 0s;';
					$css .= '-webkit-transition: all 0.3s ease 0s;';
    			$css .= 'background:url("'.JURI::root().'plugins/content/b2jsocial/images/icons'.$icon_size.'.png") -'.($icon_size*0).'px -'.$offset.'px  no-repeat';
    			$css .= '}';
                
					$css .= 'ul.b2jsocial li.t.preset5 span.background:hover {';
					$css .= 'background-position:-'.($icon_size*0).'px -'.($offset + $icon_size).'px;';
					$css .= 'transition: all 0.3s ease 0s;';
					$css .= '-webkit-transition: all 0.3s ease 0s;';
					$css .= '}';
                
                //--//
    			
    			$css .= 'ul.b2jsocial li.g span.background {';
    			$css .= 'width:'.$icon_size.'px;';
    			$css .= 'height:'.$icon_size.'px;';
					$css .= 'transition: all 0.3s ease 0s;';
					$css .= '-webkit-transition: all 0.3s ease 0s;';
    			$css .= 'background:url("'.JURI::root().'plugins/content/b2jsocial/images/icons'.$icon_size.'.png") -'.($icon_size*2).'px -'.$offset.'px  no-repeat';
    			$css .= '}';
                
					$css .= 'ul.b2jsocial li.g.preset5 span.background:hover {';
					$css .= 'background-position:-'.($icon_size*2).'px -'.($offset + $icon_size).'px;';
					$css .= 'transition: all 0.3s ease 0s;';
					$css .= '-webkit-transition: all 0.3s ease 0s;';
					$css .= '}';
                
                //--//
    			
    			$css .= 'ul.b2jsocial li.l span.background {';
    			$css .= 'width:'.$icon_size.'px;';
    			$css .= 'height:'.$icon_size.'px;';
					$css .= 'transition: all 0.3s ease 0s;';
					$css .= '-webkit-transition: all 0.3s ease 0s;';
    			$css .= 'background:url("'.JURI::root().'plugins/content/b2jsocial/images/icons'.$icon_size.'.png") -'.($icon_size*3).'px -'.$offset.'px  no-repeat';
    			$css .= '}';
                
					$css .= 'ul.b2jsocial li.l.preset5 span.background:hover {';
					$css .= 'background-position:-'.($icon_size*3).'px -'.($offset + $icon_size).'px;';
					$css .= 'transition: all 0.3s ease 0s;';
					$css .= '-webkit-transition: all 0.3s ease 0s;';
					$css .= '}';
    			
                //--//
                
    			$css .= 'ul.b2jsocial li.e span.background {';
    			$css .= 'width:'.$icon_size.'px;';
    			$css .= 'height:'.$icon_size.'px;';
					$css .= 'transition: all 0.3s ease 0s;';
					$css .= '-webkit-transition: all 0.3s ease 0s;';
    			$css .= 'background:url("'.JURI::root().'plugins/content/b2jsocial/images/icons'.$icon_size.'.png") -'.($icon_size*4).'px -'.$offset.'px  no-repeat';
    			$css .= '}';
                
					$css .= 'ul.b2jsocial li.e.preset5 span.background:hover {';
					$css .= 'background-position:-'.($icon_size*4).'px -'.($offset + $icon_size).'px;';
					$css .= 'transition: all 0.3s ease 0s;';
					$css .= '-webkit-transition: all 0.3s ease 0s;';
					$css .= '}';
                
                //--//
                
                $css .= 'ul.b2jsocial li.p span.background {';
    			$css .= 'width:'.$icon_size.'px;';
    			$css .= 'height:'.$icon_size.'px;';
					$css .= 'transition: all 0.3s ease 0s;';
					$css .= '-webkit-transition: all 0.3s ease 0s;';
    			$css .= 'background:url("'.JURI::root().'plugins/content/b2jsocial/images/icons'.$icon_size.'.png") -'.($icon_size*5).'px -'.$offset.'px  no-repeat';
    			$css .= '}';
                
					$css .= 'ul.b2jsocial li.p.preset5 span.background:hover {';
					$css .= 'background-position:-'.($icon_size*5).'px -'.($offset + $icon_size).'px;';
					$css .= 'transition: all 0.3s ease 0s;';
					$css .= '-webkit-transition: all 0.3s ease 0s;';
					$css .= '}';
    		}
    		
    		if ($buttons == 'text'){
            
    			$css .= 'ul.b2jsocial li span.text{';
    			$css .= 'display:inline;';
    			$css .= '}';
    						
    			$css .= 'ul.b2jsocial li a{';
    			$css .= 'text-align:center';
    			$css .= '}';
    		}
    		
    		if ($buttons == 'icons'){
					
					if ($share_counts){
							$extra_width = '36';
							$left = '46';
						if ($icon_size == 16){
							$extra_width = '36';
							$left = '27';
						} else if ($icon_size == 32){
							$extra_width = '42';
							$left = '46';
						} else if ($icon_size == 64){
							$extra_width = '40';
							$left = '72';
						}
						$css .= 'ul.b2jsocial li span.social_count {';
						$css .= 'display:block;';
						$css .= 'color:'. $count_color . ";";
						$css .= 'border-color:'. $count_color . ";";
						$css .= 'left:'.$left.'px;';
						$css .= '}';
						$css .= 'ul.b2jsocial li span.social_count:before {';
						$css .= 'border-left-color:'. $count_color . ";";
						$css .= '}';
						$css .= 'ul.b2jsocial li span.background {';
						$css .= 'left:0';
						$css .= '}';
					} else {
						$extra_width = '0';
					}
    		    		
    			$css .= 'ul.b2jsocial li {';
    			$css .= 'width:'.($icon_size+10 + $extra_width).'px;';
    			$css .= 'height:'.($icon_size+10).'px;';
    			$css .= '}';
    			
    			$css .= 'ul.b2jsocial li a{';
    			$css .= 'width:'.($icon_size+10 + $extra_width).'px;';
    			$css .= 'height:'.($icon_size+10).'px;';
    			$css .= '}';
                
                 //--//
    			
    			$css .= 'ul.b2jsocial li.f span.background {';
					$css .= 'width:'.$icon_size.'px;';
					$css .= 'height:'.$icon_size.'px;';
					$css .= 'transition: all 0.3s ease 0s;';
					$css .= '-webkit-transition: all 0.3s ease 0s;';
					$css .= 'background:url("'.JURI::root().'plugins/content/b2jsocial/images/icons'.$icon_size.'.png") -'.$icon_size.'px -'.$offset.'px  no-repeat';
					$css .= '}';
					
					$css .= 'ul.b2jsocial li.f.preset5 span.background:hover {';
					$css .= 'background-position:-'.$icon_size.'px -'.($offset + $icon_size).'px;';
					$css .= 'transition: all 0.3s ease 0s;';
					$css .= '-webkit-transition: all 0.3s ease 0s;';
					$css .= '}';
                
                //--//
    			
    			$css .= 'ul.b2jsocial li.t span.background {';
    			$css .= 'width:'.$icon_size.'px;';
    			$css .= 'height:'.$icon_size.'px;';
					$css .= 'transition: all 0.3s ease 0s;';
					$css .= '-webkit-transition: all 0.3s ease 0s;';
    			$css .= 'background:url("'.JURI::root().'plugins/content/b2jsocial/images/icons'.$icon_size.'.png") -'.($icon_size*0).'px -'.$offset.'px  no-repeat';
    			$css .= '}';
                
					$css .= 'ul.b2jsocial li.t.preset5 span.background:hover {';
					$css .= 'background-position:-'.($icon_size*0).'px -'.($offset + $icon_size).'px;';
					$css .= 'transition: all 0.3s ease 0s;';
					$css .= '-webkit-transition: all 0.3s ease 0s;';
					$css .= '}';
                
                //--//
    			
    			$css .= 'ul.b2jsocial li.g span.background {';
    			$css .= 'width:'.$icon_size.'px;';
    			$css .= 'height:'.$icon_size.'px;';
					$css .= 'transition: all 0.3s ease 0s;';
					$css .= '-webkit-transition: all 0.3s ease 0s;';
    			$css .= 'background:url("'.JURI::root().'plugins/content/b2jsocial/images/icons'.$icon_size.'.png") -'.($icon_size*2).'px -'.$offset.'px  no-repeat';
    			$css .= '}';
                
					$css .= 'ul.b2jsocial li.g.preset5 span.background:hover {';
					$css .= 'background-position:-'.($icon_size*2).'px -'.($offset + $icon_size).'px;';
					$css .= 'transition: all 0.3s ease 0s;';
					$css .= '-webkit-transition: all 0.3s ease 0s;';
					$css .= '}';
                
                //--//
    			
    			$css .= 'ul.b2jsocial li.l span.background {';
    			$css .= 'width:'.$icon_size.'px;';
    			$css .= 'height:'.$icon_size.'px;';
					$css .= 'transition: all 0.3s ease 0s;';
					$css .= '-webkit-transition: all 0.3s ease 0s;';
    			$css .= 'background:url("'.JURI::root().'plugins/content/b2jsocial/images/icons'.$icon_size.'.png") -'.($icon_size*3).'px -'.$offset.'px  no-repeat';
    			$css .= '}';
                
					$css .= 'ul.b2jsocial li.l.preset5 span.background:hover {';
					$css .= 'background-position:-'.($icon_size*3).'px -'.($offset + $icon_size).'px;';
					$css .= 'transition: all 0.3s ease 0s;';
					$css .= '-webkit-transition: all 0.3s ease 0s;';
					$css .= '}';
    			
                //--//
                
    			$css .= 'ul.b2jsocial li.e span.background {';
    			$css .= 'width:'.$icon_size.'px;';
    			$css .= 'height:'.$icon_size.'px;';
					$css .= 'transition: all 0.3s ease 0s;';
					$css .= '-webkit-transition: all 0.3s ease 0s;';
    			$css .= 'background:url("'.JURI::root().'plugins/content/b2jsocial/images/icons'.$icon_size.'.png") -'.($icon_size*4).'px -'.$offset.'px  no-repeat';
    			$css .= '}';
                
					$css .= 'ul.b2jsocial li.e.preset5 span.background:hover {';
					$css .= 'background-position:-'.($icon_size*4).'px -'.($offset + $icon_size).'px;';
					$css .= 'transition: all 0.3s ease 0s;';
					$css .= '-webkit-transition: all 0.3s ease 0s;';
					$css .= '}';
                
                //--//
                
					$css .= 'ul.b2jsocial li.p span.background {';
    			$css .= 'width:'.$icon_size.'px;';
    			$css .= 'height:'.$icon_size.'px;';
					$css .= 'transition: all 0.3s ease 0s;';
					$css .= '-webkit-transition: all 0.3s ease 0s;';
    			$css .= 'background:url("'.JURI::root().'plugins/content/b2jsocial/images/icons'.$icon_size.'.png") -'.($icon_size*5).'px -'.$offset.'px  no-repeat';
    			$css .= '}';
                
					$css .= 'ul.b2jsocial li.p.preset5 span.background:hover {';
					$css .= 'background-position:-'.($icon_size*5).'px -'.($offset + $icon_size).'px;';
					$css .= 'transition: all 0.3s ease 0s;';
					$css .= '-webkit-transition: all 0.3s ease 0s;';
					$css .= '}';
    		}
    } else if($preset == 7){
					$css .= 'ul.b2jsocial li.e span.background {';
					$css .= 'width:74px;';
					$css .= 'height:20px;';
					$css .= 'transition: all 0.3s ease 0s;';
					$css .= '-webkit-transition: all 0.3s ease 0s;';
					$css .= 'background:url("'.JURI::root().'plugins/content/b2jsocial/images/email_icon.png") no-repeat';
					$css .= '}';

					// facebook javascirpt

					$js .= "(function(d, s, id) {";
					$js .= "var js, fjs = d.getElementsByTagName(s)[0];";
					$js .= "if (d.getElementById(id)) return;";
					$js .= "js = d.createElement(s); js.id = id;";
					$js .= "js.src = '//connect.facebook.net/en_GB/all.js#xfbml=1&appId=371410889631698';";
					$js .= "fjs.parentNode.insertBefore(js, fjs);";
					$js .= '}(document, "script", "facebook-jssdk"));';

					// twitter javascirpt
					$js .= '!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");';
		} else {

					$css .= 'ul.b2jsocial{';
					$css .= 'width:100%';
					$css .= '}';
					
					$array_true = array($f, $t, $g, $l, $e, $p);
					$array_true = array_filter($array_true);
					$elem_count = count($array_true);
					
					$array_bootstrap = array(0=>'100', 1=>'100', 2=>'50', 3=>'33.3', 4=>'25', 5=>'20', 6=>'16.6');
					
					$css .= 'ul.b2jsocial li{';
					$css .= 'width:'.$array_bootstrap[$elem_count].'%;';
					$css .= 'height:auto;';
					$css .= 'transition: all 0.3s ease 0s;';
					$css .= 'text-align:center;';
					$css .= '-webkit-transition: all 0.3s ease 0s;';
					$css .= '}';
					
					$css .= 'ul.b2jsocial li a{';
					$css .= 'width:auto;';
					$css .= 'height:auto;';
					$css .= 'display:block;';
					$css .= 'background:'. $background_color.';';
					$css .= 'margin-right:10px;';
					$css .= '}';
					
					$css .= 'ul.b2jsocial li a:after{';
					$css .= 'font-size:'. $icon_font_size.'px;';
					$css .= 'color:'. $icon_color.';';
					$css .= '}';
		}
		
		$uri = JURI::getInstance();
		$domain = $uri->toString(array('scheme', 'host', 'port'));
		$item_url = $domain.$item_link;
		if ($context == 'redShop'){
				$item_url = $item_link;
		}
		
		$doc = JFactory::getDocument();
		$doc->addStyleDeclaration($css);
		$doc->addScriptDeclaration($js); 
		
		$app = JFactory::getApplication();
		$template = $app->getTemplate();
		$email_link = JRoute::_('index.php?option=com_mailto&tmpl=component&template='.$template.'&link='.MailToHelper::addLink($item_url));	
		if($preset!= 7 && $preset!= 10){
			// all except default and custom
			if($share_counts){
				if ($f) {
					$fb_count = $this->shorten_counts($this->getFBShareCount($item_url));
				} else {
					$fb_count = -1;
				}
				if ($t) {
					$tw_count = $this->shorten_counts($this->getTweetCount($item_url));
				} else {
					$tw_count = -1;
				}
				if ($g) {
					$gp_count = $this->shorten_counts($this->get_plusones($item_url));
				} else {
					$gp_count = -1;
				}
				if ($l) {
					$ld_count = $this->shorten_counts($this->getLinkedInCount($item_url));
				} else {
					$ld_count = -1;
				}
				if ($p) {
					$pt_count = $this->shorten_counts($this->get_pins($item_url));
				} else {
					$pt_count = -1;
				}
			} else {
				$fb_count = -1;
				$tw_count = -1;
				$gp_count = -1;
				$ld_count = -1;
				$pt_count = -1;
			}
				$fb_count_class = "";
				$tw_count_class = "";
				$gp_count_class = "";
				$ld_count_class = "";
				$pt_count_class = "";
				if ($fb_count < 0){
					$fb_count_class = "hide";
				}
				if ($tw_count < 0){
					$tw_count_class = "hide";
				}
				if ($gp_count < 0){
					$gp_count_class = "hide";
				}
				if ($ld_count < 0){
					$ld_count_class = "hide";
				}
				if ($pt_count < 0){
					$pt_count_class = "hide";
				}
				$item_url = urlencode($item_url);
				$f_cont = '<li class="f '.$preset_name.'"><a class="'.$preset_name.'" href="http://www.facebook.com/sharer.php?u='.$item_url.'" target="_blank"><span class="text">'.JText::_('PLG_B2J_SOCIAL_FACEBOOK_TEXT').'</span><span class="background"></span><span class="social_count '.$fb_count_class.'">'.$fb_count.'</span></a></li>';
				$t_cont = '<li class="t '.$preset_name.'"><a class="'.$preset_name.'" href="http://twitter.com/intent/tweet?text='.$twitter_title.'&url='.$item_url.'&via='.$t_name.'" target="_blank"><span class="text">'.JText::_('PLG_B2J_SOCIAL_TWITTER_TEXT').'</span><span class="background"></span><span class="social_count '.$tw_count_class.'">'.$tw_count.'</span></a></li>';
				$g_cont = '<li class="g '.$preset_name.'"><a class="'.$preset_name.'" href="http://plus.google.com/share?url='.$item_url.'" target="_blank"><span class="text">'.JText::_('PLG_B2J_SOCIAL_GOOGLE_TEXT').'</span><span class="background"></span><span class="social_count '.$gp_count_class.'">'.$gp_count.'</span></a></li>';
				$l_cont = '<li class="l '.$preset_name.'"><a class="'.$preset_name.'" href="http://www.linkedin.com/shareArticle?mini=true&url='.$item_url.'&title='.$title.'" target="_blank"><span class="text">'.JText::_('PLG_B2J_SOCIAL_LINKEDIN_TEXT').'</span><span class="background"></span><span class="social_count '.$ld_count_class.'">'.$ld_count.'</span></a></li>';
				$e_cont = '<li class="e '.$preset_name.'"><a class="'.$preset_name.'" href="'.$email_link.'" onclick="window.open(this.href,\'emailWindow\',\'width=800,height=600,location=no,menubar=no,resizable=no,scrollbars=no\'); return false;"><span class="text">'.JText::_('PLG_B2J_SOCIAL_EMAIL_TEXT').'</span><span class="background"></span><span class="social_count hide"></span></a></li>';
				$p_cont = '<li class="p '.$preset_name.'"><a class="'.$preset_name.'" href="http://pinterest.com/pin/create/button/?url='.$item_url.'&media='.$image_url.'&description='.$title.'" target="_blank"><span class="text">'.JText::_('PLG_B2J_SOCIAL_PINTEREST_TEXT').'</span><span class="background"></span><span class="social_count '.$pt_count_class.'">'.$pt_count.'</span></a></li>';
		}else if ($preset == 7){
				// default
				$f_cont = '<li class="f '.$preset_name.'"><div class="fb-like" data-href="'.$item_url.'" data-width="450" data-layout="button_count" data-show-faces="true" data-send="false"></div></li>';
				$t_cont = '<li class="t '.$preset_name.'"><a href="https://twitter.com/share" class="twitter-share-button" data-size="small" data-text="'.$twitter_title.'" data-url="'.$item_url.'" data-via="'.$t_name.'" data-lang="en">Tweet</a></li>';
				$g_cont = '<li class="g '.$preset_name.'"><div class="g-plus" data-annotation="bubble" data-action="share" data-height="20"></div><script type="text/javascript">(function() {var po = document.createElement("script"); po.type = "text/javascript"; po.async = true; po.src = "https://apis.google.com/js/plusone.js"; var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(po, s); })(); </script></li>';
				$l_cont = '<li class="l '.$preset_name.'"><script src="//platform.linkedin.com/in.js" type="text/javascript">lang: en_US</script><script type="IN/Share" data-url="'.$image_url.'" data-counter="right"></script></li>';
				$e_cont = '<li class="e '.$preset_name.'"><a class="'.$preset_name.'" href="'.$email_link.'" onclick="window.open(this.href,\'emailWindow\',\'width=800,height=600,location=no,menubar=no,resizable=no,scrollbars=no\'); return false;"><span class="background"></span></a></li>';
				$p_cont = '<li class="p '.$preset_name.'"><a data-pin-config="beside" href="//pinterest.com/pin/create/button/?url='.$item_url.'&media='.$image_url.'&description='.$title.'" data-pin-do="buttonPin" ><img src="//assets.pinterest.com/images/pidgets/pin_it_button.png" /></a><script type="text/javascript" src="//assets.pinterest.com/js/pinit.js"></script></li>';
		} else {
				//custom
				$item_url = urlencode($item_url);
				$f_cont = '<li class="f '.$preset_name.'"><a class="'.$preset_name.'" href="http://www.facebook.com/sharer.php?u='.$item_url.'" target="_blank"></a></li>';
				$t_cont = '<li class="t '.$preset_name.'"><a class="'.$preset_name.'" href="http://twitter.com/intent/tweet?text='.$twitter_title.'&url='.$item_url.'&via='.$t_name.'" target="_blank"></a></li>';
				$g_cont = '<li class="g '.$preset_name.'"><a class="'.$preset_name.'" href="http://plus.google.com/share?url='.$item_url.'" target="_blank"></a></li>';
				$l_cont = '<li class="l '.$preset_name.'"><a class="'.$preset_name.'" href="http://www.linkedin.com/shareArticle?mini=true&url='.$item_url.'&title='.$title.'" target="_blank"></a></li>';
				$e_cont = '<li class="e '.$preset_name.'"><a class="'.$preset_name.'" href="'.$email_link.'" onclick="window.open(this.href,\'emailWindow\',\'width=800,height=600,location=no,menubar=no,resizable=no,scrollbars=no\'); return false;"></a></li>';
				$p_cont = '<li class="p '.$preset_name.'"><a class="'.$preset_name.'" href="http://pinterest.com/pin/create/button/?url='.$item_url.'&media='.$image_url.'&description='.$title.'" target="_blank"></a></li>';
		}

		$seq = array();
		if ($f) {
			$seq[$f_pos] = $f_cont;
		}
		if ($t) {
			$seq[$t_pos] = $t_cont;
		}
		if ($g) {
			$seq[$g_pos] = $g_cont;
		}
		if ($l) {
			$seq[$l_pos] = $l_cont;
		}
		if ($e) {
			$seq[$e_pos] = $e_cont;
		}
		if ($p) {
			$seq[$p_pos] = $p_cont;
		}
		
		ksort($seq);
		
		$social = '<div class="b2jsocial_parent">';
		$social .= '<ul class="b2jsocial">';
		foreach($seq as $li){
			$social .= $li;
		}
		$social.='</ul>';
		$social.='</div>';

		return $social;
	}
}
?>