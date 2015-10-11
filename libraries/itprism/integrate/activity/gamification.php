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
 * integrate extensions with the activities of Gamification Platform.
 *
 * @package      ITPrism
 * @subpackage   Integrations\Activities
 */
class ITPrismIntegrateActivityGamification implements ITPrismIntegrateInterfaceActivity
{
    protected $id;
    protected $info;
    protected $image;
    protected $url;
    protected $created;
    protected $status;

    protected $user_id;

    /**
     * Initialize the object, setting a user id
     * and information about the activity.
     *
     * <code>
     * $userId = 1;
     * $content = "...";
     *
     * $activity = new ITPrismIntegrateActivityGamification($userId, $content);
     * </code>
     *
     * @param  integer $userId User ID
     * @param  string  $content   Information about the activity.
     */
    public function __construct($userId = 0, $content = "")
    {
        $this->user_id = $userId;
        $this->info    = $content;
    }

    /**
     * Set values to object properties.
     *
     * <code>
     * $data = array(
     *     "user_id" => 1,
     *     "content" => "...",
     * );
     *
     * $activity = new ITPrismIntegrateActivityGamification();
     * $activity->bind($data);
     * </code>
     *
     * @param array $data
     */
    public function bind($data)
    {
        if (!empty($data)) {

            foreach ($data as $key => $value) {
                $this->$key = $value;
            }

        }
    }

    /**
     * Store information about activity.
     *
     * <code>
     * $userId = 1;
     * $content = "...";
     *
     * $activity = new ITPrismIntegrateActivityGamification($userId, $content);
     * $activity->store();
     * </code>
     *
     * @param string $content
     */
    public function store($content = "")
    {
        if (!empty($content)) {
            $this->info = $content;
        }

        jimport("gamification.activity");
        $activity = new GamificationActivity();

        $activity->info    = $this->getInfo();
        $activity->user_id = $this->getUserId();

        if (!empty($this->image)) {
            $activity->image = $this->getImage();
        }

        if (!empty($this->url)) {
            $activity->url = $this->getUrl();
        }

        $activity->store();

    }

    /**
     * Return an item ID.
     *
     * <code>
     * $activity = new ITPrismIntegrateActivityGamification();
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
     * $activity = new ITPrismIntegrateActivityGamification();
     * $content = $activity->getInfo();
     * </code>
     *
     * @return string $note
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * Return an image.
     *
     * <code>
     * $activity = new ITPrismIntegrateActivityGamification();
     * $image = $activity->getImage();
     * </code>
     *
     * @return string $image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Return a URL that is part from activity.
     *
     * <code>
     * $activity = new ITPrismIntegrateActivityGamification();
     * $url = $activity->getUrl();
     * </code>
     *
     * @return string $url
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Return a date when the activity has been created.
     *
     * <code>
     * $activity = new ITPrismIntegrateActivityGamification();
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
     * $activity = new ITPrismIntegrateActivityGamification();
     * $status = $activity->getStatus();
     * </code>
     *
     * @return string $status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Return a user ID.
     *
     * <code>
     * $activity = new ITPrismIntegrateActivityGamification();
     * $userId = $activity->getUserId();
     * </code>
     *
     * @return int $user_id
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Set an item ID.
     *
     * <code>
     * $id = 1;
     *
     * $activity = new ITPrismIntegrateActivityGamification();
     * $activity->setId($id);
     * </code>
     *
     * @param integer $id
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set the content of the activity.
     *
     * <code>
     * $content = "...";
     *
     * $activity = new ITPrismIntegrateActivityGamification();
     * $activity->setInfo($id);
     * </code>
     *
     * @param string $content
     *
     * @return self
     */
    public function setInfo($content)
    {
        $this->info = $content;

        return $this;
    }

    /**
     * Set an image.
     *
     * <code>
     * $image = "...";
     *
     * $activity = new ITPrismIntegrateActivityGamification();
     * $activity->setImage($image);
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
     * Set a URL.
     *
     * <code>
     * $url = "...";
     *
     * $activity = new ITPrismIntegrateActivityGamification();
     * $activity->setUrl($url);
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
     * Set a date when the activity has been created.
     *
     * <code>
     * $created = "...";
     *
     * $activity = new ITPrismIntegrateActivityGamification();
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
     * $activity = new ITPrismIntegrateActivityGamification();
     * $activity->setStatus($status);
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
     * Set an ID of a user which has made the activity.
     *
     * <code>
     * $userId = 1;
     *
     * $activity = new ITPrismIntegrateActivityGamification();
     * $activity->setUserId($userId);
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
