<?php

include "vendor/autoload.php";

use HipChat\Api;

$token = 'YourTokenKey';
$client = new \HipChat\Http\Client($token, HipChat\Api::DEFAULT_TARGET, 'v2');
$hc = new HipChat\Api($client);

// list rooms
foreach ($hc->getRoomRepo()->getRooms() as $room) {
    echo "$room->id = $room->name\n";
}

var_dump($hc->getRoomRepo()->testToken('My room'));

$hc->getRoomRepo()->messageRoom('My room', 'Hello from sdk');

foreach ($hc->getUserRepo()->getUsers() as $user) {
    echo "$user->id = $user->name".PHP_EOL;
}

$hc->getUserRepo()->messageUser('SomeUser', 'Hello from sdk');

$hc->getRoomRepo()->getRoomHistory('My room');
