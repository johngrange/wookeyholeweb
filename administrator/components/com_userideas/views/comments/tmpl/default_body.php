<?php
/**
 * @package      UserIdeas
 * @subpackage   Component
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2014 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

// no direct access
defined('_JEXEC') or die;
?>
<?php foreach ($this->items as $i => $item) {?>
	<tr class="row<?php echo $i % 2;?>">
		<td class="center hidden-phone">
            <?php echo JHtml::_('grid.id', $i, $item->id); ?>
        </td>
        <td class="center">
            <?php echo JHtml::_('jgrid.published', $item->published, $i, "comments."); ?>
        </td>
        <td class="title">
			<a href="<?php echo JRoute::_("index.php?option=com_userideas&view=comment&layout=edit&id=".$item->id); ?>" >
		        <?php echo $this->escape($item->comment); ?>
	        </a>
	    </td>
		<td>
			<?php echo $item->item; ?>
		</td>
		<td class="center hidden-phone"><?php echo $item->record_date; ?></td>
		<td class="center hidden-phone"><?php echo $item->user; ?></td>
        <td class="center hidden-phone"><?php echo $item->id;?></td>
	</tr>
<?php } ?>
	  