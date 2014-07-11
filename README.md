# HipChat api for PHP

A PHP library for interacting with the HipChat REST API v2.

## Composer Installation

HipChat-PHP can be installed with Composer (http://getcomposer.org/).  Add the following to your
composer.json file.  Composer will handle the autoloading.

```json
{
    "require": {
        "dustler/hipchat": "1.0.1"
    }
}
```

## Usage

```php
include "vendor/autoload.php";

use HipChat\Api;

$token = 'YourTokenKey';
$client = new HipChat\Http\Curl($token, HipChat\Api::DEFAULT_TARGET, 'v2');
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
```
