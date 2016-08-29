<?php
require_once 'src/Registration.php';
require_once '../class/utils.class.php';
$c=new utils;
$c->connect();
$debug = true;
$username = $_GET['username'];
$code = $_GET['code'];
$w = new Registration($username, $debug);

    try {
        $result = $w->codeRegister(trim($code));
        echo $result->pw;
    } catch (Exception $e) {
        echo $e->getMessage()."\n";
        exit(0);
    }
$c->insert("update whatsapp_users set pswd='" . $result->pw . "', code='$code' where userid='". $result->login ."'");
echo $c->err;