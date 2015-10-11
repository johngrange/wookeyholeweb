<?php
/**
 * @package Sj Slideshow
 * @version 2.5
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2012 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 *
 */
defined('_JEXEC') or die;
$app = & JFactory::getApplication();
$template = $app->getTemplate();
JHtml::stylesheet('templates/'.$template.'/html/'.$module->module.'/slideshow.css');

//JHtml::stylesheet('modules/mod_sj_slideshow/assets/css/slideshow.css');
if( !defined('SMART_JQUERY') && $params->get('include_jquery', 0) == "1" ){
	JHtml::script('modules/mod_sj_slideshow/assets/js/jquery-1.8.2.min.js');
	JHtml::script('modules/mod_sj_slideshow/assets/js/jquery-noconflict.js');
	define('SMART_JQUERY', 1);
}
JHtml::script('modules/mod_sj_slideshow/assets/js/jcarousel.js');
ImageHelper::setDefault($params);

if(!empty($list)) {
	
	$instance	= rand().time();
	$options=$params->toObject();
	
	$start = $params->get('start', 1);
	if ($start <= 0 || ($start > count($list))){
		$start = 1;
	}
	$start_item = &$list[$start - 1];
	$play = $params->get('play', 1);
	if (!$play){
		$interval = 0;
	} else {
		$interval = $params->get('interval', 5000);
	}	
if(!empty($options->pretext)) { ?>
<div class="pre-text">
	<?php echo $options->pretext; ?>
</div>
<?php } ?>
<div class="slideshow theme1 <?php if( $options->effect == 'slide' ){ echo $options->effect;}?>" id="slideshow_<?php echo $instance; ?>" data-interval="<?php echo $interval; ?>" data-pause="<?php echo $options->pause_hover; ?>">
	<div class="slideshow-inner">
		<?php $j = 0 ;
		foreach($list as $item) {
			$j++;?>
		<div class="sl-item <?php if($j == $start){echo "active";}?> item" data-href="<?php echo $item->link; ?>" data-caption="<?php echo $item->title; ?>">
			<div class="sl-item-image">
				<a href="<?php echo $item->link ?>" target="<?php echo $options->item_link_target ?>">
		    		<?php $img = SjSlideshowHelper::getAImage($item, $params);
    					echo SjSlideshowHelper::imageTag($img);?>
				</a>
			</div>
			<?php if( $options->show_introtext == 1 && !empty($item->displayIntrotext )){ ?>
			<div class="sl-item-info">
				<div class="transparency"></div>
				<div class="sl-item-content" >
					<?php if( $options->show_introtext == 1 ) { ?>
						<div class="sl-item-description">
							<?php echo $item->displayIntrotext; ?>
						</div>
					<?php } ?>
					<?php if( $options->item_readmore_display == 1 ) { ?>
					<div class="sl-item-readmore">
						<a href="<?php echo $item->link ?>" target="<?php echo $options->item_link_target ?>">
							<?php echo $options->item_readmore_text; ?>
						</a>
					</div>
					<?php } ?>
				</div>
			</div>
			<?php }?>
		</div><!--End sj-item-->
		<?php } ?>
	</div>
		<div class="sl-control">
			<div class="transparency"></div>
			<?php if( $options->item_title_display == 1 ) { ?>
				<div class="sl-captions">
					<a class="slide-caption" data-caption="<?php echo $start_item->title; ?>" href="<?php echo $start_item->link; ?>" target="<?php echo $options->item_link_target ?>"></a>
				</div>
			<?php } ?>
			<ul class="pag-list">
				<?php for( $i=0; $i<count($list); $i++ ): ?>
					<li class="pag-item <?php if( $i == $start-1 ){echo ' sel';}?>" href="<?php echo '#slideshow_'.$instance;?>" data-jslide="<?php echo $i;?>"></li>
				<?php endfor; ?>
			</ul>
		</div>
	
</div>
<?php if(!empty($options->posttext)) {  ?>
<div class="post-text">
	<?php echo $options->posttext; ?>
</div>
<?php }} else {?>
	<p><?php echo JText::_('Has no content to show!'); ?></p>
<?php }?>

<script>
//<![CDATA[    					
	jQuery(function($){
		$('#slideshow_<?php echo $instance; ?>').each(function(){
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

				// process change title
				var itemd = $(this).find(e.relatedTarget).data();
				$this.find('.slide-caption').attr({
					'data-caption': itemd.caption,
					'href': itemd.href
				});
			});
		});
		return ;
	});
//]]>	
</script>
