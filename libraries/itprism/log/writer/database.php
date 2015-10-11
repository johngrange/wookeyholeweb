<?php
/**
 * @package      ITPrism
 * @subpackage   Logs\Writers
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2014 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined('JPATH_PLATFORM') or die;

JLoader::register("ITPrismLogInterfaceWriter", JPATH_LIBRARIES . "/itprism/log/interface/writer.php");

/**
 * This is a class that provides functionality for storing log data in database.
 *
 * @package         ITPrism
 * @subpackage      Logs\Writers
 */
class ITPrismLogWriterDatabase implements ITPrismLogInterfaceWriter
{
    protected $title;
    protected $type;
    protected $data;
    protected $recordDate;

    protected $db;
    protected $tableName;

    /**
     * Initialize the object.
     *
     * <code>
     * $tableName = "#__crowdf_logs";
     *
     * $writer = new ITPrismLogWriterDatabase(JFactory::getDbo(), $tableName);
     * </code>
     *
     * @param JDatabaseDriver $db
     * @param string $tableName
     *
     * @throws UnexpectedValueException
     */
    public function __construct(JDatabaseDriver $db, $tableName)
    {
        $this->db        = $db;
        $this->tableName = $tableName;

        if (!$this->tableName) {
            throw new UnexpectedValueException(JText::_("LIB_ITPRISM_ERROR_INVALID_LOG_DATABASE_TABLE_NAME"));
        }
    }

    /**
     * Set a title of logged information.
     *
     * <code>
     * $tableName = "#__crowdf_logs";
     * $title = "Logged title...";
     *
     * $writer = new ITPrismLogWriterDatabase(JFactory::getDbo(), $tableName);
     * $writer->setTitle($title);
     * </code>
     *
     * @param string $title
     *
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Set a type of logged information.
     *
     * <code>
     * $tableName = "#__crowdf_logs";
     * $type = "PAYMENT_PROCESS";
     *
     * $writer = new ITPrismLogWriterDatabase(JFactory::getDbo(), $tableName);
     * $writer->setType($type);
     * </code>
     *
     * @param string $type
     *
     * @return self
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Set an additional information about logged information.
     *
     * <code>
     * $tableName = "#__crowdf_logs";
     * $data = array(
     *    "amount" => 100,
     *    "currency" => "USD"
     * );
     *
     * $writer = new ITPrismLogWriterDatabase(JFactory::getDbo(), $tableName);
     * $writer->setData($data);
     * </code>
     *
     * @param array $data
     *
     * @return self
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Set date of logged information.
     *
     * <code>
     * $tableName = "#__crowdf_logs";
     * $date = "01-01-2014";
     *
     * $writer = new ITPrismLogWriterDatabase(JFactory::getDbo(), $tableName);
     * $writer->setDate($date);
     * </code>
     *
     * @param string $date
     *
     * @return self
     */
    public function setDate($date)
    {
        $date = new JDate($date);

        $this->recordDate = $date->toSql();

        return $this;
    }

    /**
     * Save the information in a database.
     *
     * <code>
     * $tableName = "#__crowdf_logs";
     *
     * $writer = new ITPrismLogWriterDatabasef(JFactory::getDbo(), $tableName);
     * $writer->store();
     * </code>
     */
    public function store()
    {
        $query = $this->db->getQuery(true);

        $data = (!empty($this->data)) ? $this->data : "NULL";

        $query
            ->insert($this->db->quoteName($this->tableName))
            ->set($this->db->quoteName("id") . "=" . $this->db->quote("NULL"))
            ->set($this->db->quoteName("title") . "=" . $this->db->quote($this->title))
            ->set($this->db->quoteName("type") . "=" . $this->db->quote($this->type))
            ->set($this->db->quoteName("data") . "=" . $this->db->quote($data))
            ->set($this->db->quoteName("record_date") . "=" . $this->db->quote($this->recordDate));

        $this->db->setQuery($query);
        $this->db->execute();
    }
}
