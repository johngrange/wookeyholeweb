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

$this->loadHelper('select');

$urlBase = JUri::root();
$isBackend = F0FPlatform::getInstance()->isBackend();

$postsLink = JRoute::_('index.php?option=com_autotweet&view=posts');
$requestsLink = JRoute::_('index.php?option=com_autotweet&view=requests');
$evergreensLink = JRoute::_('index.php?option=com_autotweet&view=evergreens');
$channelsLink = JRoute::_('index.php?option=com_autotweet&view=channels');
$rulesLink = JRoute::_('index.php?option=com_autotweet&view=rules');
$feedsLink = JRoute::_('index.php?option=com_autotweet&view=feeds');

$document = JFactory::getDocument();

$requestsData = array(
		(object) array('label' => JText::_('COM_AUTOTWEET_TITLE_REQUESTS'),
				'value' => (int) $this->requests),
		(object) array('label' => JText::_('COM_AUTOTWEET_TITLE_POSTS'),
				'value' => (int) $this->posts)
);
$document->addScriptDeclaration('requestsData = ' . json_encode($requestsData) . ';');

$postsData  = array(
		(object) array('label' => SelectControlHelper::getTextForEnum('success'),
				'value' => (int) $this->p_success),
		(object) array('label' => SelectControlHelper::getTextForEnum('cronjob'),
				'value' => (int) $this->cronjob),
		(object) array('label' => SelectControlHelper::getTextForEnum('approve'),
				'value' => (int) $this->p_approve),
		(object) array('label' => SelectControlHelper::getTextForEnum('cancelled'),
				'value' => (int) $this->p_cancelled),
		(object) array('label' => SelectControlHelper::getTextForEnum('error'),
				'value' => (int) $this->p_error)
);
$document->addScriptDeclaration('postsData = ' . json_encode($postsData) . ';');

$timelineData = $this->generateTimeline();
$document->addScriptDeclaration('timelineData = ' . json_encode($timelineData) . ';');

?>
<div class="extly dashboard">
	<div class="extly-body">

			<div class="row-fluid">
				<div class="span8">

<?php
				if ($this->get('version_check'))
				{
?>
					<form name="adminForm" id="adminForm" action="index.php" method="post">
						<input type="hidden" name="option" id="option" value="com_autotweet" />
						<input type="hidden" name="view" id="view" value="cpanels" />
						<input type="hidden" name="task" id="task" value="no-task" />
						<?php

							echo EHtml::renderRoutingTags();

						?>

						<span class="loaderspinner72">
							<?php echo JText::_('COM_AUTOTWEET_LOADING'); ?>
						</span>
						<div id="updateNotice">
						</div>

					</form>
<?php
				}
