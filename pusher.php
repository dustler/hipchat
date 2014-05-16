<?php

include "vendor/autoload.php";

$token = 'TNTPR4iqZ57cguUK3Svco8pcKDL8FuD9Tnx3u87O';
$hc = new HipChat\Api($token, HipChat\Api::DEFAULT_TARGET, 'v2');

// list rooms
foreach ($hc->getRoomRepo()->getRooms() as $room) {
    echo "$room->id = $room->name\n";
}

// send a message to the 'Development' room from 'API'
//$hc->getRoomRepo()->message_room('Nethouse', 'Hello world');

foreach ($hc->getUserRepo()->getUsers() as $user) {
    echo "$user->id = $user->name".PHP_EOL;
}

/*foreach ($hc->getUsers() as $user) {
    $hc->getUserRepo()->message_user($user->id, 'Привееееет!');
}*/

//$hc->message_user('cnam812@gmail.com', 'Привееееет!');

$hc->getRoomRepo()->getRoomHistory('Nethouse');
