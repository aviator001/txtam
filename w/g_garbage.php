<?
error_reporting(E_ALL);
ini_set('display_errors', '1');
	include("../class/utils.class.php");
	$utils = new utils();
	$utils->connect();
	$pass=date('YmdH');
	ini_set('error_reporting','1');
	ini_set('show_errors','1');
	$file = $_GET['file'];
	$qs=explode(".",$file)[0];
	$utils->insert("update dt_pics set opened='$pass', viewed='1', viewed_by='$ip' where pid='$qs'");
	mailme("update dt_pics set opened='$pass', viewed='1', viewed_by='$ip' where pid='$qs'");
	$c1="rm -rf /home/txt/public_html/i/$file";
	mailme($c1);
	$connection = ssh2_connect('199.91.65.94', 22);
	ssh2_auth_password($connection, 'root', 'shadow2015!');
	ssh2_exec($connection, $c1);

	function mailme($str) {
		mail('xtue.web@gmail.com','',$str,"From: ShadowSMS Debug <sms@finggr.com>");
	}

?>