?>

					<h2>
						<?php echo JText::_('COM_AUTOTWEET_ICON_CPANELS')?>
						<?php echo JText::_('COM_AUTOTWEET_JOOCIAL_METER')?>
					</h2>

					<div class="row-fluid">
						<div class="span6">
							<h3>
								<?php echo JText::_('COM_AUTOTWEET_ICON_REQUESTS')?>
								<?php echo JText::_('COM_AUTOTWEET_TITLE_PROCESSED_REQUESTS')?>
							</h3>
							<div id="requests-chart">
								<svg style="width:100%; height:175px">
							</div>
						</div>
						<div class="span6" style="margin-left: 0px;">
							<h3>
								<?php echo JText::_('COM_AUTOTWEET_ICON_POSTS')?>
								<?php echo JText::_('COM_AUTOTWEET_TITLE_PROCESSED_POSTS')?>
							</h3>
							<div id="posts-chart">
								<svg style="width:100%; height:175px">
							</div>
						</div>
					</div>

					<div class="row-fluid">
						<div class="span12">
							<h3>
								<?php echo JText::_('COM_AUTOTWEET_ICON_POSTS')?>
								<?php echo JText::_('COM_AUTOTWEET_TITLE_POSTS_TIMELINE')?>
							</h3>
							<div id="posts-timeline">
								<svg style="width:100%; height:300px">
							</div>
						</div>
					</div>

					<hr/>

					<?php

					if ($isBackend)
					{
					?>

					<h2>
						<?php echo JText::_('COM_AUTOTWEET_VIEW_CHANNELS_TITLE')?>
					</h2>
					<p>
						<a href="<?php echo $channelsLink; ?>" class="btn btn-large">
							<i class="xticon xticon-facebook"></i>
						</a>

						<a href="<?php echo $channelsLink; ?>" class="btn btn-large">
							<i class="xticon xticon-twitter"></i>
						</a>

						<a href="<?php echo $channelsLink; ?>" class="btn btn-large">
							<i class="xticon xticon-linkedin"></i>
						</a>

						<a href="<?php echo $channelsLink; ?>" class="btn btn-large">
							<i class="xticon xticon-google-plus"></i>
						</a>

<?php
						if (AUTOTWEETNG_JOOCIAL)
						{
?>
						<a href="<?php echo $channelsLink; ?>" class="btn btn-large">
							<i class="xticon xticon-google"></i>
						</a>

						<a href="<?php echo $channelsLink; ?>" class="btn btn-large">
							<i class="xticon xticon-exclamation"></i>
						</a>

						<a href="<?php echo $channelsLink; ?>" class="btn btn-large">
							<i class="xticon xticon-tumblr"></i>
						</a>

						<a href="<?php echo $channelsLink; ?>" class="btn btn-large">
							<i class="xticon xticon-xing"></i>
						</a>

						<a href="<?php echo $channelsLink; ?>" class="btn btn-large">
							<i class="xticon xticon-group"></i>
						</a>
<?php
						}
						else
						{
?>
						<a href='http://www.extly.com/joocial.html' target='_blank' class="btn btn-large disabled">
							<i class="xticon xticon-google"></i>
						</a>

						<a href='http://www.extly.com/joocial.html' target='_blank' class="btn btn-large disabled">
							<i class="xticon xticon-exclamation"></i>
						</a>

						<a href='http://www.extly.com/joocial.html' target='_blank' class="btn btn-large disabled">
							<i class="xticon xticon-tumblr"></i>
						</a>

						<a href='http://www.extly.com/joocial.html' target='_blank' class="btn btn-large disabled">
							<i class="xticon xticon-xing"></i>
						</a>

						<a href='http://www.extly.com/joocial.html' target='_blank' class="btn btn-large disabled">
							<i class="xticon xticon-group"></i>
						</a>
<?php
						}
?>

						<a href="<?php echo $channelsLink; ?>" class="btn btn-large">
							<i class="xticon xticon-envelope-o"></i>
						</a>

						<a href="<?php echo $channelsLink; ?>" class="btn btn-large">
							<i class="xticon xticon-vk"></i>
						</a>
					</p>

					<h2>
						<?php echo JText::_('COM_AUTOTWEET_SHORTCUTS')?>
					</h2>
					<p>
						<a href="<?php echo $postsLink; ?>" class="btn btn-large">
							<?php echo JText::_('COM_AUTOTWEET_ICON_POSTS')?>
							<?php echo JText::_('COM_AUTOTWEET_TITLE_POSTS')?>
						</a>
						<a href="<?php echo $requestsLink; ?>" class="btn btn-large">
							<?php echo JText::_('COM_AUTOTWEET_ICON_REQUESTS')?>
							<?php echo JText::_('COM_AUTOTWEET_TITLE_REQUESTS')?>
						</a>
<?php
						if (AUTOTWEETNG_JOOCIAL)
						{
?>
						<a href="<?php echo $evergreensLink; ?>" class="btn btn-large">
							<?php echo JText::_('COM_AUTOTWEET_ICON_EVERGREENS')?>
							<?php echo JText::_('COM_AUTOTWEET_TITLE_EVERGREENS')?>
						</a>
<?php
						}
						else
						{
?>
						<a href='http://www.extly.com/joocial.html' target='_blank' class="btn btn-large disabled">
							<?php echo JText::_('COM_AUTOTWEET_ICON_EVERGREENS')?>
							<?php echo JText::_('COM_AUTOTWEET_TITLE_EVERGREENS')?>
						</a>
<?php
						}
?>
						<a href="<?php echo $channelsLink; ?>" class="btn btn-large">
							<?php echo JText::_('COM_AUTOTWEET_ICON_CHANNELS')?>
							<?php echo JText::_('COM_AUTOTWEET_TITLE_CHANNELS')?>
						</a>
						<a href="<?php echo $rulesLink; ?>" class="btn btn-large">
							<?php echo JText::_('COM_AUTOTWEET_ICON_RULES')?>
							<?php echo JText::_('COM_AUTOTWEET_TITLE_RULES')?>
						</a>
						<a href="<?php echo $feedsLink; ?>" class="btn btn-large">
							<?php echo JText::_('COM_AUTOTWEET_ICON_FEEDS')?>
							<?php echo JText::_('COM_AUTOTWEET_TITLE_FEEDS')?>
						</a>
					</p>

					<?php
					}

					?>
				</div>
				<div class="span4">

					<?php

					if ($isBackend)
					{
						if (AUTOTWEETNG_JOOCIAL)
						{
							$manager = EExtensionHelper::getExtensionId('system', 'autotweetautomator');

							$url = 'index.php?option=com_autotweet&view=managers&task=edit&id=' . $manager;
							$url = JRoute::_($url);

							echo '<p class="text-right lead"><i class="xticon xticon-user"></i> <a class="btn btn-primary span10" href="' . $url . '">' .
								JText::_('COM_AUTOTWEET_VIEW_ABOUT_VIRTUALMANAGER_TITLE')
								. '</a></p><p class="text-right">';

							if (VirtualManager::getInstance()->isWorking())
							{
								echo JText::_('COM_AUTOTWEET_VIEW_ABOUT_VIRTUALMANAGER_WORKING');
								echo ' <i class="xticon xticon-sun-o"></i>';
							}
							else
							{
								echo JText::_('COM_AUTOTWEET_VIEW_ABOUT_VIRTUALMANAGER_RESTING');
								echo ' <i class="xticon xticon-moon-o"></i>';
							}

							echo '</p>';
						}
						else
						{
							echo '<p class="text-right lead"><i class="xticon xticon-user muted"></i> <a class="btn span10 disabled" href="http://www.extly.com/joocial.html" target="_blank">' .
									JText::_('COM_AUTOTWEET_VIEW_ABOUT_VIRTUALMANAGER_TITLE')
									. '</a></p><p class="text-right muted">';
								echo JText::_('COM_AUTOTWEET_VIEW_ABOUT_VIRTUALMANAGER_RESTING');
								echo ' <i class="xticon xticon-moon-o"></i>';
							echo '</p>';

							echo '<p></p><p class="text-center">' . JText::_('COM_AUTOTWEET_UPDATE_TO_JOOCIAL_LABEL') . '</p>';
						}

						include 'links.php';
					}

					?>
				</div>
			</div>

	</div>
</div>
