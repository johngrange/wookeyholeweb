<?php
/**
 * @package Sj News Scrollbar
 * @version 2.5
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2013 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 * 
 */
defined('_JEXEC') or die;
JHtml::stylesheet('templates/' . JFactory::getApplication()->getTemplate().'/html/mod_sj_news_scrollbar/css/styles.css');
//JHtml::stylesheet('templates/' . JFactory::getApplication()->getTemplate().'/html/mod_sj_news_scrollbar/css/jquery.mCustomScrollbar.css');

if( !defined('SMART_JQUERY') && $params->get('include_jquery', 0) == "1" ){
	JHtml::script('modules/'.$module->module.'/assets/js/jquery-1.8.2.min.js');
	JHtml::script('modules/'.$module->module.'/assets/js/jquery-noconflict.js');
	define('SMART_JQUERY', 1);
}
JHtml::script('modules/'.$module->module.'/assets/js/jquery.mCustomScrollbar.concat.min.js');

$show_arrows = $params->get('show_arrows', 1);
$show_arrows = ($show_arrows)?'true':'false';

	if ($params->get('pretext', '') != ''){
		echo '<div class="text-block">' . $params->get('pretext', '') . '</div>';
	}
	
	if (count($list)){
		$layoutname = $layout.'_';
		$layoutname .= $params->get('theme', 'horizontal');
		require JModuleHelper::getLayoutPath($module->module, $layoutname);
	} else {
		?>
		<p>There no item matching selection.</p>
		<?php
	}

	if ($params->get('posttext', '') != ''){
		echo '<div class="text-block">' . $params->get('posttext', '') . '</div>';
	}
	?>