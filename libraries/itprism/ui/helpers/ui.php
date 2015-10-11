<?php
/**
 * @package      ITPrism
 * @subpackage   UI
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2014 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

// no direct access
defined('JPATH_PLATFORM') or die;

/**
 * ITPrism UI Html Helper
 *
 * @package       ITPrism
 * @subpackage    UI
 */
abstract class ITPrismUI
{
    /**
     * This parameter contains an information for loaded files.
     *
     * @var   array
     */
    protected static $loaded = array();

    /**
     * Include jQuery PNotify library.
     *
     * <code>
     * JHtml::addIncludePath(ITPRISM_PATH_LIBRARY .'/ui/helpers');
     *
     * JHtml::_('itprism.ui.pnotify');
     * </code>
     *
     * @link http://sciactive.github.io/pnotify/ Documentation of PNotify
     */
    public static function pnotify()
    {
        // Only load once
        if (!empty(self::$loaded[__METHOD__])) {
            return;
        }

        $document = JFactory::getDocument();

        $document->addStylesheet(JUri::root() . 'libraries/itprism/ui/css/jquery.pnotify.css');
        $document->addScript(JUri::root() . 'libraries/itprism/ui/js/jquery.pnotify.min.js');

        self::$loaded[__METHOD__] = true;

    }

    /**
     * Include Twitter Bootstrap library.
     *
     * <code>
     * JHtml::addIncludePath(ITPRISM_PATH_LIBRARY .'/ui/helpers');
     *
     * JHtml::_('itprism.ui.bootstrap');
     * </code>
     *
     * @link http://getbootstrap.com/2.3.2/index.html Documentation of Bootstrap v2.3.2
     */
    public static function bootstrap()
    {
        // Only load once
        if (!empty(self::$loaded[__METHOD__])) {
            return;
        }

        $document = JFactory::getDocument();

        $document->addStylesheet(JUri::root() . 'libraries/itprism/ui/css/bootstrap.min.css');
        $document->addScript(JUri::root() . 'libraries/itprism/ui/js/bootstrap.min.js');

        self::$loaded[__METHOD__] = true;

    }

    /**
     * Include Bootstrap rowlink library.
     *
     * <code>
     * JHtml::addIncludePath(ITPRISM_PATH_LIBRARY .'/ui/helpers');
     *
     * JHtml::_('itprism.ui.bootstrap_rowlink');
     * </code>
     *
     * @link http://jasny.github.io/bootstrap/javascript/#rowlink Documentation of Bootstrap rowlink
     */
    public static function bootstrap_rowlink()
    {
        // Only load once
        if (!empty(self::$loaded[__METHOD__])) {
            return;
        }

        $document = JFactory::getDocument();

        $document->addStylesheet(JUri::root() . 'libraries/itprism/ui/css/bootstrap-rowlink.min.css');
        $document->addScript(JUri::root() . 'libraries/itprism/ui/js/bootstrap-rowlink.min.js');

        self::$loaded[__METHOD__] = true;

    }

    /**
     * Include Bootstrap Editable library.
     *
     * <code>
     * JHtml::addIncludePath(ITPRISM_PATH_LIBRARY .'/ui/helpers');
     *
     * JHtml::_('itprism.ui.bootstrap_editable');
     * </code>
     *
     * @link https://github.com/vitalets/x-editable Documentation of Bootstrap Editable
     */
    public static function bootstrap_editable()
    {
        // Only load once
        if (!empty(self::$loaded[__METHOD__])) {
            return;
        }

        $document = JFactory::getDocument();

        $document->addStylesheet(JUri::root() . 'libraries/itprism/ui/css/bootstrap-editable.css');
        $document->addScript(JUri::root() . 'libraries/itprism/ui/js/bootstrap-editable.js');

        self::$loaded[__METHOD__] = true;

    }

    /**
     * Include Bootstrap Maxlength library.
     *
     * <code>
     * JHtml::addIncludePath(ITPRISM_PATH_LIBRARY .'/ui/helpers');
     *
     * JHtml::_('itprism.ui.bootstrap_maxlength');
     * </code>
     *
     * @link https://github.com/mimo84/bootstrap-maxlength Documentation of Bootstrap Maxlength
     */
    public static function bootstrap_maxlength()
    {
        // Only load once
        if (!empty(self::$loaded[__METHOD__])) {
            return;
        }

        $document = JFactory::getDocument();

        $document->addScript(JUri::root() . 'libraries/itprism/ui/js/bootstrap-maxlength.min.js');

        self::$loaded[__METHOD__] = true;
    }

