<?php
/**
 * @package      ITPrism
 * @subpackage   XML
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2014 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined('JPATH_PLATFORM') or die;

/**
 * This class extends the native PHP class Simple XML
 *
 * @package      ITPrism
 * @subpackage   XML
 */
class ITPrismXmlSimple extends SimpleXMLElement
{
    /**
     * Include a CDATA element to an XML content.
     *
     * <code>
     * $sxml = new ITPrismXmlSimple();
     *
     * $sxml->addCData("<strong>This text contains HTML code.</strong>");
     * </code>
     *
     * @param string $cdataText
     */
    public function addCData($cdataText)
    {
        $node = dom_import_simplexml($this);
        $no   = $node->ownerDocument;
        $node->appendChild($no->createCDATASection($cdataText));
    }
}
