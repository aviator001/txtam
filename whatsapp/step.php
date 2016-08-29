<?
error_reporting(E_ALL);
require_once 'src/whatsprot.class.php';
require_once '../class/utils.class.php';
$c=new utils;
$c->connect();
$p=$c->query("select * from whatsapp_users where userid='". $_REQUEST['username'] ."'");
$password=$p[0]['pswd'];
$username = $p[0][userid];
$nickname = $_REQUEST[nick];
$debug = true;

// Create a instance of WhastPort.
$w = new WhatsProt($username, $nickname, $debug);

$w->connect(); // Connect to WhatsApp network
$w->loginWithPassword($password); // logging in with the password we got!

$target = '13105676686'; // The number of the person you are sending the message
$message = 'Hi! :) this is a test message';
$w->sendMessage($target , $message);