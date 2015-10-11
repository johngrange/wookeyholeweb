<?php
/*
 * ------------------------------------------------------------------------
 * Yt FrameWork for Joomla 3.0
 * ------------------------------------------------------------------------
 * Copyright (C) 2009 - 2012 The YouTech JSC. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: The YouTech JSC
 * Websites: http://www.smartaddons.com - http://www.cmsportal.net
 * ------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

if(!defined('J_TEMPLATEDIR')){
    define('J_TEMPLATEDIR', JPATH_SITE.J_SEPARATOR.'templates'.J_SEPARATOR.$this->template);
}
// Include file: frame_inc.php
include_once (J_TEMPLATEDIR.'/includes/frame_inc.php');
// Check RTL or LTF direction
$dir = ($direction == 'rtl') ? ' dir="rtl"' : '';

$app   = JFactory::getApplication();
$doc   = JFactory::getDocument();
$this->language = $doc->language;
$this->direction = $doc->direction;
?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
    <jdoc:include type="head" />
    <meta name="HandheldFriendly" content="true"/>
    <meta name="viewport" content="width=device-width, target-densitydpi=160dpi, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <?php 
    include_once (J_TEMPLATEDIR.'/includes/head.php');
    ?>
</head>
<body class="contentpane<?php echo ($direction == 'rtl')?' rtl':''; ?>">
	<jdoc:include type="message" />
	<jdoc:include type="component" />
</body>
</html>




