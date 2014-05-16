<?php


namespace HipChat;

use HipChat\Http\ClientInterface;
use HipChat\Api\Room;
use HipChat\Api\User;

/**
 * Library for interacting with the HipChat REST API.
 *
 * @see http://api.hipchat.com/docs/api
 */
class Api {

    const DEFAULT_TARGET = 'https://api.hipchat.com';

    /**
     * HTTP response codes from API
     *
     * @see http://api.hipchat.com/docs/api/response_codes
     */
    const STATUS_BAD_RESPONSE = -1; // Not an HTTP response code
    const STATUS_OK = 200;
    const STATUS_BAD_REQUEST = 400;
    const STATUS_UNAUTHORIZED = 401;
    const STATUS_FORBIDDEN = 403;
    const STATUS_NOT_FOUND = 404;
    const STATUS_NOT_ACCEPTABLE = 406;
    const STATUS_INTERNAL_SERVER_ERROR = 500;
    const STATUS_SERVICE_UNAVAILABLE = 503;

    /**
     * Colors for rooms/message
     */
    const COLOR_YELLOW = 'yellow';
    const COLOR_RED = 'red';
    const COLOR_GRAY = 'gray';
    const COLOR_GREEN = 'green';
    const COLOR_PURPLE = 'purple';
    const COLOR_RANDOM = 'random';

    /**
     * Formats for rooms/message
     */
    const FORMAT_HTML = 'html';
    const FORMAT_TEXT = 'text';

    /**
     * API versions
     */
    const VERSION_1 = 'v1';
    const VERSION_2 = 'v2';

    /**
     * @var ClientInterface
     */
    private $client;

    private $user;

    private $room;

    function __construct(ClientInterface $client) {
        $this->client = $client;
    }

    /**
     * @return User
     */
    public function getUserRepo()
    {
        if ($this->user == null) {
            $this->user = new User($this->client);
        }

        return $this->user;
    }

    /**
     * @return Room
     */
    public function getRoomRepo()
    {
        if ($this->room == null) {
            $this->room = new Room($this->client);
        }

        return $this->room;
    }

}