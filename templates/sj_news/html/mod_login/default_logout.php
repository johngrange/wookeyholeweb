<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_login
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
?>
<div class="day-login">
	<p class="sj-day">
      <?php 
        $db	 = &JFactory::getDBO();
        $query = 'SELECT created FROM #__content a ORDER BY created DESC LIMIT 1';
        $db->setQuery($query);
        $data = $db->loadObject();
        if( $data->created ){  //return gmdate( 'h:i:s A', strtotime($data->created) ) .' GMT ';
             $date =& JFactory::getDate(strtotime($data->created));
             $user =& JFactory::getUser();
             $tz = $user->getParam('timezone');
             $sec =$date->toUNIX();   //set the date time to second
             echo "<span class=\"date\">".date('d F Y')."</span> Last <span class=\"gmdate\">updated at ".gmdate("h:i ", $sec+$tz)." GMT</span>";
        }
        
      ?>
    </p>

<form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" id="sj_login_form" class="form-vertical">
<?php if ($params->get('greeting')) : ?>
	<ul class="sj-login-regis">
		<li class="sj-logout">
			<div class="login-greeting logout-button">
				<span>
					<span>
						<?php if ($params->get('name') == 0) : {
							echo JText::sprintf('MOD_LOGIN_HINAME', htmlspecialchars($user->get('name')));
						} else : {
							echo JText::sprintf('MOD_LOGIN_HINAME', htmlspecialchars($user->get('username')));
						} endif; ?>
					</span>
				</span>
				<a href="javascript:;" name="Submit" class="logout-switch" onclick="$('sj_login_form').submit();">
					<span>
						<?php echo JText::_('JLOGOUT'); ?>
					</span>
				</a>
				<input type="hidden" name="option" value="com_users" />
				<input type="hidden" name="task" value="user.logout" />
				<input type="hidden" name="return" value="<?php echo $return; ?>" />
				<?php echo JHtml::_('form.token'); ?>
			</div>
		</li>
	</ul>
<?php endif; ?>
	<?php /*?><div class="logout-button">
		<input type="submit" name="Submit" class="btn btn-primary" value="<?php echo JText::_('JLOGOUT'); ?>" />
		<input type="hidden" name="option" value="com_users" />
		<input type="hidden" name="task" value="user.logout" />
		<input type="hidden" name="return" value="<?php echo $return; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div><?php */?>
</form>
</div>