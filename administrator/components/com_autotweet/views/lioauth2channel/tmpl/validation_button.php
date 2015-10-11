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

$channeltypeId = $this->input->get('channeltype_id', AutotweetModelChanneltypes::TYPE_LIOAUTH2_CHANNEL, 'cmd');

$accessTokenEncoded = htmlentities($accessToken);
$expires_date = $this->item->xtform->get('expires_date');
$expires_date = EParameter::convertUTCLocal($expires_date);

$lioauth2ChannelHelper = new LiOAuth2ChannelHelper($this->item);
$authUrl = $lioauth2ChannelHelper->getAuthorizationUrl();

if ((!empty($accessTokenEncoded)) && ($channeltypeId == AutotweetModelChanneltypes::TYPE_LIOAUTH2COMPANY_CHANNEL))
{
?>

<div class="control-group">
	<label class="required control-label" for="xtformlioauth2company_id" id="lioauth2company_id-lbl"><?php echo JText::_('COM_AUTOTWEET_CHANNEL_LINKEDIN_COMPANY_ID'); ?> <span class="star">&nbsp;*</span></label>
	<div class="controls">
		<a class="btn btn-info" id="lioauth2companyloadbutton"><?php echo JText::_('COM_AUTOTWEET_VIEW_CHANNEL_LOADBUTTON_TITLE'); ?></a>
		<?php echo SelectControlHelper::lioauth2companies(
				$this->item->xtform->get('company_id'),
				'xtform[company_id]',
				array(),
				$this->item->id
			); ?>
	</div>
</div>
<?php
}
?>

<div id="validationGroup" class=" <?php echo $validationGroupStyle; ?>">

	<div class="control-group">

		<label class="control-label">
			<a class="btn btn-info" id="lioauth2validationbutton"><?php echo JText::_('COM_AUTOTWEET_VIEW_CHANNEL_VALIDATEBUTTON'); ?></a>&nbsp;
		</label>

		<div id="validation-notchecked" class="controls">
			<span class="lead"><i class="xticon xticon-question-circle"></i> </span><span class="loaderspinner">&nbsp;</span>
		</div>

		<div id="validation-success" class="controls" style="display: none">
			<span class="lead"><i class="xticon xticon-check"></i> <?php echo JText::_('COM_AUTOTWEET_STATE_PUBSTATE_SUCCESS'); ?></span><span class="loaderspinner">&nbsp;</span>
		</div>

		<div id="validation-error" class="controls" style="display: none">
			<span class="lead"><i class="xticon xticon-exclamation"></i> <?php echo JText::_('COM_AUTOTWEET_STATE_PUBSTATE_ERROR'); ?></span><span class="loaderspinner">&nbsp;</span>
		</div>

	</div>

	<div id="validation-errormsg" class="alert alert-block alert-error" style="display: none">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		<div id="validation-theerrormsg">
			<?php echo JText::_('COM_AUTOTWEET_VIEW_CHANNEL_AUTH_MSG'); ?>
		</div>
	</div>

	<div class="control-group">
		<label class="required control-label" for="raw_user_id" id="user_id-lbl"><?php echo JText::_('COM_AUTOTWEET_CHANNEL_LIOAUTH2_USERID_TITLE'); ?> <span class="star">&nbsp;*</span>
		</label>
		<div class="controls">
			<input type="text" maxlength="255" size="64" value="<?php echo $userId; ?>" id="raw_user_id" name="xtform[user_id]" readonly="readonly" class="required" required="required">
<?php

		require dirname(__FILE__) . '/../../channel/tmpl/social_url.php';

		if ((!empty($accessTokenEncoded)) && ($channeltypeId == AutotweetModelChanneltypes::TYPE_LIOAUTH2COMPANY_CHANNEL))
		{
			$social_target = 'social_url_lioauth2company';
			require dirname(__FILE__) . '/../../channel/tmpl/social_url.php';
		}

?>

		</div>
	</div>

	<div class="control-group">
		<label class="required control-label" for="access_token" id="access_token-lbl"><?php echo JText::_('COM_AUTOTWEET_CHANNEL_LIOAUTH2_ACCESS_TOKEN'); ?> <span class="star">&nbsp;*</span>
		</label>
		<div class="controls">
			<input type="text" maxlength="255" size="64" value="<?php echo $accessTokenEncoded; ?>" id="access_token" name="xtform[access_token]" readonly="readonly" class="required" required="required">
		</div>
	</div>

	<div class="control-group">
		<label class="required control-label" for="expires_date" id="expires_date-lbl"><?php echo JText::_('COM_AUTOTWEET_CHANNEL_LIOAUTH2_EXPIRES_DATE'); ?> <span class="star">&nbsp;*</span>
		</label>
		<div class="controls">
			<input type="text" maxlength="255" size="64" value="<?php echo $expires_date; ?>" id="expires_date" readonly="readonly" class="required" required="required">

			<a id="authorizeButton" href="<?php	echo $authUrl;
			?>" class="btn btn-info"><?php echo JText::_('COM_AUTOTWEET_CHANNEL_LIOAUTH2_REFRESH'); ?></a>
		</div>
	</div>

</div>
