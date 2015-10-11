<?php
/**
 * @package Sj Facebook
 * @version 2.5
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2012 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 *
 */
defined('_JEXEC') or die;
JHtml::stylesheet('templates/' . JFactory::getApplication()->getTemplate().'/html/mod_sj_meganews/style.css');

?>
<?php if($intro): ?>
<div class="pre-text-facebook">
	<?php  echo $intro;?>
</div>
<?php endif;?>

	<div class="sj_facebook-nav">
		<?php echo $sj_facebook_html; ?>
	</div>
	
<?php if($footer): ?>
<div class="post-text-facebook">
	<?php  echo $footer;?>
</div>
<?php endif;?>
