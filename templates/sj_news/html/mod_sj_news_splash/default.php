<?php
/**
 * @package Sj Content Accordion
 * @version 2.5
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2012 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 *
 */
defined('_JEXEC') or die;

if(!empty($list)){
	 JHtml::stylesheet('templates/' . JFactory::getApplication()->getTemplate().'/html/mod_sj_news_splash/css/sj-splash.css');

	$uniquied = 'sj_splash_'.time().rand();
	//JHtml::stylesheet('modules/'.$module->module.'/assets/css/sj-splash.css');
	if( !defined('SMART_JQUERY') && $params->get('include_jquery', 0) == "1" ){
		JHtml::script('modules/'.$module->module.'/assets/js/jquery-1.8.2.min.js');
		JHtml::script('modules/'.$module->module.'/assets/js/jquery-noconflict.js');
		define('SMART_JQUERY', 1);
	}
	
	JHtml::script('modules/'.$module->module.'/assets/js/jcarousel.js');
	
	$start = $params->get('start', 1);
	if ($start <= 0 || ($start > count($list))){
		$start = 1;
	}
	
	$play = $params->get('play', 1);
	if (!$play){
		$interval = 0;
	} else {
		$interval = $params->get('interval', 1000);
	}	
?>
	<div id="<?php echo $uniquied; ?>" class="sj-splash <?php echo ($params->get('effect_type') == 'vertical')?' vertical':'';?>  <?php if( $params->get('effect') == 'slide' ){ echo $params->get('effect');}?>" data-interval="<?php echo $interval; ?>" data-pause="<?php echo $params->get('pause_hover'); ?>">
		<?php if($params->get('module_title_display') == 1 && $params->get('module_title') != ''){?>
		<div class="spl-title google-font">
			<span class="spl-title-inner"><?php echo $params->get('module_title'); ?></span>
		</div>
		<?php }?>
		<div class="spl-items">
			<div class="spl-items-inner">
				<?php $i=0; foreach($list as $item) { $i++;
					$active = ($i == $start)?' active':'';
				?>
				<div class="spl-item  item  <?php echo $active; ?>" data-href="<?php echo $item->link; ?>" >
					<?php if($params->get('item_date_display')) {?>
					<span class="spl-item-date">
					<?php if($params->get('article_time_style') == 'layout1') : ?> <?php else : ?>(<?php endif; ?><?php echo JHTML::_('date', $item->created, "d/m") ?><?php if($params->get('article_time_style')=='layout2') : ?>)<?php endif; ?> &nbsp;
					</span>
					<?php }?>

					<?php if($params->get('item_title_display') == 1) {?>
					<span class="spl-item-title">
						<a href="<?php echo $item->link; ?>" title="<?php echo $item->title; ?>" <?php echo SjNewsSplashHelper::parseTarget($params->get('link_target')); ?> >
							<?php echo SjNewsSplashHelper::truncate($item->title, $params->get('item_title_max_characters')); ?> - 
						</a>
					</span>
					<?php }?>

					<?php if($params->get('item_description_display') == 1) {?>
					<span class="spl-item-desc">"
						<?php echo SjNewsSplashHelper::truncate($item->introtext, $params->get('item_description_max_characters')); ?>"
					</span>
					<?php } ?>
					
				</div>
				<?php }?>
			</div>
		</div>
		<?php if($params->get('controls') == 1){?>
		<div class="spl-control">
			<ul class="spl-control-inner">
				<li class="control-prev" href="#<?php echo $uniquied; ?>" data-jslide="prev"></li>
				<li class="control-next" href="#<?php echo $uniquied; ?>" data-jslide="next"></li>
			</ul>
		</div>
		<?php }?>
	</div>	
	
<script>
//<![CDATA[    					
	jQuery(function($){
		;(function(element){
			var $element = $(element);
			$element.each(function(){
				var $this = $(this), options = options = !$this.data('modal') && $.extend({}, $this.data());
				$this.jcarousel(options);
				$this.bind('jslide', function(e){
					var index = $(this).find(e.relatedTarget).index();
	
					// process for nav
					$('[data-jslide]').each(function(){
						var $nav = $(this), $navData = $nav.data(), href, $target = $($nav.attr('data-target') || (href = $nav.attr('href')) && href.replace(/.*(?=#[^\s]+$)/, ''));
						if ( !$target.is($this) ) return;
						if (typeof $navData.jslide == 'number' && $navData.jslide==index){
							$nav.addClass('sel');
						} else {
							$nav.removeClass('sel');
						}
					});
	
				});
			});
			return ;
			
		})('#<?php echo $uniquied; ?>');
	});
//]]>	
</script>
	
	
<?php } else {
	echo JText::_('Has no content to show!');	
}?>
