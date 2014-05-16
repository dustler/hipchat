<?php


namespace HipChat\Http;

use HipChat\Api;
use HipChat\Exception\Exception;

class Curl implements ClientInterface
{
    const QUERY_TYPE_POST = 'POST';
    const QUERY_TYPE_GET = 'GET';

    private $verify_ssl;
    private $auth_token;
    private $api_target;
    private $api_version;

    public function __construct($auth_token, $api_target, $api_version)
    {
        $this->verify_ssl = true;
        $this->auth_token = $auth_token;
        $this->api_version = $api_version;
        $this->api_target = $api_target;
    }

    public function curl_request($url, $post_data = null) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->verify_ssl);
        curl_setopt($ch, CURLOPT_HTTPHEADER,array (
            "Content-type: application/json"
        ));
        if (is_array($post_data)) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
        }
        $response = curl_exec($ch);

        if (strlen($response) == 0) {
            $errno = curl_errno($ch);
            $error = curl_error($ch);
            throw new Exception(Api::STATUS_BAD_RESPONSE,
                "CURL error: $errno - $error", $url);
        }

        // make sure we got a 200
        $code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        /*if ($code != Api::STATUS_OK) {
            throw new Exception($code,
                "HTTP status code: $code, response=$response", $url);
        }*/

        curl_close($ch);

        return $response;
    }

    public function make_request($api_method, $args = array(),
                                 $http_method = 'GET') {
        $args['auth_token'] = $this->auth_token;
        $url = "$this->api_target/$this->api_version/$api_method";
        $post_data = null;

        if ($http_method == 'GET') {
            $url .= '?'.http_build_query($args);
        } else {
            $post_data = $args;
        }

        $response = $this->curl_request($url, $post_data);

        $response = json_decode($response);

        return $response;
    }

    public function set_verify_ssl($bool = true) {
        $this->verify_ssl = (bool)$bool;
        return $this->verify_ssl;
    }
}