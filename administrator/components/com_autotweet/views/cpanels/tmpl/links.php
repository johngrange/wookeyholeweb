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

$root = JUri::root();

?>
	<hr/>

	<div class="row-fluid">
		<div class="span6">
			<p class="text-center">Coming Soon<br/><img src="<?php

			echo $root;

			?>/media/com_autotweet/images/appstore.png"></p>
		</div>
		<div class="span6" style="margin-left: 0px;">
			<p class="text-center">Coming Soon<br/><img src="<?php

			echo $root;

			?>/media/com_autotweet/images/googleplay.png"></p>
		</div>
	</div>
	<p class="text-center"><a href="http://www.joomgap.com/" target="_blank">Powered by JoomGap</a></p>

	<hr/>

	<p><i class="xticon xticon-question-circle"></i>
		<?php echo JText::_('COM_AUTOTWEET_VIEW_ABOUT_SUPPORT_TITLE'); ?>

		<a href="http://support.extly.com" target="_blank">http://support.extly.com</a>
	</p>

	<p>
		<i class="xticon xticon-desktop"></i> Tutorials: <a
			href="http://www.extly.com/docs/autotweetng_joocial/index.html#tutorials"
			target="_blank"> How to AutoTweet from Joomla in 5 minutes</a>
	</p>

	<p>
		<i class="xticon xticon-info"></i> For more information: <a
			href="http://www.extly.com/docs/autotweetng_joocial/index.html"
			target="_blank"><?php echo VersionHelper::getFlavourName(); ?> Documentation</a>
	</p>

	<p>
		<i class="xticon xticon-group"></i> <?php

	echo JText::sprintf('COM_AUTOTWEET_VIEW_ABOUT_SUPPORT_JEDREVIEW', '<a href="' . JED_ID . '" target="_blank">Joomla! Extensions Directory: ');
							echo '</a>.';
							?>
	</p>

	<ul class="footer-links">
		<li><a href="http://www.extly.com/blog.html" target="_blank">Read
				the Extly.com blog</a></li>
		<li><a href="http://support.extly.com" target="_blank">Submit
				issues</a></li>
		<li><a
			href="http://www.extly.com/docs/autotweetng_joocial/changelog.html"
			target="_blank">Roadmap and changelog</a></li>
	</ul>

	<p class="customsocialicons">
		<?php echo JText::_('COM_AUTOTWEET_VIEW_ABOUT_SUPPORT_TWITTERFOLLOW'); ?> :
		<a target="_blank" href="http://twitter.com/extly"> <i
			class="xticon xticon-twitter"> </i>
		</a> <a target="_blank" href="http://www.facebook.com/extly"> <i
			class="xticon xticon-facebook-sign"></i>
		</a> <a target="_blank"
			href="http://www.linkedin.com/company/extly-com---joomla-extensions?trk=hb_tab_compy_id_2890809">
			<i class="xticon xticon-linkedin"></i>
		</a> <a target="_blank"
			href="https://plus.google.com/108048364795063174131"> <i
			class="xticon xticon-google-plus"> </i>
		</a> <a target="_blank" href="http://pinterest.com/extly/"> <i
			class="xticon xticon-pinterest"> </i>
		</a> <a target="_blank" href="https://github.com/anibalsanchez"> <i
			class="xticon xticon-github"> </i>
		</a>
	</p>
