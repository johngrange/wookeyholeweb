<?php
/**
 * @package     Extly.Components
 * @subpackage  com_autotweet - A powerful social content platform to manage multiple social networks.
 *
 * @author      Prieco S.A. <support@extly.com>
 * @copyright   Copyright (C) 2007 - 2015 Prieco, S.A. All rights reserved.
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @link        http://www.extly.com http://support.extly.com
 */
// No direct access
defined('_JEXEC') or die('Restricted access');

$author = $this->item->xtform->get('author');
$title = $this->item->xtform->get('title');

if (($isManualMsg) && (empty($title)))
{
	$this->item->xtform->set('title', '');
}

$fulltext = $this->item->xtform->get('fulltext');

if (($isManualMsg) && (empty($fulltext)))
{
	$this->item->xtform->set('fulltext', '');
}

$allow_new_reqpost = EParameter::getComponentParam(CAUTOTWEETNG, 'allow_new_reqpost', 0);
$create_event = $this->item->xtform->get('create_event', 0);

?>

<div class="span6">
	<div class="row-fluid">
		<div class="span12">

			<ul class="nav nav-tabs" id="qTypeTabs">

				<li id="auditinfo-tab"><a data-toggle="tab" href="#auditinfo">
					<i class="xticon xticon-user"></i>
					 <?php echo JText::_('COM_AUTOTWEET_AUDIT_INFORMATION'); ?>
				</a></li>

				<li id="overrideconditions-tab"><a data-toggle="tab" href="#override-conditions">
					<i class="xticon xticon-file-text-o"></i>
					 <?php echo JText::_('COM_AUTOTWEET_VIEW_MANUALMSG_MESSAGE_OPTIONS'); ?>
				</a></li>

				<li id="filterconditions-tab"><a data-toggle="tab" href="#filterconditions">
					<i class="xticon xticon-filter"></i>
					 <?php echo JText::_('COM_AUTOTWEET_VIEW_MANUALMSG_FILTERS_OPTIONS'); ?>
				</a></li>
			</ul>

			<div class="tab-content" id="qContent">

				<div id="auditinfo" class="tab-pane fade">
					<dl class="dl-horizontal">
						<dt>
							<?php
							echo JText::_('COM_AUTOTWEET_CREATED_DATE');
							?>
						</dt>
						<dd>
							<?php
							echo $this->item->get('created');
							?>

							<?php
							$created = $this->item->get('created_by');

							if ($created)
							{
								echo JFactory::getUser($created)->get('username');
							}
							else
							{
								echo '-';
							}
							?>
						</dd>

						<dt>
							<?php
							echo JText::_('COM_AUTOTWEET_MODIFIED_DATE');
							?>
						</dt>
						<dd>
							<?php
							$modified = $this->item->get('modified');

							if ((int) $modified)
							{
								echo $modified;
							}
							?>

							<?php
							$modified_by = $this->item->get('modified_by');

							if ($modified_by)
							{
								echo JFactory::getUser($modified_by)->get('username');
							}
							else
							{
								echo '-';
							}
							?>
						</dd>

						<dt>
							<?php
							echo JText::_('COM_AUTOTWEET_RESULT_MESSAGE');
							?>
						</dt>
						<dd>
							<?php
							echo $alert_message ? TextUtil::autoLink($alert_message) : '-';
							?>
						</dd>
					</dl>
				</div>

				<div id="override-conditions" class="tab-pane fade">
					<div class="control-group">
						<label for="title" class="control-label <?php echo $labelDisabledNotManual; ?>" rel="tooltip" data-original-title="<?php
			echo JText::_('COM_AUTOTWEET_VIEW_MANUALMSG_MESSAGE_DESC');
			?>"><?php echo JText::_('COM_AUTOTWEET_VIEW_MANUALMSG_MESSAGE'); ?> </label>
						<div class="controls">
							<textarea name="xtform[title]" id="title" rows="5" cols="80" maxlength="512" <?php echo $readonlyNotManual; ?>><?php
								echo $this->item->xtform->get('title');
								?></textarea>
						</div>
					</div>

					<div class="control-group">
						<label for="fulltext" class="control-label <?php echo $labelDisabledNotManual; ?>" rel="tooltip" data-original-title="<?php
			echo JText::_('COM_AUTOTWEET_VIEW_MANUALMSG_FULL_TEXT_DESC');
			?>"><?php echo JText::_('COM_AUTOTWEET_VIEW_MANUALMSG_FULL_TEXT'); ?> </label>
						<div class="controls">
							<textarea name="xtform[fulltext]" id="fulltext" rows="5" cols="40" maxlength="512" <?php echo $readonlyNotManual; ?>><?php
								echo $this->item->xtform->get('fulltext');
								?></textarea>
						</div>
					</div>

					<div class="control-group">
						<label for="hashtags" class="control-label <?php echo $labelDisabledNotManual; ?>" rel="tooltip" data-original-title="<?php
			echo JText::_('COM_AUTOTWEET_VIEW_MANUALMSG_HASHTAGS_DESC');
			?>"><?php echo JText::_('COM_AUTOTWEET_VIEW_MANUALMSG_HASHTAGS'); ?> </label>
						<div class="controls">
							<input type="text" name="xtform[hashtags]" id="hashtags" value="<?php echo $this->item->xtform->get('hashtags'); ?>" maxlength="64" <?php
							echo $readonlyNotManual;
							?> />
						</div>
					</div>
				</div>

				<div id="filterconditions" class="tab-pane fade">
					<?php

						if (!$isRequest)
						{
							echo EHtmlSelect::yesNoControl(
								$this->item->xtform->get('featured', 0),
								'xtform[featured]',
								'COM_AUTOTWEET_VIEW_MANUALMSG_FEATURED',
								'COM_AUTOTWEET_VIEW_MANUALMSG_FEATURED_DESC');
						}

					?>
					<div class="control-group">
						<label for="catid" class="control-label" rel="tooltip" data-original-title="<?php
			echo JText::_('COM_AUTOTWEET_VIEW_MANUALMSG_CATEGORY_DESC');
			?>"><?php echo JText::_('COM_AUTOTWEET_VIEW_MANUALMSG_CATEGORY'); ?> </label>
						<div class="controls">
							<?php echo SelectControlHelper::category('xtform[catid]', 'com_content', $this->item->xtform->get('catid'), null, null, 1, 1, !$isManualMsg); ?>
						</div>
					</div>

					<div class="control-group">
						<label for="author" class="control-label" rel="tooltip" data-original-title="<?php
			echo JText::_('COM_AUTOTWEET_VIEW_MANUALMSG_AUTHOR_DESC');
			?>"><?php echo JText::_('COM_AUTOTWEET_VIEW_MANUALMSG_AUTHOR'); ?> <span class="star">&#160;*</span> </label>
						<div class="controls">

