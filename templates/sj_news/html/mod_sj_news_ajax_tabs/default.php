<?php
/**
 * @package Sj News Ajax Tabs
 * @version 2.5
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2012 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 *
 */
defined('_JEXEC') or die;

JHtml::stylesheet('templates/' . JFactory::getApplication()->getTemplate().'/html/mod_sj_news_ajax_tabs/css/ajax-tabs.css');
JHtml::stylesheet('templates/' . JFactory::getApplication()->getTemplate().'/html/mod_sj_news_ajax_tabs/css/ajax-tabs-color.css');

if( !defined('SMART_JQUERY') && $params->get('include_jquery', 0) == "1" ){
	JHtml::script('modules/mod_sj_news_ajax_tabs/assets/js/jquery-1.8.2.min.js');
	JHtml::script('modules/mod_sj_news_ajax_tabs/assets/js/jquery-noconflict.js');
	define('SMART_JQUERY', 1);
}
JHtml::script('modules/mod_sj_news_ajax_tabs/assets/js/jsmart.easing.1.3.js');
JHtml::script('modules/mod_sj_news_ajax_tabs/assets/js/jsmart.cookie.js');
JHtml::script('modules/mod_sj_news_ajax_tabs/assets/js/jquery.mousewheel.js');
JHtml::script('modules/mod_sj_news_ajax_tabs/assets/js/jquery.tabsmart.js');
JHtml::script('modules/mod_sj_news_ajax_tabs/assets/js/jcarousel.js');

$instance	= rand().time();
ImageHelper::setDefault($params);
$uniqueid = 'ajaxtabs_'.rand().time();
$options = $params->toObject();
$css_posiotion = $params->get('position', 'top') . '-position';

// check category loaded.
if( $list != null ){
	foreach ($list as $first_category){
		$category_preload = $first_category->id;
		break;
	}
	
	$preload_cookie = $module->module . '_' . $module->id;
	if( JRequest::getInt($preload_cookie, -1, 'cookie') != -1){
		$preload_cookie_value = (int)JRequest::getInt($preload_cookie, -1, 'cookie');
		if (isset($list[$preload_cookie_value])){
			$category_preload = $preload_cookie_value;
		}
	} else if( (int)$params->get('category_preload') && isset($list[ (int)$params->get('category_preload') ])) {
		$category_preload = (int)$params->get('category_preload');
	}
	// get $tabs_markup
	include JModuleHelper::getLayoutPath($module->module, 'tabs');
	
	if ($params->get('pretext', '')!=''): ?>
	<div class="pre-text"><?php echo $params->get('pretext'); ?></div>
	<?php
	endif; ?>
	
	<div id="<?php echo $uniqueid; ?>" class="sj-ajax-tabs <?php echo $css_posiotion?>">
	<?php
		// show tabs here if (top, left);
		if (in_array($params->get('position', 'top'), array('top', 'left'))){
			echo $tabs_markup;
		}?>
		<div class="tabs-content" <?php //echo $tabs_content_style; ?>>
			<div class="tabs-content-inner">
			<?php
			foreach($list as $category):
				// test category is preload
				$is_preload = $category->id == $category_preload;
				$css_selected = $is_preload ? "tab-content category-id-{$category->id} selected" : "tab-content category-id-{$category->id}"; ?>
				<?php //var_dump($css_selected); die("ancnc");?>
				<div class="<?php echo $css_selected; ?>">
				<?php
					if ($is_preload){
						$category_items = & $category->child;
						include JModuleHelper::getLayoutPath($module->module, 'items');
					} else { ?>
						<div class="clear"></div>
						<div class="ajax-loader"></div>
				<?php
					} ?>
					<div class="clear"></div>
				</div>
			<?php
			endforeach; ?>
			<div class="clear"></div>
			</div>
		</div>
	<?php
		// show tabs here if (right, bottom);
		if (in_array($params->get('position', 'top'), array('right', 'bottom'))){
			echo $tabs_markup;
		}
	?>
	
	</div>
	<?php
	if ($params->get('posttext', '')!=''): ?>
	<div class="post-text"><?php echo $params->get('posttext'); ?></div>
	<?php
	endif; ?>
	<script type="text/javascript">
		//<![CDATA[
		jQuery(document).ready(function($){
			$('<?php echo "#$uniqueid"; ?>').tabsmart({
				sj_module: '<?php echo $module->module; ?>',
				sj_module_id: '<?php echo $module->id; ?>',
				ajax_url : '<?php echo (string)JURI::getInstance(); ?>',
			    ajaxUpdate: function(element, options){
					var loaded = $(element).hasClass('loaded');
					if ( !loaded ){
						var loading = $('.ajax-loader', element);
						// show ajax indicator
						loading = loading.first();
						var loading_top = ($(element).parent().height() - loading.height())/2;
						var loading_left = ($(element).parent().width() - loading.width())/2;
						loading.css('padding', loading_top + 'px 0 0 ' + loading_left + 'px');
						
						var category_id = $(element).attr('class');
						category_id = category_id.replace('selected', '');
						category_id = category_id.replace('tab-content', '');
						category_id = category_id.replace('category-id-', '');
						category_id = $.trim(category_id);
	
						
						ajax_options = {
							sj_category_id:	category_id,
							sj_module_id:	options.sj_module_id,
							sj_module:		options.sj_module,
							sj_module_ajax_request: 1
						};
						$.ajax({
							type: 'POST',
							url : options.ajax_url,
							data: ajax_options,
							success: function(data, textStatus, jqXHR){
								// save this for cookie.
								// console.log(ajax_options);
								var cookie_name = ajax_options.sj_module + '_' + ajax_options.sj_module_id;
								var cookie_value = ajax_options.sj_category_id;
								$.fn.cookie(cookie_name, cookie_value, 1);
	
								$(element).html(data).attr('title', null);
								//$(element).jsmart_slider(options);
								$(element).addClass('loaded');
							}
						});
					}
				}  ,
				
				//event: '<?php  //echo $params->get('pager_event', 'click'); ?>', 
				//duration: <?php //echo (int)$params->get('slide_speed', 400); ?> 
			});
	
		});
		//]]>
	</script>
<?php } else { echo JText::_('Has no content to show!'); }?>