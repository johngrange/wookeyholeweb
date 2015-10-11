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

?>
<form id="adminForm" name="adminForm" action="index.php" method="post"
	class="form form-horizontal form-validate"
	ng-controller="EditorController as editorCtrl">
	<input type="hidden" name="option" value="com_autotweet" /> <input
		type="hidden" name="view" value="composer" /> <input type="hidden"
		name="task" value="" /> <input type="hidden" name="returnurl"
		value="<?php

		echo base64_encode(JRoute::_('index.php?option=com_autotweet&view=cpanels'));

		?>" />
<?php
echo EHtml::renderRoutingTags();

// Publish_up

echo '<input type="hidden" name="plugin"
 		ng-init="editorCtrl.plugin = \'autotweetpost\'"
 		ng-value="editorCtrl.plugin" />';

echo '<input type="hidden" name="ref_id"
 		ng-init="editorCtrl.ref_id = \'' . AutotweetBaseHelper::getHash() . '\'"
 		ng-value="editorCtrl.ref_id"/>';

echo '<input type="hidden" name="id"
 		ng-init="editorCtrl.request_id = 0"
 		ng-value="editorCtrl.request_id" />';

echo '<input type="hidden" name="published" value="0" />';

?>
<fieldset>
		<div class="row-fluid">
			<div class="span12">

				<p class="text-center" ng-if="editorCtrl.waiting"><span class="loaderspinner72 loading72">
					<?php echo JText::_('COM_AUTOTWEET_LOADING'); ?>
				</span></p>

				<div class="control-group" ng-if="editorCtrl.showDialog">
					<div class="alert alert-success" ng-if="editorCtrl.messageResult">
						<button type="button" class="close"
							ng-click="editorCtrl.showDialog = false">&times;</button>
						<div ng-bind-html="editorCtrl.messageText"></div>
					</div>
					<div class="alert alert-error" ng-if="!editorCtrl.messageResult">
						<button type="button" class="close"
							ng-click="editorCtrl.showDialog = false">&times;</button>
						<div ng-bind-html="editorCtrl.messageText"></div>
					</div>
				</div>

				<div class="control-group">
					<textarea id="description" rows="2" class="span12"
						placeholder="<?php echo JText::_('COM_AUTOTWEET_COMPOSER_TYPE_MESSAGE_LABEL'); ?>" ng-model="editorCtrl.description"
						ng-change="editorCtrl.countRemaining()"></textarea>
					<br /> <span class="xtd-counter pull-right">
					<sub class="{{editorCtrl.remainingCountClass}}">
					{{editorCtrl.remainingCount}}</sub></span>
				</div>

				<div class="control-group">

					<div class="pull-right post-attrs-group">
						<input type="hidden" value="2" id="xtformid5095" name="postAttrs" class="ng-pristine ng-untouched ng-valid">

						<div data-toggle="buttons-radio" class="xt-group">
							<a class="xt-button btn btn-small" data-value="l" data-ref="xtformid5095"
								data-original-title="<?php echo JText::_('COM_AUTOTWEET_VIEW_ITEMEDITOR_LINK_DESC'); ?>" rel="tooltip">
								<?php echo JText::_('COM_AUTOTWEET_VIEW_ITEMEDITOR_LINK_ICON'); ?></a>
							<a class="xt-button btn btn-small" data-value="i" data-ref="xtformid5095"
								data-original-title="<?php echo JText::_('COM_AUTOTWEET_VIEW_ITEMEDITOR_IMGCHOOSER_DESC'); ?>" rel="tooltip">
								<?php echo JText::_('COM_AUTOTWEET_VIEW_ITEMEDITOR_IMGCHOOSER_ICON'); ?></a>
