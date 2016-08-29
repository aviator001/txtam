<?
session_start();
	include "class/utils.class.php";
	$u = new utils();
	$u->connect();
	$file="members.php";
	if ($u->is_mobile()) $file="m.members.php";
?>
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html lang="en">
<head>	
	<title>txt.am - send images and video files without restriction, via sms</title>		
	<meta name="keywords" content="sugar daddy, gay, gay sugar daddy, sugardaddy, gay sugardaddy, wealthy gay men, twink, hot young gay men, boyz, gay dating, sugar daddy dating site, daddy dating, find daddy, find twink, GaySugarDaddyFinder" />		
	<meta name="description" content="A place that connects handsome wealthy and caring gay men to hot young gay males" />		
	<meta name="Author" content="Gautam Sharma" />		
	<meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=0" />		
	<link rel="icon" href="assets/img/favicon.ico">		
	<link rel="apple-touch-icon" href="assets/img/favicon.ico">		
	<style>
			@import url(https://fonts.googleapis.com/css?family=Economica);
			@import url(https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300);
			a {text-decoration:none}
	</style>
	<link href="assets/css/bootstrap.min.css" rel="stylesheet">		
	<link href="assets/css/layout-responsive.css" rel="stylesheet" type="text/css" />		
	<link href="assets/css/bootstrap.min.css" rel="stylesheet">
	<link href="assets/css/jquery-confirm.css" rel="stylesheet" type="text/css" />		

	<script type="text/javascript" src="assets/js/jquery.js"></script>
	<script type="text/javascript" src="assets/js/jquery-ui.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js" integrity="sha512-K1qjQ+NcF2TYO/eI3M6v8EiNYZfA95pQumfvcVrTHtwQVDG+aHRqLi/ETn2uB+1JqwYqVG3LIvdm9lj6imS/pQ==" crossorigin="anonymous"></script>
	<script src="assets/js/jquery-confirm.min.js"></script>
<body style="background:#ccc">
<div style="color:#00BFFF;position:absolute;margin:auto;top:40%;left:40%">
<input type=text value="<?=$_COOKIE['mobile'];?>" maxlength=10 id="mobile_txt" style="letter-spacing:5px;height:50px;width:200px;box-shadow:0 0 20px rgba(255,255,255,0.3);border-radius:4px;border:1px solid skyblue;padding-left:10px;font-family:Economica;font-size:32px">
<input type=button value="Go" style="font-family:Economica;margin-bottom:15px" class="btn btn-warning btn-lg" onclick="go()">
</div>
<script>
	function delCookie(cname) {
		var d = new Date();
		d.setTime(d.getTime());
		var expires = "expires="+d.toGMTString();
		document.cookie = cname + "=" + "" + "; " + expires;
	 }

	function setCookie(cname,cvalue,exdays)	{
		var d = new Date();
		d.setTime(d.getTime()+(exdays*24*60*60*1000));
		var expires = "expires="+d.toGMTString();
		document.cookie = cname + "=" + cvalue + "; " + expires;
	 }

	function getCookie(cname)	{
		var name = cname + "=";
		var ca = document.cookie.split(';');
		for(var i=0; i<ca.length; i++) {
		  var c = ca[i].trim();
		  if (c.indexOf(name)==0) return c.substring(name.length,c.length);
		}
		return "";
	}	
	var m
	function go() {
		m = document.getElementById('mobile_txt').value
		setCookie('mobile', m)
		var url = "inc/x_getLC.php?m=" + m
		console.log(url)
		var request = $.ajax({
		   type: 'GET',
			url: url,
			success: function(msg) {
				setCookie('long_code', msg)
				location.href='<?=$file;?>'
			}
		})				
	}
</script>
</body>
</html>