<?php


namespace HipChat\Api;

use HipChat\Api;
use HipChat\Http\Curl;

class User
{

    /**
     * @var Curl
     */
    private $curl;

    public function __construct($curl)
    {
        $this->curl = $curl;
    }

    /**
     * List all users in the group.
     *
     * @param int  $startIndex    The start index for the result set. (default: 0)
     * @param int  $maxResults    The maximum number of results. Valid length range: 0 - 100 (default: 100)
     * @param bool $includeQuests Include active guest users in response. Otherwise, no guest users will be included. (default: false)
     * @param bool $includeDelete Include deleted users in response. (default: false)
     *
     * @return mixed
     */
    public function getUsers($startIndex = 0, $maxResults = 100, $includeQuests = false, $includeDelete = false)
    {
        $args = array(
            'start-index' => $startIndex,
            'max-results' => $maxResults,
            'include-guests' => $includeQuests,
            'include-deleted' => $includeDelete
        );

        $response = $this->curl->make_request("user", $args);

        return $response->items;
    }

    /**
     * Private message user
     *
     * @url https://www.hipchat.com/docs/apiv2/method/private_message_user
     *
     * @param string $userId         Required. The id, email address, or mention name (beginning with an '@') of the user to send a message to.
     * @param string $message        Required. The message body. Valid length range: 1 - 10000
     * @param bool   $notify         Whether or not this message should trigger a notification for people in the room
     *                               (change the tab color, play a sound, etc). Each recipient's notification preferences are taken into account.
     *                               (default: false)
     * @param string $color          Background color for message. Valid values: yellow, red, green, purple, gray, random (default: 'yellow')
     * @param string $message_format Determines how the message is treated by our server and rendered inside HipChat applications
     *                               default: html
     *                               allowed: html, text
     *
     * @return bool
     */
    public function messageUser($userId, $message, $notify = false, $color = Api::COLOR_YELLOW, $message_format = Api::FORMAT_HTML)
    {
        $args     = array(
            'message'        => $message,
            'notify'         => (bool) $notify,
            'color'          => $color,
            'message_format' => $message_format
        );
        $response = $this->curl->make_request("user/$userId/message", $args, Curl::QUERY_TYPE_POST);

        return ($response->status == 'oK');
    }

    /**
     * Get a user's details.
     *
     * @url https://api.hipchat.com/v2/user/{id_or_email}
     *
     * @param string $user_id_or_email Required. The id, email address, or mention name (beginning with an '@') of the user to view.
     *
     * @return mixed
     */
    public function getUser($user_id_or_email)
    {
        $response = $this->curl->make_request("user/$user_id_or_email");

        return $response->user;
    }

    /**
     * Delete a user.
     *
     * @url https://www.hipchat.com/docs/apiv2/method/delete_user
     *
     * @param $user_id_or_email
     *
     * @return bool
     */
    public function deleteUser($user_id_or_email)
    {
        $response = $this->curl->make_request('user/' . $user_id_or_email, array(), 'DELETE');

        return ($response->status == 'OK');
    }
} 