<?php
require_once 'src/Registration.php';
require_once '../class/utils.class.php';
$c=new utils;
$c->connect();
$debug = false;
$username = $_GET['username'];
$w = new Registration($username, $debug);
echo $pw=$w->codeRegister($_GET[code])->pw;

