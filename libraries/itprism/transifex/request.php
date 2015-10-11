<?php
/**
 * @package      ITPrism
 * @subpackage   Transifex
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2014 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

// no direct access
defined('JPATH_PLATFORM') or die;

JLoader::register("ITPrismTransifexResponse", JPATH_LIBRARIES . "/itprism/transifex/response.php");

/**
 * This class provides functionality for making requests to Transifex servers.
 *
 * @package      ITPrism
 * @subpackage   Transifex
 */
class ITPrismTransifexRequest
{
    protected $url;
    protected $timeout = 400;
    protected $connectionTimeout = 120;
    protected $username;
    protected $password;
    protected $auth = false;

    protected $info;
    protected $data = array();

    /**
     * Initialize the object.
     *
     * <code>
     * $url = "https://www.transifex.com/api/2/";
     *
     * $request = new ITPrismTransifexRequest($url);
     * </code>
     *
     * @param string $url Transifex API URL
     */
    public function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * Make a request to Transifex server.
     *
     * @param string  $path
     * @param array $options
     *
     * @throws RuntimeException
     *
     * @return NULL|ITPrismTransifexResponse
     */
    protected function request($path, $options = array())
    {
        if (!function_exists('curl_version')) {
            throw new RuntimeException("The cURL library is not loaded.");
        }

        $headers  = JArrayHelper::getValue($options, "headers", array(), "array");
        $method   = JArrayHelper::getValue($options, "method", "get");
        $postData = JArrayHelper::getValue($options, "data", array(), "array");

        $url = $this->url . $path;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->connectionTimeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // Prepare headers
        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_HEADER, 0);
        }

        if (strcmp($method, "post") == 0) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        }

        if ($this->auth) {
            curl_setopt($ch, CURLOPT_USERPWD, $this->username . ":" . $this->password);
        }

        // Get the data
        $response = curl_exec($ch);
        if (!empty($response)) {
            $this->data = json_decode($response, true);
            if (!is_array($this->data)) {
                $message = (string)$response . " (" . $url . ")";
                throw new RuntimeException($message);
            }
        }

        // Get info about the request
        $this->info = curl_getinfo($ch);

        // Check for error
        $errorNumber = curl_errno($ch);
        if (!empty($errorNumber)) {
            $message = curl_error($ch) . "(" . (int)$errorNumber . ")";
            throw new RuntimeException($message);
        }

        // Close the request
        curl_close($ch);

        // Create response object
        $response = new ITPrismTransifexResponse();
        $response->bind($this->data);

        return $response;
    }

    /**
     * <code>
     * $url = "https://www.transifex.com/api/2/";
     * $path = "projects";
     *
     * $options = array(
     *     "headers" => array(
     *          'Content-type: application/json',
     *          'X-HTTP-Method-Override: GET'
     *      ),
     *      "method" => "get", // GET or POST
     *      "data"   => array() // Data that should be sent to Transifex servers.
     * );
     *
     * $request = new ITPrismTransifexRequest($url);
     * $request->get($path, $options);
     * </code>
     *
     * @param string $path   A name of Transifex object.
     * @param array $options You can set three kind of options - headers, method and data.
     *
     * @return ITPrismTransifexResponse|NULL
     */
    public function get($path, $options = array())
    {
        return $this->request($path, $options);
    }

    /**
     * Return the username.
     *
     * <code>
     * $url = "https://www.transifex.com/api/2/";
     *
     * $request = new ITPrismTransifexRequest($url);
     * $request->getUsername();
     * </code>
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set a Transifex username.
     *
     * <code>
     * $url = "https://www.transifex.com/api/2/";
     * $username = "MyTransifexUsername";
     *
     * $request = new ITPrismTransifexRequest($url);
     * $request->setUsername($username);
     * </code>
     *
     * @param string $username
     *
     * @return self
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Return the password.
     *
     * <code>
     * $url = "https://www.transifex.com/api/2/";
     *
     * $request = new ITPrismTransifexRequest($url);
     * $request->getPassword();
     * </code>
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set a Transifex password.
     *
     * <code>
     * $url = "https://www.transifex.com/api/2/";
     * $password = "MyTransifexPassword";
     *
     * $request = new ITPrismTransifexRequest($url);
     * $request->setPassword($password);
     * </code>
     *
     * @param string $password
     *
     * @return self
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Enable Transifex authentication.
     *
     * <code>
     * $url = "https://www.transifex.com/api/2/";
     *
     * $request = new ITPrismTransifexRequest($url);
     * $request->enableAuthentication();
     * </code>
     *
     * @return self
     */
    public function enableAuthentication()
    {
        $this->auth = true;

        return $this;
    }

    /**
     * Enable Transifex authentication.
     *
     * <code>
     * $url = "https://www.transifex.com/api/2/";
     *
     * $request = new ITPrismTransifexRequest($url);
     * $request->disableAuthentication();
     * </code>
     *
     * @return self
     */
    public function disableAuthentication()
    {
        $this->auth = false;

        return $this;
    }
}
