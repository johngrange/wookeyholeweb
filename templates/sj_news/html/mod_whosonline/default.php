<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_whosonline
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
?>
<?php 
$class_mod = preg_match ('/online/',  $moduleclass_sfx) ? "online":"";
//print_r($class_mod);
if($class_mod =="online"){ ?>
	<div class="who-online">
	<div class="icon-group"></div>
	<div class="online_content">
		<h3 class="google-font"><?php echo JText::_('WHO_ONLINE');?></h3>
	<?php if ($showmode == 0 || $showmode == 2) : ?>
		<?php $guest = JText::plural('MOD_WHOSONLINE_GUESTS', $count['guest']); ?>
		<?php $member = JText::plural('MOD_WHOSONLINE_MEMBERS', $count['user']); ?>
		<p><?php echo JText::sprintf('MOD_WHOSONLINE_WE_HAVE', $guest, $member); ?></p>
	<?php endif; ?>
	
	<?php if (($showmode > 0) && count($names)) : ?>
		<ul  class="whosonline<?php echo $moduleclass_sfx ?>" >
		<?php if ($params->get('filter_groups')):?>
			<p><?php echo JText::_('MOD_WHOSONLINE_SAME_GROUP_MESSAGE'); ?></p>
		<?php endif;?>
		<?php foreach($names as $name) : ?>
			<li>
				<?php echo $name->username; ?>
			</li>
		<?php endforeach;  ?>
		</ul>
	<?php endif;?>
	</div>
	</div>

<?php } else { ?>
	<?php if ($showmode == 0 || $showmode == 2) : ?>
		<?php $guest = JText::plural('MOD_WHOSONLINE_GUESTS', $count['guest']); ?>
		<?php $member = JText::plural('MOD_WHOSONLINE_MEMBERS', $count['user']); ?>
		<p><?php echo JText::sprintf('MOD_WHOSONLINE_WE_HAVE', $guest, $member); ?></p>
	<?php endif; ?>
	
	<?php if (($showmode > 0) && count($names)) : ?>
		<ul  class="whosonline<?php echo $moduleclass_sfx ?>" >
		<?php if ($params->get('filter_groups')):?>
			<p><?php echo JText::_('MOD_WHOSONLINE_SAME_GROUP_MESSAGE'); ?></p>
		<?php endif;?>
		<?php foreach($names as $name) : ?>
			<li>
				<?php echo $name->username; ?>
			</li>
		<?php endforeach;  ?>
		</ul>
	<?php endif;?>
<?php }?>