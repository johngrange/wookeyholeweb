<?php
/**
 * @package      ITPrism
 * @subpackage   Integrations\Notifications
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2014 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined('JPATH_PLATFORM') or die;

JLoader::register("ITPrismIntegrateInterfaceNotification", JPATH_LIBRARIES . '/itprism/integrate/interface/notification.php');

/**
 * This class provides functionality to
 * integrate extensions with JomSocial notifications.
 *
 * @package      ITPrism
 * @subpackage   Integrations\Notifications
 */
class ITPrismIntegrateNotificationJomSocial implements ITPrismIntegrateInterfaceNotification
{
    protected $id;

    protected $actorId;
    protected $targetId;
    protected $content;

    protected $type = 0;
    protected $cmdType = "notif_system_messaging";
    protected $status = 0;
    protected $created;

    protected $image;
    protected $url;
    protected $cmd_type;

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
     * $userId = 1;
     * $content = "....";
     *
     * $notification = new ITPrismIntegrateNotificationJomSocial($userId, $content);
     * </code>
     *
     * @param  integer $userId A user ID of the target.
     * @param  string  $content Content of the notice to user.
     */
    public function __construct($userId = 0, $content = "")
    {
        $this->targetId = $userId;
        $this->content  = $content;
    }

    /**
     * Set database driver.
     *
     * <code>
     * $userId = 1;
     * $content = "....";
     *
     * $notification = new ITPrismIntegrateNotificationJomSocial($userId, $content);
     * $notification->setDb(JFactory::getDbo());
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
     * Store a notification to database.
     *
     * <code>
     * $userId = 1;
     * $content = "....";
     *
     * $notification = new ITPrismIntegrateNotificationJomSocial($userId, $content);
     * $notification->setDb(JFactory::getDbo());
     *
     * $notification->send();
     * </code>
     *
     * @param string $content
     */
    public function send($content = "")
    {
        if (!empty($content)) {
            $this->content = $content;
        }

        $query = $this->db->getQuery(true);

        if (!empty($this->image)) {
            $params["image"] = $this->image;
        }

        if (!empty($this->url)) {
            $params["url"] = $this->url;
        }

        $date = new JDate();

        $query
            ->insert($this->db->quoteName("#__community_notifications"))
            ->set($this->db->quoteName("actor") . "=" . (int)$this->actorId)
            ->set($this->db->quoteName("target") . "=" . (int)$this->targetId)
            ->set($this->db->quoteName("content") . "=" . $this->db->quote($this->content))
            ->set($this->db->quoteName("cmd_type") . "=" . $this->db->quote($this->cmdType))
            ->set($this->db->quoteName("type") . "=" . $this->db->quote($this->type))
            ->set($this->db->quoteName("status") . "=" . (int)$this->status)
            ->set($this->db->quoteName("created") . "=" . $this->db->quote($date->toSql()));

        if (!empty($params)) {
            $params = json_encode($params);
            $query->set($this->db->quoteName("params") . "=" . $this->db->quote($params));
        }

        $this->db->setQuery($query);
        $this->db->execute();

        $this->id = $this->db->insertid();
    }

    /**
     * Return item ID.
     *
     * <code>
     * $userId = 1;
     * $content = "....";
     *
     * $notification = new ITPrismIntegrateNotificationJomSocial($userId, $content);
     * $notification->send();
     *
     * if (!$notification->getId()) {
     * ...
     * }
     * </code>
     *
     * @return int $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Return the content of the notification.
     *
     * <code>
     * $notification = new ITPrismIntegrateNotificationJomSocial();
     * $content = $notification->getContent();
     * </code>
     *
     * @return string $content
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Return an image that is part of the notification.
     *
     * <code>
     * $notification = new ITPrismIntegrateNotificationJomSocial();
     * $image        = $notification->getImage();
     * </code>
     *
     * @return string $image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Return an URL which is part of the notification.
     *
     * <code>
     * $notification = new ITPrismIntegrateNotificationJomSocial();
     * $url          = $notification->getUrl();
     * </code>
     *
     * @return string $url
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Return an actor ID.
     *
     * <code>
     * $notification = new ITPrismIntegrateNotificationJomSocial();
     * $actorId      = $notification->getActorId();
     * </code>
     *
     * @return int $actorId
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Return the status of the notification.
     *
     * <code>
     * $notification = new ITPrismIntegrateNotificationJomSocial();
     * $status = $notification->getStatus();
     * </code>
     *
     * @return string $state
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Return a target user ID.
     *
     * <code>
     * $notification = new ITPrismIntegrateNotificationJomSocial();
     * $targetId    = $notification->getTargetId();
     * </code>
     *
     * @return int $targetId
     */
    public function getTargetId()
    {
        return $this->targetId;
    }

