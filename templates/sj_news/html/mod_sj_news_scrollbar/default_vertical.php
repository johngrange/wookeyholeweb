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
$tag_id = 'sj-scrollbar'.rand().time();
ob_start();

$css = ob_get_contents();
?>
	#<?php echo $tag_id ?> .scrb-items{
		height:<?php echo $params->get('pane_height',380); ?>px;
	}

<?php 
$css = ob_get_contents();
ob_end_clean();
$document = JFactory::getDocument();
$document->addStyleDeclaration($css);

ImageHelper::setDefault($params);
$class_respl = 'respl01-'.$params->get('nb-column1',6).' respl02-'.$params->get('nb-column2',4).' respl03-'.$params->get('nb-column3',2).' respl04-'.$params->get('nb-column4',1) ?>


<!--[if lt IE 9]><div id="<?php echo $tag_id; ?>" class="scrollbar-wrap  msie lt-ie9"><![endif]--> 
<!--[if IE 9]><div id="<?php echo $tag_id; ?>" class="scrollbar-wrap msie"><![endif]-->
 <!--[if gt IE 9]><!--><div id="<?php echo $tag_id; ?>" class="scrollbar-wrap "><!--<![endif]--> 
	<div  class="scrb-items cf <?php echo  $class_respl; ?>">
	
		<h3 class="title google-font"><?php echo JText::_('MOST_READ_NEWS'); ?></h3>
		
		<?php $j=0; foreach($list as $item) { $j++; ?>
		<div class="scrb-item">
			<div class="scrb-item-inner">
				<div class="scrb-image">
					<?php
					$img = NewsScrollbarHelper::getAImage($item, $params); 
					if($img){
					?>
					<a href="<?php echo $item->link ?>" <?php echo NewsScrollbarHelper::parseTarget($params->get('link_target','_self'))?> title="<?php echo $item->title?>" >
						<?php echo NewsScrollbarHelper::imageTag($img); ?>
					</a>
					<?php } ?>
				</div>
				<div class="scrb-title">
					<a href="<?php echo $item->link ?>" <?php echo NewsScrollbarHelper::parseTarget($params->get('link_target','_self'))?> title="<?php echo $item->title?>" >
						<?php echo str_replace("...","", NewsScrollbarHelper::truncate($item->title, $params->get('item_title_max_characs', 20))); ?>
					</a>
				</div>
				<div class="scrb-desc">
					<?php echo $item->displayIntrotext; ?>
				</div>
				<?php if($params->get('item_readmore_display')){ ?>
				<div class="scrb-readmore">
					<a href="<?php echo $item->link ?>" <?php echo NewsScrollbarHelper::parseTarget($params->get('link_target','_self'))?> title="<?php echo $item->title?>" >
						<?php echo JText::_($params->get('item_readmore_text','readmore')); ?>
					</a>
				</div>
				<?php } ?>
			</div>
		</div>
		<?php
			$clear = 'clr1';
			if ($j % 2 == 0) $clear .= ' clr2';
			if ($j % 3 == 0) $clear .= ' clr3';
			if ($j % 4 == 0) $clear .= ' clr4';
			if ($j % 5 == 0) $clear .= ' clr5';
			if ($j % 6 == 0) $clear .= ' clr6';
		?>
		<div class="<?php echo $clear; ?>"></div>   
		<?php } ?>
	</div>
 </div>
 
<script type="text/javascript">
//<![CDATA[
jQuery(document).ready(function($) {
	;(function(element){
		var $element = $(element);
		//(function($){
		//	$(window).load(function(){
				$(".scrb-items",$element).mCustomScrollbar({
					scrollInertia:550,
					horizontalScroll:false,
					mouseWheelPixels:116,
					autoDraggerLength:true,
					scrollButtons:{
						enable:<?php echo $show_arrows ?>,
						scrollAmount:116
					},
					 advanced:{
						updateOnContentResize: true
					},theme:"dark"
				});
		//	});
		//})(jQuery);
	})('#<?php echo $tag_id; ?>');
});	
//]]>	
</script>