    /**
     * Include Bootstrap File Upload Style library.
     *
     * <code>
     * JHtml::addIncludePath(ITPRISM_PATH_LIBRARY .'/ui/helpers');
     *
     * JHtml::_('itprism.ui.bootstrap_fileuploadstyle');
     * </code>
     */
    public static function bootstrap_fileuploadstyle()
    {
        // Only load once
        if (!empty(self::$loaded[__METHOD__])) {
            return;
        }

        $document = JFactory::getDocument();

        $document->addStylesheet(JUri::root() . 'libraries/itprism/ui/css/bootstrap-fileuploadstyle.min.css');
        $document->addScript(JUri::root() . 'libraries/itprism/ui/js/bootstrap-fileuploadstyle.min.js');

        self::$loaded[__METHOD__] = true;
    }

    /**
     * Include Bootstrap File Style library.
     *
     * <code>
     * JHtml::addIncludePath(ITPRISM_PATH_LIBRARY .'/ui/helpers');
     *
     * JHtml::_('itprism.ui.bootstrap_filestyle');
     * </code>
     *
     * @link http://markusslima.github.io/bootstrap-filestyle/ Documentation of Bootstrap File Style
     */
    public static function bootstrap_filestyle()
    {
        // Only load once
        if (!empty(self::$loaded[__METHOD__])) {
            return;
        }

        $document = JFactory::getDocument();

        $document->addScript(JUri::root() . 'libraries/itprism/ui/js/bootstrap-filestyle.js');

        self::$loaded[__METHOD__] = true;
    }

    /**
     * Include Bootstrap Typehead library.
     *
     * <code>
     * JHtml::addIncludePath(ITPRISM_PATH_LIBRARY .'/ui/helpers');
     *
     * JHtml::_('itprism.ui.bootstrap_typeahead');
     * </code>
     *
     * @link http://twitter.github.com/bootstrap/javascript.html#typeahead Documentation of Bootstrap Typehead
     */
    public static function bootstrap_typeahead()
    {
        // Only load once
        if (!empty(self::$loaded[__METHOD__])) {
            return;
        }

        $document = JFactory::getDocument();
        $document->addScript(JUri::root() . 'libraries/itprism/ui/js/bootstrap-typeahead.min.js');

        self::$loaded[__METHOD__] = true;
    }

    /**
     * Include Bootstrap Modal library.
     *
     * <code>
     * JHtml::addIncludePath(ITPRISM_PATH_LIBRARY .'/ui/helpers');
     *
     * JHtml::_('itprism.ui.bootstrap_modal');
     * </code>
     *
     * @link http://getbootstrap.com/2.3.2/javascript.html#modals Documentation of Bootstrap Modal
     */
    public static function bootstrap_modal()
    {
        // Only load once
        if (!empty(self::$loaded[__METHOD__])) {
            return;
        }

        $document = JFactory::getDocument();

        $document->addStylesheet(JUri::root() . 'libraries/itprism/ui/css/bootstrap-modal.min.css');
        $document->addScript(JUri::root() . 'libraries/itprism/ui/js/bootstrap-modal.min.js');

        self::$loaded[__METHOD__] = true;
    }

    /**
     * Include Bootstrap Navbar library.
     *
     * <code>
     * JHtml::addIncludePath(ITPRISM_PATH_LIBRARY .'/ui/helpers');
     *
     * JHtml::_('itprism.ui.bootstrap_navbar');
     * </code>
     *
     * @link http://getbootstrap.com/2.3.2/components.html#navbar Documentation of Bootstrap Navbar
     */
    public static function bootstrap_navbar()
    {
        // Only load once
        if (!empty(self::$loaded[__METHOD__])) {
            return;
        }

        $document = JFactory::getDocument();

        $document->addStylesheet(JUri::root() . 'libraries/itprism/ui/css/bootstrap-navbar.min.css');

        self::$loaded[__METHOD__] = true;
    }

    /**
     * Include Bootstrap Thumbnails library.
     *
     * <code>
     * JHtml::addIncludePath(ITPRISM_PATH_LIBRARY .'/ui/helpers');
     *
     * JHtml::_('itprism.ui.bootstrap_thumbnails');
     * </code>
     *
     * @link http://getbootstrap.com/2.3.2/components.html#thumbnails Documentation of Bootstrap Thumbnails
     */
    public static function bootstrap_thumbnails()
    {
        // Only load once
        if (!empty(self::$loaded[__METHOD__])) {
            return;
        }

        $document = JFactory::getDocument();

        $document->addStylesheet(JUri::root() . 'libraries/itprism/ui/css/bootstrap-thumbnails.min.css');

        self::$loaded[__METHOD__] = true;
    }