    /**
     * Set notification content.
     *
     * <code>
     * $content = "...";
     *
     * $notification = new ITPrismIntegrateNotificationJomSocial();
     * $notification->setContent($content);
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
     * Set a link to an image which will be part of the notification.
     *
     * <code>
     * $image = "...";
     *
     * $notification = new ITPrismIntegrateNotificationJomSocial();
     * $notification->setImage($image);
     * </code>
     *
     * @param string $image
     *
     * @return self
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Set a link to a page which will be part of the notification.
     *
     * <code>
     * $url = "...";
     *
     * $notification = new ITPrismIntegrateNotificationJomSocial();
     * $notification->setUrl($url);
     * </code>
     *
     * @param string $url
     *
     * @return self
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Set a date when the notification has been created.
     *
     * <code>
     * $created = "2014-01-01";
     *
     * $notification = new ITPrismIntegrateNotificationJomSocial();
     * $notification->setCreated($created);
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
     * Set notification status.
     *
     * <code>
     * $status = "...";
     *
     * $notification = new ITPrismIntegrateNotificationJomSocial();
     * $notification->setStatus($status);
     * </code>
     *
     * @param number $status
     *
     * @return self
     */
    public function setStatus($status)
    {
        $this->status = (int)$status;

        return $this;
    }

    /**
     * Set an ID of a target.
     *
     * <code>
     * $targetId = 1;
     *
     * $notification = new ITPrismIntegrateNotificationJomSocial();
     * $notification->setTargetId($targetId);
     * </code>
     *
     * @param number $targetId
     *
     * @return self
     */
    public function setTargetId($targetId)
    {
        $this->targetId = (int)$targetId;

        return $this;
    }

    /**
     * Set notification command type.
     *
     * <code>
     * $cmdType = "...";
     *
     * $notification = new ITPrismIntegrateNotificationJomSocial();
     * $notification->setCmdType($cmdType);
     * </code>
     *
     * @param string $cmd
     *
     * @return self
     */
    public function setCmdType($cmd)
    {
        $this->cmd_type = $cmd;

        return $this;
    }

    /**
     * Return the command type of a notification.
     *
     * <code>
     * $notification = new ITPrismIntegrateNotificationJomSocial();
     * $cmdType = $notification->getCmdType();
     * </code>
     *
     * @return string $cmd_type
     */
    public function getCmdType()
    {
        return $this->cmd_type;
    }

    /**
     * Set an ID of an actor.
     *
     * <code>
     * $actorId = 1;
     *
     * $notification = new ITPrismIntegrateNotificationJomSocial();
     * $notification->setActorId($actorId);
     * </code>
     *
     * @param int $actorId
     *
     * @return self
     */
    public function setActorId($actorId)
    {
        $this->actorId = (int)$actorId;

        return $this;
    }

    /**
     * Return an actor ID.
     *
     * <code>
     * $notification = new ITPrismIntegrateNotificationJomSocial();
     * $actorId      = $notification->getActorId();
     * </code>
     *
     * @return int $actorId
     */
    public function getActorId()
    {
        return $this->actorId;
    }

    /**
     * Return notification type.
     * 
     * <code>
     * $type = "...";
     * 
     * $notification = new ITPrismIntegrateNotificationJomSocial();
     * $notification->setType($type);
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
     * Return notification type.
     *
     * <code>
     * $notification = new ITPrismIntegrateNotificationJomSocial();
     * $type      = $notification->getType();
     * </code>
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
