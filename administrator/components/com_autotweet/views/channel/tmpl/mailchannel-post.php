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

$input = $this->input;
$message = $input->getString('message');
$image_url = $input->getString('image_url');
$url = $input->getString('url');
$org_url = $input->getString('org_url');

$sitename = JFactory::getConfig()->get('sitename');
$url = RouteHelp::getInstance()->getRoot();

?>
<p>
	<?php echo TextUtil::autoLink($message); ?>
</p>
<?php

if (!empty($image_url))
{
	?>
<p>
	<a href="<?php echo $org_url; ?>">
		<img src="<?php echo $image_url; ?>">
	</a>
</p>
<?php
}

if (!empty($org_url))
{
	?>
<p>
	<a href="<?php echo $org_url; ?>">
		<?php echo $org_url; ?>
	</a>
</p>
<?php
}
?>
<hr />
<p>
	<a href="<?php echo $url; ?>">
		<?php echo $sitename . ' - ' . $url; ?>
	</a>
</p>
