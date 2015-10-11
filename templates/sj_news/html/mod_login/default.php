<?php
// no direct access
defined('_JEXEC') or die;
?>
<?php
JHtml::_('behavior.keepalive');
$app = JFactory::getApplication();
//include_once(dirname(__FILE__).DS.'login.js.php');
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
	
<?php if ($type == 'logout') : ?>
    <form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" id="sj_login_form">
    <ul class="sj-login-regis">
    <?php if ($params->get('greeting')) : ?>
        <li class="sj-logout">
			<div class="logout-button">
				<span>
					<span>
						<?php if($params->get('name') == 0) : {
							echo JText::sprintf('MOD_LOGIN_HINAME', $user->get('name'));
						} else : {
							echo JText::sprintf('MOD_LOGIN_HINAME', $user->get('username'));
						} endif; ?>
					</span>
				</span>
				 <?php endif; ?>
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
    </form>
<?php else : ?>
<ul class="mobi-sj-login-regis" style="display: none">
	<li class="sj-login">
		<a 
                class="login-switch text-font"
                href="<?php echo JRoute::_('index.php?option=com_users&view=login');?>" 
                title="<?php JText::_('Login');?>">
                <?php echo JText::_('JLOGIN'); ?>
        </a>
    </li>
	<li class="sj-register">
        <a 
                class="register-switch text-font" 
                href="<?php echo JRoute::_("index.php?option=com_users&view=registration");?>">
            	<?php echo JText::_('JREGISTER');?>
        </a>
	</li>
</ul>
<ul class="sj-login-regis">
	
