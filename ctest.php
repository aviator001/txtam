<?
require('../vendor/autoload.php');

use WebSocket\Client;
$client = new Client("ws://199.91.65.94:1337");
echo $client->send("1");
echo $client->receive(); // Will output 'Hello WebSocket.org!'




$client = new Client("ws://199.91.65.94:7777");
echo $client->send("1");
echo $client->receive(); // Will output 'Hello WebSocket.org!'