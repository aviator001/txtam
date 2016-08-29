<?
	include "../class/utils.class.php";
	$sms=new utils;
	$sms->connect();
	$to=$_GET['to'];
	$from=$sms->isValidMobile($_GET['from']);
	$message=$_GET['message'];
	$sms->sms($to,$from,urlencode($message));
?>