<?php


namespace HipChat\Http;

use Guzzle\Http\Client as GuzzleClient;

class Client implements ClientInterface
{
    const QUERY_TYPE_POST = 'POST';
    const QUERY_TYPE_GET = 'GET';

    /**
     * @var \Guzzle\Http\Client
     */
    protected $client;

    public function __construct($auth_token, $api_target, $api_version)
    {
        $this->client = new GuzzleClient($api_target.'/{version}',array( 'version' => $api_version));
        $this->client->setDefaultOption('header',array('Content-Type' => 'application/json'));
        $this->client->setDefaultOption('query',array('auth_token' => $auth_token));
    }

    /**
     * @param string  $api_method
     * @param array  $args
     * @param string $http_method
     *
     * @return \Guzzle\Http\Message\Response|mixed
     */
    public function make_request($api_method, $args = array(), $http_method = 'GET') {

        $request = $this->client->createRequest($http_method, $api_method, $args);
        $response = $request->send();

        return json_decode($response->getBody());
    }
} 