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
 * integrate extensions with the notifications of Gamification Platform.
 *
 * @package      ITPrism
 * @subpackage   Integrations\Notifications
 */
class ITPrismIntegrateNotificationGamification implements ITPrismIntegrateInterfaceNotification
{
    protected $id;
    protected $note;
    protected $image;
    protected $url;
    protected $created;
    protected $status;

    protected $user_id;

    /**
     * Initialize the object.
     *
     * <code>
     * $userId = 1;
     * $note = "....";
     *
     * $notification = new ITPrismIntegrateNotificationGamification($userId, $note);
     * </code>
     * 
     * @param  integer $userId User ID
     * @param  string  $note   Notice to user.
     */
    public function __construct($userId = 0, $note = "")
    {
        $this->user_id = $userId;
        $this->note    = $note;
    }

    /**
     * Set values to object properties.
     * 
     * <code>
     * $data = array(
     *     "property1" => "...",
     *     "property2" => "...",
     * ....
     * );
     *
     * $notification = new ITPrismIntegrateNotificationGamification();
     * $notification->bind($data);
     * </code>
     * 
     * @param array $data
     */
    public function bind(array $data)
    {
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $this->$key = $value;
            }
        }
    }

    /**
     * Store a notification to database.
     *
     * <code>
     * $userId = 1;
     * $note = "....";
     *
     * $notification = new ITPrismIntegrateNotificationGamification($userId, $note);
     * $notification->send();
     * </code>
     *
     * @param string $note
     */
    public function send($note = "")
    {
        if (!empty($note)) {
            $this->note = $note;
        }

        jimport("gamification.notification");
        $notification = new GamificationNotification();

        $notification->note    = $this->getNote();
        $notification->user_id = $this->getUserId();

        if (!empty($this->image)) {
            $notification->image = $this->getImage();
        }

        if (!empty($this->url)) {
            $notification->url = $this->getUrl();
        }

        $notification->store();

    }

    /**
     * Return item ID.
     *
     * <code>
     * $userId = 1;
     * $content = "....";
     *
     * $notification = new ITPrismIntegrateNotificationGamification($userId, $content);
     * $notification->setDb(JFactory::getDbo());
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
     * $notification = new ITPrismIntegrateNotificationGamification();
     * $note = $notification->getNote();
     * </code>
     *
     * @return string $note
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Return an image that is part of the notification.
     *
     * <code>
     * $notification = new ITPrismIntegrateNotificationGamification();
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
     * $notification = new ITPrismIntegrateNotificationGamification();
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
     * Return a date where the notification has been created.
     *
     * <code>
     * $notification = new ITPrismIntegrateNotificationGamification();
     * $date = $notification->getCreated();
     * </code>
     *
     * @return string $created
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Return the status of the notification.
     *
     * <code>
     * $notification = new ITPrismIntegrateNotificationGamification();
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
     * Return the ID of the user receiver.
     *
     * <code>
     * $notification = new ITPrismIntegrateNotificationGamification();
     * $userId       = $notification->getUserId();
     * </code>
     *
     * @return int $actorId
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * This is the ID of the notification.
     *
     * <code>
     * $id = 1;
     *
     * $notification = new ITPrismIntegrateNotificationGamification();
     * $notification->setId($id);
     * </code>
     *
     * @param int $id
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;
        
        return $this;
    }

    /**
     * Set a content of the notification.
     * 
     * <code>
     * $note = "...";
     *
     * $notification = new ITPrismIntegrateNotificationGamification();
     * $notification->setNote($note);
     * </code>
     * 
     * @param string $note
     * 
     * @return self
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Set a link to an image, which is a part of the notification.
     *
     * <code>
     * $image = "...";
     *
     * $notification = new ITPrismIntegrateNotificationGamification();
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
     * Set a link to a page, which is a part of the notification.
     *
     * <code>
     * $url = "...";
     *
     * $notification = new ITPrismIntegrateNotificationGamification();
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
     * Set a date of the record when the notification has been sent.
     *
     * <code>
     * $created = "...";
     *
     * $notification = new ITPrismIntegrateNotificationGamification();
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
    }

    /**
     * Set a status of the notification.
     *
     * <code>
     * $status = 1;
     *
     * $notification = new ITPrismIntegrateNotificationGamification();
     * $notification->setStatus($status);
     * </code>
     * 
     * @param integer $status
     * 
     * @return self
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Set an ID of an user that is going to receive the notification.
     *
     * <code>
     * $userId = 1;
     *
     * $notification = new ITPrismIntegrateNotificationGamification();
     * $notification->setUserId($userId);
     * </code>
     * 
     * @param integer $userId
     * 
     * @return self
     */
    public function setUserId($userId)
    {
        $this->user_id = $userId;

        return $this;
    }
}
