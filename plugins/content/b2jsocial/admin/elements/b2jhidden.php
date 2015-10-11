<?php

/* ---------------------------------------------------------------------------------------------------------------------
 * Bang2Joom Social Plugin for Joomla! 2.5+
 * ---------------------------------------------------------------------------------------------------------------------
 * Copyright (C) 2011-2012 Bang2Joom. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: Bang2Joom
 * Website: http://www.bang2joom.com
  ----------------------------------------------------------------------------------------------------------------------
 */

defined('JPATH_BASE') or die;
jimport('joomla.form.formfield');
jimport('joomla.application.component.helper');
jimport('joomla.error.error');
jimport( 'joomla.version' );

class JFormFieldb2jhidden extends JFormField {

    protected $type = 'b2jhidden';

    function __construct()
    {
        parent::__construct();
        $this->check_dependencies();
    }

    protected function getLabel() {
        return null;
    }

    protected function getInput() {
        $doc = JFactory::getDocument();
        $doc->addScript(JURI::root() . 'plugins/content/b2jsocial/admin/js/b2jsocial.js');
				$doc->addScript(JURI::root() . 'plugins/content/b2jsocial/admin/js/jscolor.js');
        return null;
    }

    static function check_dependencies()
    {
      $db = JFactory::getDbo();
			//K2 Check
			$db->setQuery("SELECT enabled FROM #__extensions WHERE name = 'com_k2'");
			$is_enabled = $db->loadResult();
			if(!$is_enabled){
				$doc = JFactory::getDocument();
				$version = new JVersion;
				$joomla = $version->getShortVersion();
				if (substr($joomla, 0, 1) !=3){
					$doc->addScript('http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js');
				}
			}
    }

}

?>
