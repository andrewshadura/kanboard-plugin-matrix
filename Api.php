<?php

namespace Kanboard\Plugin\Matrix;

use Exception;
use LogicException;
use Pimple\Container;
use Kanboard\Core\Base;
use Kanboard\Job\HttpAsyncJob;

const MATRIX_V2_API_PATH = '/_matrix/client/r0';

/**
 * Matrix API
 */

class Api extends Base
{
    private $homeserver_url;
    private $user_id = null;
    private $token = null;
    private $refresh_token = null;
    private $txn_id = 0;

    /**
     * Constructor
     *
     * @access public
     * @param  Container  $container
     * @param  string     $homeserver_url
     * @param  string     $token
     */
    public function __construct(Container $container, $homeserver_url, $token = NULL)
    {
        $this->container = $container;
        $this->homeserver_url = $homeserver_url;
        $this->token = $token;
    }

    /**
     * Matrix API call
     *
     * @access private
     * @throws LogicException
     * @param  string    $method
     * @param  string    $path
     * @param  string    $content
     * @param  array     $query_params
     * @param  array     $headers
     * @param  string    $api_path
     * @return string
     */
    private function api($method, $path, $content = NULL, array $query_params = array(), array $headers = array(), $async = false, $api_path = MATRIX_V2_API_PATH)
    {
        $valid_methods = array('GET', 'PUT', 'DELETE', 'POST');
        $method = strtoupper($method);
        if (!in_array($method, $valid_methods)) {
            throw new LogicException('Unsupported HTTP method: '.$method);
        }

        if (!in_array('Content-Type', $headers)) {
            $headers['Content-Type'] = 'application/json';
        }

        if (isset($this->token)) {
            $query_params['access_token'] = $this->token;
        }

        $endpoint = $this->homeserver_url.$api_path.$path;

        if (($headers['Content-Type'] == 'application/json') && (!empty($content))) {
            $content = json_encode($content);
        }

        $http_headers = array();
        foreach ($headers as $key => $value) {
            $http_headers[] = $key.': '.$value;
        }

        $url = $endpoint.'?'.http_build_query($query_params);
        if ($async) {
            $this->queueManager->push(HttpAsyncJob::getInstance($this->container)->withParams(
                $method,
                $url,
                $content,
                array_merge(array('Accept: application/json'), $http_headers)
            ));
            return true;
        } else {
            return $this->httpClient->doRequest(
                $method,
                $url,
                $content,
                array_merge(array('Accept: application/json'), $http_headers)
            );
        }
    }

    private function _login($login_type, array $args)
    {
        $content = array_merge(array('type' => $login_type), $args);
        return json_decode($this->api('POST', '/login', $content), true);
    }

    /**
     * Log in using a password
     *
     * @access public
     * @param  string    $username
     * @param  string    $password
     * @return array
     */

    public function login($username, $password)
    {
        $response = $this->_login('m.login.password', array('user' => $username, 'password' => $password));
        $this->user_id = $response['user_id'];
        $this->token = $response['access_token'];
        if (in_array('refresh_token', $response)) {
            $this->refresh_token = $response['refresh_token'];
        }
        return $response;
    }

    /**
     * Join a chat room
     *
     * @access public
     * @param  string    $room_id_or_alias
     * @return object
     */
    public function joinRoom($room_id_or_alias)
    {
        $path = "/join/".urlencode($room_id_or_alias);
        return json_decode($this->api('POST', $path));
    }

    /**
     * Send a message event
     *
     * @access public
     * @param  string    $room_id
     * @param  string    $event_type
     * @param  string    $content
     * @param  string    $txn_id
     */
    public function sendMessageEvent($room_id, $event_type, $content, $txn_id = NULL)
    {
        if (!isset($txn_id)) {
            $txn_id = $this->txn_id.intval(microtime(true)*1000);
        }

        $this->txn_id++;
        $path = '/rooms/'.urlencode($room_id).'/send/'.urlencode($event_type).'/'.urlencode($txn_id);

        return $this->api('PUT', $path, $content, array(), array(), true);
    }


    /**
     * Send a message
     *
     * @access public
     * @param  string    $room_id
     * @param  string    $text_content
     * @param  string    $html_content
     * @param  string    $msgtype
     */
    public function sendMessage($room_id, $text_content, $html_content = NULL, $msgtype = "m.text")
    {
        $content = array('body' => $text_content, 'msgtype' => $msgtype);
        if (isset($html_content)) {
            $content['format'] = 'org.matrix.custom.html';
            $content['formatted_body'] = $html_content;
        }
        return $this->sendMessageEvent($room_id, "m.room.message", $content);
    }
}

?>
