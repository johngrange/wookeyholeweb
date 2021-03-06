<?php
/**
 * @package      ITPrism
 * @subpackage   Integrations\Notifications
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2014 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined('JPATH_PLATFORM') or die;

jimport("socialcommunity.init");
jimport("socialcommunity.notification");
JLoader::register("ITPrismIntegrateInterfaceNotification", JPATH_LIBRARIES . '/itprism/integrate/interface/notification.php');

/**
 * This class provides functionality to
 * integrate extensions with the notifications of social community.
 *
 * @package      ITPrism
 * @subpackage   Integrations\Notifications
 */
class ITPrismIntegrateNotificationSocialCommunity implements ITPrismIntegrateInterfaceNotification
{
    protected $id;
    protected $content;
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
     * $content = "....";
     *
     * $notification = new ITPrismIntegrateNotificationSocialCommunity($userId, $content);
     * </code>
     *
     * @param  integer $userId User ID
     * @param  string  $content Notice to user.
     */
    public function __construct($userId = 0, $content = "")
    {
        $this->user_id = $userId;
        $this->content = $content;
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
     * $notification = new ITPrismIntegrateNotificationSocialCommunity();
     * $notification->bind($data);
     * </code>
     *
     * @param array $data
     * @param array $excluded
     */
    public function bind($data, $excluded = array())
    {
        foreach ($data as $key => $value) {
            if (in_array($key, $excluded)) {
                continue;
            }

            $this->$key = $value;
        }
    }

    /**
     * Store a notification to database.
     *
     * <code>
     * $userId = 1;
     * $content = "....";
     *
     * $notification = new ITPrismIntegrateNotificationSocialCommunity($userId, $content);
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

        $notification = new SocialCommunityNotification(JFactory::getDbo());

        $notification->setContent($this->content);
        $notification->setUserId($this->user_id);

        if (!empty($this->image)) {
            $notification->setImage($this->image);
        }

        if (!empty($this->url)) {
            $notification->setUrl($this->url);
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
     * $notification = new ITPrismIntegrateNotificationSocialCommunity($userId, $content);
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
     * $notification = new ITPrismIntegrateNotificationSocialCommunity();
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
     * $notification = new ITPrismIntegrateNotificationSocialCommunity();
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
     * $notification = new ITPrismIntegrateNotificationSocialCommunity();
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
     * $notification = new ITPrismIntegrateNotificationSocialCommunity();
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
     * $notification = new ITPrismIntegrateNotificationSocialCommunity();
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
     * $notification = new ITPrismIntegrateNotificationSocialCommunity();
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
     * $notification = new ITPrismIntegrateNotificationSocialCommunity();
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
     * Set notification content.
     *
     * <code>
     * $content = "...";
     *
     * $notification = new ITPrismIntegrateNotificationSocialCommunity();
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
     * $notification = new ITPrismIntegrateNotificationSocialCommunity();
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
     * $notification = new ITPrismIntegrateNotificationSocialCommunity();
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
     * $notification = new ITPrismIntegrateNotificationSocialCommunity();
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
     * $status = 1;
     *
     * $notification = new ITPrismIntegrateNotificationSocialCommunity();
     * $notification->setStatus($status);
     * </code>
     *
     * @param int $status
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
     * $notification = new ITPrismIntegrateNotificationSocialCommunity();
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