<?php
		if (AUTOTWEETNG_JOOCIAL)
		{
?>
							<a class="xt-button btn btn-small" data-value="b" data-ref="xtformid5095"
								data-original-title="<?php echo JText::_('COM_AUTOTWEET_VIEW_ITEMEDITOR_BASIC_DESC'); ?>" rel="tooltip">
								<?php echo JText::_('COM_AUTOTWEET_VIEW_ITEMEDITOR_BASIC_ICON'); ?></a>
							<a class="xt-button btn btn-small" data-value="c" data-ref="xtformid5095"
								data-original-title="<?php echo JText::_('COM_AUTOTWEET_VIEW_ITEMEDITOR_CHANNELCHOOSER_DESC'); ?>" rel="tooltip">
								<?php echo JText::_('COM_AUTOTWEET_VIEW_ITEMEDITOR_CHANNELCHOOSER_ICON'); ?></a>
							<a class="xt-button btn btn-small" data-value="s" data-ref="xtformid5095"
								data-original-title="<?php echo JText::_('COM_AUTOTWEET_VIEW_ITEMEDITOR_SCHEDULER_DESC'); ?>" rel="tooltip">
								<?php echo JText::_('COM_AUTOTWEET_VIEW_ITEMEDITOR_SCHEDULER_ICON'); ?></a>
							<a class="xt-button btn btn-small" data-value="r" data-ref="xtformid5095"
								data-original-title="<?php echo JText::_('COM_AUTOTWEET_VIEW_ITEMEDITOR_REPEAT_DESC'); ?>" rel="tooltip">
								<?php echo JText::_('COM_AUTOTWEET_VIEW_ITEMEDITOR_REPEAT_ICON'); ?></a>
<?php
		}
		else
		{
?>
							<a class="xt-button btn btn-small disabled" data-value="b" data-ref="xtformid5095"
								data-original-title="<?php echo JText::_('COM_AUTOTWEET_VIEW_ITEMEDITOR_BASIC_DESC'); ?>" rel="tooltip">
								<?php echo JText::_('COM_AUTOTWEET_VIEW_ITEMEDITOR_BASIC_ICON'); ?></a>
							<a class="xt-button btn btn-small disabled" data-value="c" data-ref="xtformid5095"
								data-original-title="<?php echo JText::_('COM_AUTOTWEET_VIEW_ITEMEDITOR_CHANNELCHOOSER_DESC'); ?>" rel="tooltip">
								<?php echo JText::_('COM_AUTOTWEET_VIEW_ITEMEDITOR_CHANNELCHOOSER_ICON'); ?></a>
							<a class="xt-button btn btn-small disabled" data-value="s" data-ref="xtformid5095"
								data-original-title="<?php echo JText::_('COM_AUTOTWEET_VIEW_ITEMEDITOR_SCHEDULER_DESC'); ?>" rel="tooltip">
								<?php echo JText::_('COM_AUTOTWEET_VIEW_ITEMEDITOR_SCHEDULER_ICON'); ?></a>
							<a class="xt-button btn btn-small disabled" data-value="r" data-ref="xtformid5095"
								data-original-title="<?php echo JText::_('COM_AUTOTWEET_VIEW_ITEMEDITOR_REPEAT_DESC'); ?>" rel="tooltip">
								<?php echo JText::_('COM_AUTOTWEET_VIEW_ITEMEDITOR_REPEAT_ICON'); ?></a>
<?php
		}
?>
						</div>
<?php
		if (!AUTOTWEETNG_JOOCIAL)
		{
			echo '<p></p><p class="text-right">' . JText::_('COM_AUTOTWEET_UPDATE_TO_JOOCIAL_LABEL') . '</p>';
		}
?>

					</div>
				</div>
			</div>
		</div>

<?php
		// Link subform
		//
		echo '<div class="row-fluid xt-subform xt-subform-l alert alert-info span12" style="display: none;">';
		include_once '1-0-link.php';
		echo '</div>';

		// Image subform
		//
		echo '<div class="row-fluid xt-subform xt-subform-i alert alert-info span12" style="display: none;">';
		include_once '1-1-image.php';
		echo '</div>';

		if (AUTOTWEETNG_JOOCIAL)
		{
			// Basic subform
			//
			echo '<div class="row-fluid xt-subform xt-subform-b alert alert-info span12" style="display: none;">';
			include JPATH_ADMINISTRATOR . '/components/com_autotweet/views/itemeditor/tmpl/1-basic.php';

			// Hashtags
			$attrs = array('ng-model' => 'editorCtrl.hashtags', 'ng-change' => 'editorCtrl.countRemaining()');
			echo EHtml::textControl(null, 'hashtags', 'COM_AUTOTWEET_VIEW_ITEMEDITOR_HASHTAGS', 'COM_AUTOTWEET_VIEW_ITEMEDITOR_HASHTAGS_DESC', 'hashtags', 128, $attrs);

			// Fulltext
			echo '<div class="control-group"><textarea aria-invalid="false" class="span12" rows="2" id="fulltext" ng-model="editorCtrl.fulltext" name="fulltext" placeholder="' . JText::_('COM_AUTOTWEET_VIEW_ITEMEDITOR_FULLTEXT') . '"></textarea></div>';
			echo '</div>';

			// Channels subform
			//
			echo '<div class="row-fluid xt-subform xt-subform-c alert alert-info span12" style="display: none;">';
			include JPATH_ADMINISTRATOR . '/components/com_autotweet/views/itemeditor/tmpl/6-channels.php';
			echo '</div>';

			// Scheduler subform
			//
			echo '<div class="row-fluid xt-subform xt-subform-s alert alert-info span12" style="display: none;">';
			include '1-2-scheduler.php';
			echo '</div>';

			// Repeat subform
			//
			echo '<div class="row-fluid xt-subform xt-subform-r alert alert-info span12" style="display: none;">';
			include JPATH_ADMINISTRATOR . '/components/com_autotweet/views/itemeditor/tmpl/3-repeat.php';
			echo '</div>';
		}
?>

		<input type="hidden" name="author" value="<?php echo JFactory::getUser()->username; ?>"/>

	</fieldset>
</form>
