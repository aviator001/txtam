<?php

	require_once 'src/Registration.php';
	require_once '../class/utils.class.php';
	error_reporting(E_ALL);
	$c=new utils;
	$c->connect();
	$debug = false;
	$username = $_GET['username'];
	$web_pswd = $_GET['web_pswd'];
	$qry = "INSERT INTO `whatsapp_users` (`userid`, `code`, `pswd`, `email`, `email_pswd`, `web_pswd`) VALUES ('$username','', '', '', '', '$web_pswd')";
	$c->insert($qry);
	if (!$c->err) {
		$c->show($c->id);
		$w = new Registration($username, $debug);
		$w->codeRequest('sms');
	} else {
		$err=$c->err;
		$c->show($err);
	}
	//username=13105676686
	//code=564180