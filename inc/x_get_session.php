<?
	session_start();
	include "utils.class.php";
	error_reporting(E_ALL);
	$c = new utils();
	$c->connect();
	echo $c->query("select data from sms_sessions where mobile='".$_GET[mobile]."'")[0]['data'];
	