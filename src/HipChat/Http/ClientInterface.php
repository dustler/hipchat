<?php


namespace HipChat\Http;


interface ClientInterface
{
    /**
     * @param $auth_token
     * @param $api_target
     * @param $api_version
     */
    public function __construct($auth_token, $api_target, $api_version);

    /**
     * @param string  $api_method
     * @param array  $args
     * @param string $http_method
     *
     * @return mixed
     */
    public function make_request($api_method, $args = array(), $http_method = 'GET');
}