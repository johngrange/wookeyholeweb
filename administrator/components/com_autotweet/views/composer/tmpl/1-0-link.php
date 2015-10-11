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
	<div class="control-group">
		<label for="composer_link" class="control-label" rel="tooltip" data-original-title="<?php

		echo JText::_('COM_AUTOTWEET_VIEW_ITEMEDITOR_LINK_DESC');

		?>"><?php

		echo JText::_('COM_AUTOTWEET_VIEW_ITEMEDITOR_LINK');

		?></label>
		<div class="controls">
			<div class="input-prepend">
				<span class="add-on">
					<i class="xticon xticon-link"></i>
				</span>

				<input type="text" name="composer_link"
					placeholder="<?php echo JText::_('COM_AUTOTWEET_COMPOSER_TYPE_URL_LABEL'); ?>" ng-model="editorCtrl.url">
			<?php
				if (EXTLY_J3)
				{
			?>
				<span class="add-on">
					<a ng-click="editorCtrl.menuitemlistHide()">
						<i class="xticon xticon-caret-square-o-right "></i>
					</a>
				</span>
			<?php
				}
			?>
			</div>
		</div>
	</div>
<?php
if (EXTLY_J3)
{
?>
	<div id="menulist_group" class="control-group hide">
		<label></label>
		<div class="controls">
			<div class="input-prepend">
				<span class="add-on">
					<i class="xticon xticon-list"></i>
				</span>
		<?php
						echo EHtmlSelect::menuitemlist(
							null,
							'selectedMenuItem',
							array(
								'ng-model' => "editorCtrl.selectedMenuItem",
								'ng-change' => "editorCtrl.loadUrl(editorCtrl.selectedMenuItem)",
								'class' => 'span12',
								'size' => 1
							)
						);
		?>
			</div>
		</div>
	</div>
<?php
}
