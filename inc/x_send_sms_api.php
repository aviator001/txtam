<?
	include "../class/sms.class.php";
	$sms=new sms;
	$sms->connect();
	$to=$_GET['to'];
	$from=$sms->isValidMobile($_GET['from']);
	$message=$_GET['message'];
	$url=$sms->query("select * from dt_pics where pid='$message' limit 1")[0]['url'];
	$sms->sms($to,$from,$url);
?>