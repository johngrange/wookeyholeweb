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

	$show_introtext = $params->get('show_introtext', 0);
	$introtext_limit = $params->get('introtext_limit', 100);
	$title_limit = $params->get('item_title_max_characs', 20);
?>
<div class="item-wrap<?php //echo $item_last_css; ?> ajaxtabs-item">
	<div class="item-wrap-inner">

	<?php if( (int)$params->get('item_image_display', 1) && $item->image != null ):?>
		<div class="item-image" >
			<a href="<?php echo $item->link; ?>" target = "<?php echo $params->get('item_link_target');?>">
				<?php echo $item->image;?>
			</a>
		</div>
	<?php endif; // image display ?>
	
	<?php if( (int)$params->get('item_title_display', 1) ): ?>
		<div class="item-title">
			<a href="<?php echo $item->link; ?>" target = "<?php echo $params->get('item_link_target');?>">
				<?php echo str_replace('...','',SjAjaxtabsHelper::truncate($item->title, $title_limit,''));?>
			</a>
		</div>
	<?php endif; // title display ?>
	
	<?php if( (int)$params->get('item_description_display', 1) ): ?>
		<div class="item-description">
			<?php echo SjAjaxtabsHelper::truncate($item->displayIntrotext, $introtext_limit,'');?>
		</div>
	<?php endif; // description display ?>
	
	<?php if( (int)$params->get('item_readmore_display', 1) ): ?>
		<div class="item-readmore">
			<a href="<?php echo $item->link; ?>" target = "<?php echo $params->get('item_link_target');?>">
				<?php echo $params->get('item_readmore_text', 'Product details'); ?>
			</a>		
		</div>
	<?php endif; // readmore display ?>
	</div>
</div>

