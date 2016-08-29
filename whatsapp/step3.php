<?
error_reporting(E_ALL);
require_once 'src/whatsprot.class.php';
require_once '../class/utils.class.php';
$c=new utils;
$c->connect();
$p=$c->query("select * from whatsapp_users where userid='". $_REQUEST['username'] ."'");
$waPassword=$p[0]['pswd'];
	
$username = $_REQUEST['username'] ;    // Your number with country code, ie: 34123456789
$nickname = '';    // Your nickname, it will appear in push notifications
$debug    = true;  // Shows debug log, this is set to false if not specified
$log      = true;  // Enables log file, this is set to false if not specified
$password = $waPassword; // The one we got registering the number

// Create a instance of WhastPort.
$w = new WhatsProt($username, $nickname, $debug);

$w->connect(); // Connect to WhatsApp network
$w->loginWithPassword($password); // logging in with the password we got!
$c->show($w);
