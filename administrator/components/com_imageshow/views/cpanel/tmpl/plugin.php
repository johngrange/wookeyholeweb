<?php
/**
 * @version    $Id: plugin.php 16077 2012-09-17 02:30:25Z giangnd $
 * @package    JSN.ImageShow
 * @author     JoomlaShine Team <support@joomlashine.com>
 * @copyright  Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
$objUtils 	= JSNISFactory::getObj('classes.jsn_is_utils');
$device     = $objUtils->checkSupportedFlashPlayer();
$showlistID = JRequest::getInt('showlist_id');
$showcaseID = JRequest::getInt('showcase_id');
$url 		= JURI::root() . '/administrator/components/com_imageshow/assets/swf';
?>
<script type="text/javascript">
var clipboard = null;
(function($){
	$(document).ready(function () {
		var client = new ZeroClipboard(document.getElementById("jsn-clipboard-button"));
		client.on( "ready", function( readyEvent ) {
			client.on("copy", function(e) {
					client.setData("text/plain", $('#syntax-plugin').val());
				});
			  client.on( "aftercopy", function( event ) {
				  alert("Copied text to clipboard: " + event.data["text/plain"] );
				  var checkIcon = $('.jsn-clipboard-checkicon');
				  checkIcon.addClass('jsn-clipboard-coppied');
				  (function() { checkIcon.removeClass('jsn-clipboard-coppied');  } ).delay(3000);
				  
			  });
		});		
	});
})((typeof JoomlaShine != 'undefined' && typeof JoomlaShine.jQuery != 'undefined') ? JoomlaShine.jQuery : jQuery);
</script>
<div class="jsn-plugin-details">
	<div class="jsn-bootstrap">
		<div class="form-search">
		<?php
		echo JText::_('CPANEL_PLEASE_INSERT_FOLLOWING_TEXT_TO_YOUR_ARTICLE_AT_THE_POSITION_WHERE_YOU_WANT_TO_SHOW_GALLERY');
		?>
			<div id="jsn-clipboard">
				<span class="jsn-clipboard-input">
					<input type="text" id="syntax-plugin" name="plugin" value="{imageshow sl=<?php echo $showlistID; ?> sc=<?php echo $showcaseID; ?> /}" />
					<span class="jsn-clipboard-checkicon jsn-icon-ok"></span>
				</span>
				<?php if ($device == 'iphone' || $device == 'ipad' || $device == 'ipod' || $device == 'android' || $device == 'windows')
				{
					// do nothing
				}
				else 
				{
				?>
				<span id="jsn-clipboard-container">
					<button id="jsn-clipboard-button" class="btn"><?php echo JText::_('CPANEL_COPY_TO_CLIPBOARD')?></button>
				</span>				
				<?php 
				}
				?>
			</div>
		</div>
		<?php
		echo JText::_('CPANEL_MORE_DETAILS_ABOUT_PLUGIN_SYNTAX');
		?>
	</div>
</div>
