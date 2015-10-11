<?php
/**
 * @package      ITPrism
 * @subpackage   Extensions
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2014 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

// no direct access
defined('JPATH_PLATFORM') or die;

/**
 * This class contains methods that are used for managing extensions.
 *
 * @package     ITPrism
 * @subpackage  Extensions
 */
class ITPrismExtensions
{
    protected $extensions = array();

    /**
     * Database driver.
     *
     * @var JDatabaseDriver
     */
    protected $db;

    /**
     * Initialize the object.
     *
     * <code>
     * $extensionsNames = array(
     *     "com_crowdfunding",
     *     "com_gamification"
     * );
     *
     * $extensions = new ITPrismExtensions(JFactory::getDbo(), $extensionsNames);
     * </code>
     *
     * @param JDatabaseDriver $db         Database driver.
     * @param array           $extensions A list with extensions name.
     */
    public function __construct(JDatabaseDriver $db, array $extensions)
    {
        $this->db         = $db;
        $this->extensions = $extensions;
    }

    /**
     * Return a list with names of enabled extensions.
     *
     * <code>
     * $extensionsNames = array(
     *     "com_crowdfunding",
     *     "com_gamification"
     * );
     *
     * $extensions = new ITPrismExtensions(JFactory::getDbo(), $extensionsNames);
     *
     * $enabled = $extensions->getEnabled();
     * </code>
     *
     * @return array
     */
    public function getEnabled()
    {
        $extensions = array();

        if (!$this->extensions) {
            return $extensions;
        }

        foreach ($this->extensions as $extension) {
            $extensions[] = $this->db->quote($extension);
        }

        $query = $this->db->getQuery(true);
        $query
            ->select("a.element")
            ->from($this->db->quoteName("#__extensions", "a"))
            ->where("a.element IN (" . implode(",", $extensions) . ")")
            ->where("a.enabled = 1");

        $this->db->setQuery($query);
        $extensions = $this->db->loadColumn();

        if (!$extensions) {
            $extensions = array();
        }

        return $extensions;
    }
}
