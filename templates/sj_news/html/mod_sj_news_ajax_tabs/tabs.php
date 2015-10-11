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

ob_start(); ?>

<div class="tabs-wrap">
<a href="#" class="tabs-previous" style="<?php if( $params->get('position') == 'bottom' ){echo 'top:2px';}?>">&#139;</a>
<div class="tabs-container" <?php //echo $tabs_container_style; ?>>
	<ul class="tabs">
	<?php foreach($list as $category) {
		$css_selected = $category->id == $category_preload ? ' class="selected"' : ''; ?>
		<li<?php echo $css_selected; ?>><div class="tab google-font"><?php echo $category->title; ?></div></li>
	<?php } ?>
	</ul>
</div>
<a href="#" class="tabs-next"  style="<?php if( $params->get('position') == 'bottom' ){echo 'top:2px';}?>">&#155;</a>
</div>
<?php $tabs_markup = ob_get_contents(); ?>
<?php ob_end_clean(); ?>
