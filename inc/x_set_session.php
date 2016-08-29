<?
	session_start();
	include "../class/utils.class.php";
	error_reporting('E_ALL');
	$c = new utils();
	$c->connect();
	echo $c->setSession($_GET['mobile'],$_GET['data']);