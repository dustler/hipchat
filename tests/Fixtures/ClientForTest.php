<?php

namespace Tests;

use HipChat\Http\ClientInterface;
use Guzzle\Plugin\Mock\MockPlugin;
use Guzzle\Http\Message\Response;

class ClientForTest implements ClientInterface
{

    /**
     * @param $auth_token
     * @param $api_target
     * @param $api_version
     */
    public function __construct($auth_token, $api_target, $api_version)
    {
        // TODO: Implement __construct() method.
    }

    public function addResponse($code, $body, $headers = null)
    {
        $plugin = new MockPlugin();
        $response = new Response($code, $headers, $body);
        $plugin->addResponse($response);
        $this->plugin = $plugin;
        $this->getClient()->addSubscriber($plugin);
    }

    /**
     * @param string $api_method
     * @param array  $args
     * @param string $http_method
     *
     * @return mixed
     */
    public function make_request($api_method, $args = array(), $http_method = 'GET')
    {
        // TODO: Implement make_request() method.
    }
}