<?php
	$config     = &JFactory::getConfig();
	
	$option = JRequest::getCmd('option');
	$task = JRequest::getCmd('task');
	if($option!='com_user' && $task != 'register') { ?>
		<li class="sj-register">
			<a
				data-toggle="modal"
				class="register-switch text-font" 
				onclick="return false;"
				href="#register-modal">
				<span class="title-link"><?php echo JText::_('JREGISTER');?></span>
			</a>
			<script type="text/javascript" src="<?php echo JURI::base();?>media/system/js/validate.js"></script>
			<div id="register-modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
				</div>
				
				<div id="sj_register_box" class="show-box" >
					<div class="inner">
						<div class="sj_box_inner">
							<div class="sj_box_title google-font">
								<h3><?php echo JText::_('JREGISTER');?></h3>
							</div>
							<div class="sj_box_content">
								<script type="text/javascript">
								<!--
									window.addEvent('domready', function(){
										document.formvalidator.setHandler('passverify', function (value) { return ($('password').value == value); }	);
									});
								// -->
								</script>         
								<form id="member_registration" class="form-validate" action="<?php echo JRoute::_('index.php?option=com_users&task=registration.register'); ?>" method="post">
									<div class="col-right">
										<label class="required sj-field-regis" for="jform_name" id="namemsg" title="Name&lt;br&gt;Enter your full name">
											<span><?php echo JText::_( 'COM_USERS_PROFILE_NAME_LABEL' ); ?></span><span class="required">(*)</span><input type="text" size="25" class="inputbox required" value="" id="jform_name" name="jform[name]" placeholder="" />
										</label>
										<label title="" class="required sj-field-regis" for="jform_username" id="usernamemsg">
											<span><?php echo JText::_( 'COM_USERS_REGISTER_USERNAME_LABEL' ); ?></span><span class="required">(*)</span><input type="text" size="25" class="inputbox validate-username required" value="" id="jform_username" name="jform[username]" placeholder="" />
										</label>
										<label title="" class="required sj-field-regis" for="jform_password1" id="pwmsg">
											<span><?php echo JText::_( 'COM_USERS_PROFILE_PASSWORD1_LABEL' ); ?></span><span class="required">(*)</span><input type="password" size="25" class="inputbox validate-password required" value="" id="jform_password1" name="jform[password1]" placeholder="" />
										</label>
										<label title="" class="required sj-field-regis" for="jform_password2" id="pw2msg">
											<span><?php echo JText::_( 'COM_USERS_PROFILE_PASSWORD2_LABEL' ); ?></span><span class="required">(*)</span><input type="password" size="25" class="inputbox validate-password required" value="" id="jform_password2" name="jform[password2]" placeholder="" />
										</label>    
										<label title="" class="required sj-field-regis" for="jform_email1" id="emailmsg">
											<span><?php echo JText::_( 'COM_USERS_REGISTER_EMAIL1_LABEL' ); ?></span><span class="required">(*)</span><input type="text" size="25" class="inputbox validate-email required" value="" id="jform_email1" name="jform[email1]" placeholder="" />
										</label>
										<label title="" class="required sj-field-regis" for="jform_email2" id="email2msg">
											<span><?php echo JText::_( 'COM_USERS_REGISTER_EMAIL2_LABEL'); ?></span><span class="required">(*)</span><input type="text" size="25" class="inputbox validate-email required" value="" id="jform_email2" name="jform[email2]" placeholder="" />
										</label>
									</div>
									<div class="col-left">
										<div class="required-all"><?php echo JText::_( 'ALL_FIELDS_ARE_REQUIRED'); ?></div>
										<div class="button2"><input type="submit" class="validate button font-special" value="<?php echo JText::_( 'JREGISTER'); ?>"  />
										
										</div>
										<!--<a  class="btn" href="<?php //echo JRoute::_('');?>" title="<?php //echo JText::_('JCANCEL');?>"><?php //echo JText::_('JCANCEL');?></a>-->
									</div>
		
									<input type="hidden" name="option" value="com_users" />
									<input type="hidden" name="task" value="registration.register" />
									<?php echo JHtml::_('form.token');?>
								</form>
							</div>
						 </div>
					</div>
				</div>
			</div>
		</li>
	<?php 
	} // End $option!='com_user' && $task != 'register'
	?>
	<li class="line">|</li>
	<li class="sj-login">
		<a href="#mod-login" role="button" class="login-switch text-font" title="<?php JText::_('Login');?>" data-toggle="modal">
        	<span class="title-link"><?php echo JText::_('JLOGIN'); ?></span>
        </a>
		<div id="mod-login" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
			</div>
			<div id="sj_login_box" class="show-box">
				<div class="sj_box_inner">
					<div class="sj_box_title google-font">
						<h3><?php echo JText::_('SINGIN'); ?></h3>
					</div>
					<div class="sj_box_content">
						<form id="login_form" action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post">
							<?php if ($params->get('pretext')): ?>
							<div class="pretext">
								<p><?php echo $params->get('pretext'); ?></p>
							</div>
							<?php endif; ?>
							<fieldset class="userdata">
								<div class="login_input">
									<p>
										<label for="modlgn-username" class="sj-login-user">
											<span><?php echo JText::_('MOD_LOGIN_VALUE_USERNAME') ?></span><input id="modlgn-username" type="text" name="username" class="inputbox"  size="25" />
											
										</label>
									</p>
									<p>
										<label for="modlgn-passwd" class="sj-login-password">
											<span><?php echo JText::_('JGLOBAL_PASSWORD') ?></span><input id="modlgn-passwd" type="password" name="password" class="inputbox" size="25" />
											
										</label>
									</p>
									
								</div>
								<div class="link-forgot">
									<ul>
									<li>
										<a href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>" title="<?php echo JText::_('MOD_LOGIN_FORGOT_YOUR_USERNAME'); ?>"><i class="icon-circle"></i><span><?php echo JText::_('MOD_LOGIN_FORGOT_YOUR_USERNAME'); ?></span></a>
									</li>
									<li>
										<a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>" title="<?php echo JText::_('MOD_LOGIN_FORGOT_YOUR_PASSWORD'); ?>"><i class="icon-circle"></i><span><?php echo JText::_('MOD_LOGIN_FORGOT_YOUR_PASSWORD'); ?></span></a>
									</li>
									<li>									
										<a href="<?php echo JRoute::_("index.php?option=com_users&view=registration");?>"><i class="icon-circle"></i><span><?php echo JText::_('MOD_LOGIN_REGISTER');?>
								</span></a>
								</li>
								</ul>
										
								</div>
								<div class="login_button">
									<?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
										<p id="form_login_remember">
											<input id="modlgn-remember" type="checkbox" name="remember" class="checkbox" value="yes"/>
											<label for="modlgn-remember"><?php echo JText::_('MOD_LOGIN_REMEMBER_ME') ?></label>
										</p>
									 <?php endif; ?>
									<div class="button2">
										<input type="submit" name="Submit" class="font-special button" value="<?php echo JText::_('JLOGIN') ?>" />
									</div>
								</div>
								<input type="hidden" name="option" value="com_users" />
								<input type="hidden" name="task" value="user.login" />
								<input type="hidden" name="return" value="<?php echo $return; ?>" />
								<?php echo JHtml::_('form.token'); ?>
							</fieldset>
							<div class="more_login sj-login-links clearfix">
								
								
							</div>
							<?php if ($params->get('posttext')): ?>
							<div class="posttext">
								<p><?php echo $params->get('posttext'); ?></p>
							</div>
							<?php endif; ?>
						</form>
					</div>
				</div>
			</div>
		</div>
	</li>
	
</ul>
<?php
endif; // End type=login(not logout)
?>
</div>