<?php
						echo EHtmlSelect::userSelect($author, 'xtform[author]', 'author');
?>

						</div>
					</div>

					<div class="control-group">
						<label for="language" class="control-label" rel="tooltip" data-original-title="<?php
			echo JText::_('COM_AUTOTWEET_VIEW_MANUALMSG_LANGUAGE_DESC');
			?>"><?php echo JText::_('COM_AUTOTWEET_VIEW_MANUALMSG_LANGUAGE'); ?> </label>
						<div class="controls">
							<?php echo SelectControlHelper::languages($this->item->xtform->get('language'), 'xtform[language]') ?>
						</div>
					</div>

					<?php
					if (EXTLY_J3)
					{
						?>
					<div class="control-group">
						<label for="language" class="control-label" rel="tooltip" data-original-title="<?php
			echo JText::_('COM_AUTOTWEET_VIEW_MANUALMSG_ACCESS_DESC');
			?>"><?php echo JText::_('COM_AUTOTWEET_VIEW_MANUALMSG_ACCESS'); ?> </label>
						<div class="controls">
							<?php
							echo JHTML::_('access.level', 'xtform[access]', $this->item->xtform->get('access', 1));
							?>
						</div>
					</div>

					<?php
					}
					?>

					<?php
					if ((AUTOTWEETNG_JOOCIAL) && (EParameter::getComponentParam(CAUTOTWEETNG, 'targeting', false)))
					{
					?>
		            <hr/>

					<div class="control-group">
	            		<label class="control-label" for="xtformtarget_id" id="target_id-lbl"><?php
						echo JText::_('COM_AUTOTWEET_VIEW_FBWACCOUNT_TARGETING_TITLE');
						?></label>
	            		<div class="controls">
	              			<?php echo SelectControlHelper::targets($this->item->xtform->get('target_id'), 'xtform[target_id]', null); ?>
	            		</div>
	          		</div>

					<?php
					}
					?>
				</div>
			</div>

		</div>
	</div>
</div>
