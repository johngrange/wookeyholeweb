<?php
/**
 * @package      ITPrism
 * @subpackage   Integrations\Activities
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2014 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined('JPATH_PLATFORM') or die;

JLoader::register("ITPrismIntegrateInterfaceActivity", JPATH_LIBRARIES . '/itprism/integrate/interface/activity.php');

/**
 * This class provides functionality to
 * integrate extensions with the activities of JomSocial.
 *
 * @package      ITPrism
 * @subpackage   Integrations\Activities
 */
class ITPrismIntegrateActivityJomSocial implements ITPrismIntegrateInterfaceActivity
{
    protected $id;

    /**
     * Information about activity.
     * !!required
     *
     * @var string
     */
    protected $content;

    /**
     * Application name and task.
     * Example: com_vipquotes.post
     * !!required
     *
     * @var string
     */
    protected $app;

    /**
     * This is the status of the activity.
     * @var integer
     */
    protected $archived = 0;

    /**
     * This is the user that has done the activity.
     * !!required
     *
     * @var integer
     */
    protected $actorId;

    protected $created;

    /**
     * Database driver.
     *
     * @var JDatabaseDriver
     */
    protected $db;

    /**
     * Initialize the object, setting a user id
     * and information about the activity.
     *
     * <code>
     * $userId = 1;
     * $content = "...";
     *
     * $activity = new ITPrismIntegrateActivityJomSocial($userId, $content);
     * </code>
     * 
     * @param  integer $userId User ID
     * @param  string  $content Information about the activity.
     */
    public function __construct($userId, $content)
    {
        $this->actorId = $userId;
        $this->content = $content;
    }

    /**
     * Set a database driver.
     * 
     * <code>
     * $userId = 1;
     * $content = "...";
     *
     * $activity = new ITPrismIntegrateActivityJomSocial($userId, $content);
     * $activity->setDb(JFactory::getDbo());
     * </code>
     * 
     * @param JDatabaseDriver $db
     *
     * @return self
     */
    public function setDb(JDatabaseDriver $db)
    {
        $this->db = $db;

        return $this;
    }

    /**
     * Store information about activity.
     *
     * <code>
     * $userId = 1;
     * $content = "...";
     *
     * $activity = new ITPrismIntegrateActivityJomSocial($userId, $content);
     * $activity->setDb(JFactory::getDbo());
     * $activity->store();
     * </code>
     *
     * @param string $content
     *
     * @throws Exception
     */
    public function store($content = "")
    {
        if (!empty($content)) {
            $this->content = $content;
        }

        if (!$this->app) {
            throw new Exception(JText::_("LIB_ITPRISM_ERROR_INVALID_JOMSOCIAL_APP"));
        }

        $query = $this->db->getQuery(true);

        $date = new JDate();

        $query
            ->insert("#__community_activities")
            ->set($this->db->quoteName("actor") . "=" . (int)$this->actorId)
            ->set($this->db->quoteName("content") . "=" . $this->db->quote($this->content))
            ->set($this->db->quoteName("archived") . "=" . $this->db->quote($this->archived))
            ->set($this->db->quoteName("app") . "=" . $this->db->quote($this->app))
            ->set($this->db->quoteName("created") . "=" . $this->db->quote($date->toSql()));

        $this->db->setQuery($query);
        $this->db->execute();

        // Get the ID of the record.
        $this->id = $this->db->insertid();
    }

    /**
     * Return an item ID.
     *
     * <code>
     * $activity = new ITPrismIntegrateActivityJomSocial();
     * $id = $activity->getId();
     * </code>
     *
     * @return int $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Return the content of the activity.
     *
     * <code>
     * $activity = new ITPrismIntegrateActivityJomSocial();
     * $content = $activity->getContent();
     * </code>
     *
     * @return string $content
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Return a date when the activity has been created.
     *
     * <code>
     * $activity = new ITPrismIntegrateActivityJomSocial();
     * $created = $activity->getCreated();
     * </code>
     *
     * @return string $created
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Return a status of the activity.
     *
     * <code>
     * $activity = new ITPrismIntegrateActivityJomSocial();
     * $status = $activity->getStatus();
     * </code>
     *
     * @return string $status
     */
    public function getStatus()
    {
        return $this->archived;
    }

    /**
     * Return an actor ID.
     *
     * <code>
     * $activity = new ITPrismIntegrateActivityJomSocial();
     * $actorId = $activity->getActorId();
     * </code>
     *
     * @return int $actorId
     */
    public function getActorId()
    {
        return $this->actorId;
    }

    /**
     * Set the content of the activity.
     *
     * <code>
     * $content = "...";
     *
     * $activity = new ITPrismIntegrateActivityJomSocial();
     * $activity->setContent($id);
     * </code>
     *
     * @param string $content
     * 
     * @return self
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Set a date when the activity has been created.
     *
     * <code>
     * $created = "...";
     *
     * $activity = new ITPrismIntegrateActivityJomSocial();
     * $activity->setCreated($created);
     * </code>
     *
     * @param string $created
     * 
     * @return self
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Set a status of the activity.
     *
     * <code>
     * $status = "...";
     *
     * $activity = new ITPrismIntegrateActivityJomSocial();
     * $activity->setStatus($status);
     * </code>
     *
     * @param integer $status
     *
     * @return self
     */
    public function setStatus($status)
    {
        $this->archived = $status;

        return $this;
    }

    /**
     * Set an ID of a user which has made the activity.
     *
     * <code>
     * $actorId = 1;
     *
     * $activity = new ITPrismIntegrateActivityJomSocial();
     * $activity->setActorId($actorId);
     * </code>
     *
     * @param integer $actorId
     *
     * @return self
     */
    public function setActorId($actorId)
    {
        $this->actorId = $actorId;

        return $this;
    }

    /**
     * Set the name of the application that has made the activity.
     *
     * <code>
     * $app = "...";
     *
     * $activity = new ITPrismIntegrateActivityJomSocial();
     * $activity->setApp($app);
     * </code>
     *
     * @param string $app
     *
     * @return self
     */
    public function setApp($app)
    {
        $this->app = $app;

        return $this;
    }
}
