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
<p id="authorizeLiOAuth2" class="text-center">
	<a id="authorizeButton" href="<?php

	echo $authUrl;
	?>" class="btn btn-info <?php

	echo $authUrlButtonStyle;

	?>"><?php

	echo JText::_('COM_AUTOTWEET_VIEW_CHANNEL_AUTHBUTTON_TITLE');

	?></a>
</p>
<p>
<?php

	echo $message;

?>
</p>
<p><?php echo JText::_('COM_AUTOTWEET_CHANNEL_LIOAUTH2_AFTER_CLICK'); ?></p>
