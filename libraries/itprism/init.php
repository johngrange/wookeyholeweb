<?php
/**
 * @package      ITPrism
 * @subpackage   Initialization
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2014 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined('JPATH_PLATFORM') or die;

if (!defined("ITPRISM_PATH_LIBRARY")) {
    define("ITPRISM_PATH_LIBRARY", JPATH_LIBRARIES . DIRECTORY_SEPARATOR. "itprism");
}

// Register interfaces
JLoader::register("ITPrismValidatorInterface", JPATH_LIBRARIES . "validator" . DIRECTORY_SEPARATOR . "interface.php");

// Register logger
JLoader::register("ITPrismLog", ITPRISM_PATH_LIBRARY . DIRECTORY_SEPARATOR . "log.php");
JLoader::register("ITPrismLogWriterDatabase", ITPRISM_PATH_LIBRARY .DIRECTORY_SEPARATOR. "log" .DIRECTORY_SEPARATOR. "writer" .DIRECTORY_SEPARATOR. "database.php");
JLoader::register("ITPrismLogWriterFile", ITPRISM_PATH_LIBRARY .DIRECTORY_SEPARATOR. "log" .DIRECTORY_SEPARATOR. "writer" .DIRECTORY_SEPARATOR. "file.php");

// Register some helpers
JHtml::addIncludePath(ITPRISM_PATH_LIBRARY .'/ui/helpers');

// Register some classes
JLoader::register("ITPrismValidatorInterface", ITPRISM_PATH_LIBRARY . DIRECTORY_SEPARATOR . "validator" . DIRECTORY_SEPARATOR . "interface.php");
JLoader::register("ITPrismValidatorDate", ITPRISM_PATH_LIBRARY . DIRECTORY_SEPARATOR . "validator" . DIRECTORY_SEPARATOR . "date.php");
JLoader::register("ITPrismDate", ITPRISM_PATH_LIBRARY . DIRECTORY_SEPARATOR . "date.php");
JLoader::register("ITPrismMath", ITPRISM_PATH_LIBRARY . DIRECTORY_SEPARATOR . "math.php");

// Load library language
$lang = JFactory::getLanguage();
$lang->load('lib_itprism', ITPRISM_PATH_LIBRARY);