    /**
     * Include Bootstrap Media Component library.
     *
     * <code>
     * JHtml::addIncludePath(ITPRISM_PATH_LIBRARY .'/ui/helpers');
     *
     * JHtml::_('itprism.ui.bootstrap_mediacomponent');
     * </code>
     *
     * @link http://getbootstrap.com/2.3.2/components.html#media Documentation of Bootstrap Media Object
     */
    public static function bootstrap_mediacomponent()
    {
        // Only load once
        if (!empty(self::$loaded[__METHOD__])) {
            return;
        }

        $document = JFactory::getDocument();

        $document->addStylesheet(JUri::root() . 'libraries/itprism/ui/css/bootstrap-mediacomponent.min.css');

        self::$loaded[__METHOD__] = true;
    }

    /**
     * Include Parsley library.
     *
     * <code>
     * JHtml::addIncludePath(ITPRISM_PATH_LIBRARY .'/ui/helpers');
     *
     * JHtml::_('itprism.ui.parsley');
     * </code>
     *
     * @link http://parsleyjs.org/ Documentation of Parsley
     */
    public static function parsley()
    {
        // Only load once
        if (!empty(self::$loaded[__METHOD__])) {
            return;
        }

        $document = JFactory::getDocument();
        $document->addScript(JUri::root() . 'libraries/itprism/ui/js/parsley.min.js');

        self::$loaded[__METHOD__] = true;
    }

    /**
     * This method loads a script that initializes helper methods,
     * which are used in many ITPrism extensions.
     *
     * <code>
     * JHtml::addIncludePath(ITPRISM_PATH_LIBRARY .'/ui/helpers');
     *
     * JHtml::_('itprism.ui.joomla_helper');
     * </code>
     */
    public static function joomla_helper()
    {
        // Only load once
        if (!empty(self::$loaded[__METHOD__])) {
            return;
        }

        $document = JFactory::getDocument();
        $document->addScript(JUri::root() . 'libraries/itprism/ui/js/joomla/helper.js');

        self::$loaded[__METHOD__] = true;
    }

    /**
     * This method loads a script that initializes Joomla! list functionality.
     *
     * <code>
     * JHtml::addIncludePath(ITPRISM_PATH_LIBRARY .'/ui/helpers');
     *
     * JHtml::_('itprism.ui.joomla_list');
     * </code>
     */
    public static function joomla_list()
    {
        // Only load once
        if (!empty(self::$loaded[__METHOD__])) {
            return;
        }

        $document = JFactory::getDocument();
        $document->addScript(JUri::root() . 'libraries/itprism/ui/js/joomla/list.js');

        self::$loaded[__METHOD__] = true;
    }

    /**
     * Include jQuery File Upload library.
     *
     * <code>
     * JHtml::addIncludePath(ITPRISM_PATH_LIBRARY .'/ui/helpers');
     *
     * JHtml::_('itprism.ui.fileupload');
     * </code>
     *
     * @link http://blueimp.github.io/jQuery-File-Upload/ Documentation of jQuery File Upload
     */
    public static function fileupload()
    {
        // Only load once
        if (!empty(self::$loaded[__METHOD__])) {
            return;
        }

        $document = JFactory::getDocument();
        $document->addStylesheet(JUri::root() . 'libraries/itprism/ui/css/jquery.fileupload-ui.css');

        $document->addScript(JUri::root() . 'libraries/itprism/ui/js/fileupload/jquery.ui.widget.js');
        $document->addScript(JUri::root() . 'libraries/itprism/ui/js/fileupload/jquery.iframe-transport.js');
        $document->addScript(JUri::root() . 'libraries/itprism/ui/js/fileupload/jquery.fileupload.js');

        self::$loaded[__METHOD__] = true;
    }

    /**
     * Include D3 library.
     *
     * <code>
     * JHtml::addIncludePath(ITPRISM_PATH_LIBRARY .'/ui/helpers');
     *
     * JHtml::_('itprism.ui.d3');
     * </code>
     *
     * @param bool $cdn Include the library from content delivery network.
     *
     * @link http://d3js.org/ Documentation of D3
     */
    public static function d3($cdn = false)
    {
        // Only load once
        if (!empty(self::$loaded[__METHOD__])) {
            return;
        }

        $document = JFactory::getDocument();

        if (!$cdn) {
            $document->addScript(JUri::root() . 'libraries/itprism/ui/js/d3/d3.min.js');
        } else {
            $document->addScript("//cdnjs.cloudflare.com/ajax/libs/d3/3.4.8/d3.min.js");
        }

        self::$loaded[__METHOD__] = true;
    }
}
