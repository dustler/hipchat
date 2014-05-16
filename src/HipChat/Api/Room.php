<?php


namespace HipChat\Api;

use HipChat\Http\ClientInterface;
use HipChat\Api;

class Room
{

    /**
     * @var ClientInterface
     */
    private $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * List non-archived rooms for this group.
     *
     * @url https://www.hipchat.com/docs/apiv2/method/get_all_rooms
     *
     * Response Required. Array of objects containing:
     ** id - Required. ID of the room.
     ** links - Required. URLs to retrieve room information.
     ** name -  Required. Name of the room.
     *** self - Required. The URL to use to retrieve the full room information.
     *** webhooks - Required. The URL to use to retrieve webhooks registered for this room.
     *** members - The URL to use to retrieve members for this room. Only available for private rooms.
     *
     * @return \stdClass
     */
    public function getRooms()
    {
        $response = $this->client->make_request('room');

        return $response->items;
    }

    /**
     * Get room details.
     *
     * @param string $room_id Required. The id or name of the room. Valid length range: 1 - 100
     *
     * @return \stdClass
     */
    public function getRoom($room_id)
    {
        $response = $this->client->make_request("room/$room_id");

        return $response;
    }

    /**
     * Send a message to a room.
     *
     * @url https://www.hipchat.com/docs/apiv2/method/send_room_notification
     *
     * @param string $room_id        Required. The id or name of the room. Valid length range: 1 - 100
     * @param string $message        Required. The message body. Valid length range: 1 - 10000
     * @param bool   $notify         Whether or not this message should trigger a notification for people
     *                               in the room (change the tab color, play a sound, etc). Each recipient's
     *                               notification preferences are taken into account.
     * @param string $color          Background color for message. Valid values:
     *                               yellow, red, green, purple, gray, random (default: 'yellow')
     * @param string $message_format Determines how the message is treated by our server and rendered inside HipChat applications
     *                               Default format for message. Valid values:
     *                               html, text
     *
     * @return bool
     */
    public function messageRoom($room_id, $message, $notify = false, $color = Api::COLOR_YELLOW, $message_format = Api::FORMAT_HTML)
    {

        $args     = array(
            'message'        => $message,
            'notify'         => (bool) $notify,
            'color'          => $color,
            'message_format' => $message_format
        );
        $response = $this->client->make_request("room/$room_id/notification", $args, Curl::QUERY_TYPE_POST);
        return ($response->status == 'sent');
    }

    /**
     * Fetch chat history for this room.
     *
     * @url https://www.hipchat.com/docs/apiv2/method/view_history
     *
     * @param string $room_id    Required. The id or name of the room. Valid length range: 1 - 100
     * @param string $date       Either the latest date to fetch history for in ISO-8601 format, or 'recent'
     *                           to fetch the latest 75 messages. Note, paging isn't supported for 'recent',
     *                           however they are real-time values, whereas date queries may not include the most recent messages.
     *                           (default: 'recent')
     * @param string $timezone   Your timezone. Must be a supported timezone. (default: 'UTC')
     * @param int    $startIndex The offset for the messages to return. Only valid with a non-recent data query. (default: 0)
     * @param int    $maxResults The maximum number of messages to return. Only valid with a non-recent data query.(default: 100)
     * @param bool   $reverse    Reverse the output such that the oldest message is first. For consistent paging, set to 'false'.
     *                           (default: true)
     *
     * @return \stdClass
     */
    public function getRoomHistory($room_id, $date = 'recent', $timezone = 'UTC', $startIndex = 0, $maxResults = 100, $reverse = true)
    {
        $response = $this->client->make_request("room/$room_id/history", array(
            'date'        => $date,
            'timezone'    => $timezone,
            'start-index' => $startIndex,
            'max-results' => $maxResults,
            'reverse'     => $reverse
        ));

        return $response->items;
    }

    /**
     * Creates a new room
     *
     * Response
     *
     * * id - Required. The unique identifier for the created entity. May be null.
     * * links-  Required.
     * * * self - Required.
     *
     * @url https://www.hipchat.com/docs/apiv2/method/create_room
     *
     * @param string $name        Whether or not to enable guest access for this room.
     * @param bool   $guestAccess Required. Name of the room.Valid length range: 1 - 50
     * @param int    $ownerUserId The id, email address, or mention name (beginning with an '@')
     *                            of the room's owner. Defaults to the current user.
     * @param string $privacy     Whether the room is available for access by other users or not.
     *                            Valid values: public, private (default: 'public')
     *
     * @return \stdClass
     */
    public function addRoom($name, $guestAccess, $ownerUserId, $privacy = 'public')
    {
        $args     = array(
            'name'          => $name,
            'guest_access'  => (bool) $guestAccess,
            'owner_user_id' => $ownerUserId,
            'privacy'       => $privacy
        );
        $response = $this->client->make_request("room", $args, Curl::QUERY_TYPE_POST);

        return $response;
    }

    /**
     * Deletes a room and kicks the current participants.
     *
     * @url https://www.hipchat.com/docs/apiv2/method/delete_room
     *
     * @param string $room_id_or_name Required. The id or name of the room. Valid length range: 1 - 100
     *
     * @return mixed
     */
    public function deleteRoom($room_id_or_name)
    {
        $response = $this->client->make_request("room/" . $room_id_or_name, array(),'DELETE');

        return ($response->status == 'ok');
    }

    /**
     * Invite a user to a room.
     *
     * @url https://www.hipchat.com/docs/apiv2/method/invite_user
     *
     * @param string $room_id_or_name  Required. The id or name of the room. Valid length range: 1 - 100
     * @param string $user_id_or_email Required. The id, email address, or mention name (beginning with an '@') of the user to invite.
     *                                 Valid length range: 1 - 100
     * @param string $reason           The reason to give to the invited user.Valid length range: 1 - 250
     *
     * @return mixed
     */
    public function inviteUser($room_id_or_name, $user_id_or_email, $reason)
    {
        $args = array(
            'reason' => $reason
        );

        $response = $this->client->make_request('room/' . $room_id_or_name . '/invite/' . $user_id_or_email, $args, Curl::QUERY_TYPE_POST);

        return $response;
    }

    /**
     * Testing a token
     */
    public function testToken($room_id)
    {
        $args = array(
            'auth_test' => 'true'
        );
        $response = $this->client->make_request("room/$room_id/notification", $args);

        return $response;
    }

    public function setRoomTopic($room_id, $topic, $from = null) {
        $args = array(
            'room_id' => $room_id,
            'topic' => utf8_encode($topic),
        );
        if ($from) {
            $args['from'] = utf8_encode($from);
        }
        $response = $this->client->make_request("rooms/topic", $args, 'POST');
        return ($response->status == 'ok');
    }
}