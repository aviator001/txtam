<?
	session_start();
	include "../class/utils.class.php";
	error_reporting(E_ALL);
	$u = new utils;
	$u->connect("199.91.65.82","finggr");
	
	$from_real_number = $_GET['m'];
	$q = $u->query("select long_code from sms_long_codes where mobile = '$from_real_number'");
	
	if (empty($q[0][long_code])) {
		$from_long_code=$u->query("select long_code from sms_long_codes where mobile='' and long_code not in (select long_code from sms_tracker) order by id asc limit 1")[0]['long_code'];		
		$u->insert("UPDATE `sms_long_codes` SET `mobile`='$from_real_number', `active`='1' WHERE (`long_code`='$from_long_code') AND (`mobile`='')");
	} else {
		$from_long_code=$q[0][long_code];
	}
	echo $from_long_code;
