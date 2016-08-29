<?
	include('x_lib.php');
	$f = $_REQUEST['d'];
	$i = trim($_REQUEST['i']);
	if (strlen($i) == 10) $i = "1".$i;
	if(mysql_num_rows(mysql_query("select id from sms_subscribers where ($f = '$i')"))) echo "1";
?> 
