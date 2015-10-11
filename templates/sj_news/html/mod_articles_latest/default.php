<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_articles_latest
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
?>

<?php
 $trending =  $moduleclass_sfx;
 ?>
<ul class="latestnews<?php echo $moduleclass_sfx; ?>">
<?php $i=1;
	foreach ($list as $item) :  ?>	
	<li <?php if($i==1){ echo "class='first'";}else if($i==count($list)){echo "class='last'";} ?>>	
		<?php if($moduleclass_sfx == $trending && $moduleclass_sfx !=''){?><span class="google-font"><?php echo $i;?></span><?php } ?>
		<?php if($moduleclass_sfx == $trending && $moduleclass_sfx !=''){?>
		
			<a class="font_size<?php echo $i;?>" href="<?php echo $item->link; ?>"><?php echo $item->title; ?></a>
		
		<?php } else{ ?>
			<a href="<?php echo $item->link; ?>"><?php echo $item->title; ?></a>
		<?php } ?>
	</li>
<?php $i ++;
	endforeach; ?>
</ul>

<?php if($moduleclass_sfx==''){?>
	<a href="#" class="more-news"><?php echo JText::_('MORE_NEWS');?><i class="icon-caret-right"></i></a>
<?php } ?>

<?php if($moduleclass_sfx == $trending && $moduleclass_sfx !=''){?>
	 <a href="#" class="see-all"><?php echo JText::_('SEE_ALL_TRENDS');?><i class="icon-caret-right"></i></a>
<?php } ?>
