<?
session_start();
require_once 'facebook-sdk-v5/autoload.php';
$fb = new Facebook\Facebook([
  'app_id' => '166355080403596',
  'app_secret' => 'b2adc0910bbead52935127839d923f0c',
  'default_graph_version' => 'v2.4',
  ]);
$helper = $fb->getRedirectLoginHelper();
$permissions = ['public_profile','email','user_friends','publish_actions']; // Optional permissions
$loginUrl = $helper->getLoginUrl('http://txt.am/callback.php', $permissions);
setCookie("admin",'admin',time()+3600,"/");
include "class/utils.class.php"; 
$c = new utils;
$table=" dt_members ";
$c->connect(); 
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
	<link href="assets/css/bootstrap.min.css" rel="stylesheet">		
	<link href="assets/css/layout-responsive.css" rel="stylesheet" type="text/css" />		
	<link href="assets/css/stream.css" rel="stylesheet" type="text/css" />
	<link href="assets/css/bootstrap.min.css" rel="stylesheet">

		<style>
				@import url(https://fonts.googleapis.com/css?family=Economica);
				@import url(https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300);
				.tooltipsy {
					color: #0093D9;
					background-size:cover;
					border: none;
					border-radiuse:4px;
					text-shadow:0px 0px 1px #333;
					opacity:1
				}
				.eee, .content{
					width:400px;
				}
				.biggy {
					margin-left:-100px;margin-top:-200px;width:200px;height:200px;border:5px solid white;
					opacity:1;
					boxshadow:0 0 25px #333
				}
				a {text-decoration:none}
		</style>
		<link href="assets/css/jquery-confirm.css" rel="stylesheet" type="text/css" />		
		<link href="assets/css/tooltipster.css" rel="stylesheet" type="text/css" />		
		<link href="assets/css/layout-responsive.css" rel="stylesheet" type="text/css" />		
		<link href="assets/css/dropzones.css" rel="stylesheet" type="text/css" />		
		<link href="assets/css/dropzone.css" rel="stylesheet" type="text/css" />		
		<link href="assets/css/glyphicons.css" rel="stylesheet" type="text/css" />		
		<link href="http://gaysugardaddyfinder.com/assets/css/style-20.css" rel="stylesheet">	
		<link href="http://gaysugardaddyfinder.com/assets2/css/retina.min.css" rel="stylesheet">
		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
		<link href='https://fonts.googleapis.com/css?family=Raleway:100|Nothing+You+Could+Do|Wire+One|Averia+Sans+Libre|Flavors' rel='stylesheet' type='text/css'>
<!-- Latest compiled and minified CSS -->
<link href="assets/css/bootstrap.min.css" rel="stylesheet">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
		<style>
				@import url(https://fonts.googleapis.com/css?family=Economica|Kristi);
				@import url(https://fonts.googleapis.com/css?family=Poiret+One);
				.notify {
					display:none;
					right:0;
					left:0;
					margin:auto;
					top:10px;
					z-index:99999999999999;
					background:#333;
					box-shadow:0 0 6px #000;
					width:300px;
					height:40px;
					position:absolute;
					border-radius:4px;
					font-family:Open Sans Condensed;
					color:#000;
					text-shadow:0 0 1px #fff
				}
				.tooltipsy {
					color: #0093D9;
					background-size:cover;
					border: none;
					border-radiuse:4px;
					text-shadow:0px 0px 1px #333;
					opacity:1
				}
				.eee, .content{
					width:400px;
				}
				.biggy {
					margin-left:-100px;margin-top:-200px;width:200px;height:200px;border:5px solid white;
					opacity:1;
					boxshadow:0 0 25px #333
				}
				a {text-decoration:none}
				.jconfirm {
					background:#333;
					opacity:0.9;
					text-align:center;
				}
				.jconfirm-box, container,jconfirm-box-container{
					background:pink;
					text-align:center;
					width:350px;
					position:absolute;
					left:0;
					right:0;
					margin:auto;
					opacity:1;
				}
				.ccc{
					text-shadow: 0 0 1px #fff;
					color:#000;
					width:100%;
					height:30px;
					margin-top:5px;
					opacity:1;
					font-family:Open Sans Condensed;
					font-size:18px;
					}
				.online{
					color:#f0f0f0;
					width:300px;
					opacity:1;
					font-family:Open Sans Condensed;
					font-size:18px;
					background: #333
				}
				.notify{
					opacity:1;
				}
				#total {
					color:red
				}
				.OL {
					font-family:Economica;
					font-size:14px;
					color: #000;
					text-shadow: 1px 1px 1px #fff;
				}
				.dropzone-previews {
					margin-left:-50px
				}
				a,div{
					font-family:Nothing You Could Do;font-size:36px
				}
				.cust{
					max-width:120px;border-radius:10px
				}

.wb {
	background: url(assets/grey_button.png);
	background-size: 90px,90px;
	width:90px;
	height:90px;
	color: #000;
	text-shadow: 1px 1px 1px #fff;
}
.wb_o {
	background: url(assets/grey_button_o.png);
	background-size: 90px,90px;
	width:90px;
	height:90px;
	color: #fff;
}
.wb_c {
	background: url(assets/grey_button_c.png);
	background-size: 90px,90px;
	width:90px;
	height:90px;
	color: #000;
	text-shadow: 1px 1px 1px #fff;
}

				</style>

	<!-- Latest compiled and minified JavaScript -->
	
			<script type="text/javascript" src="assets/js/jquery.js"></script>
			<script type="text/javascript" src="assets/js/jquery-ui.min.js"></script>
			<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js" integrity="sha512-K1qjQ+NcF2TYO/eI3M6v8EiNYZfA95pQumfvcVrTHtwQVDG+aHRqLi/ETn2uB+1JqwYqVG3LIvdm9lj6imS/pQ==" crossorigin="anonymous"></script>
			<script src="assets/js/jquery-confirm.min.js"></script>
			<script type="text/javascript" src="assets/js/framework.js"></script>		
			<script type="text/javascript" src="assets/js/tooltipsy.min.js"></script>
			<script type="text/javascript" src="assets/jquery.min.js"></script> 
			<script type="text/javascript" src="assets/jquery-ui.min.js"></script> 
			<script src="assets/js/dropzone.min.js"></script>	 
			<script src="assets/js/bootstrap.min.js"></script>

			<body onload="init()" style="background:url(assets/backgrounds/b122.jpg) center center no-repeat;background-size:100% 100%">
				<img id="loading" src="assets/loader_b.gif" style="z-index:99999999999999999;display:none;position:absolute;top:100px;right:0;left:0;margin:auto">
				
				<div style="position:absolute;width:100%;height:100%;top:0px;left:0px;right:0px;bottom:0px;background:#000;opacity:0.9;z-index:9999100000;display:none;overflow-y:auto" id="pay_modal"></div>	
				<div style="box-shadow: 10px 10px 50px #000;display:none;margin:auto;position:absolute;left:0;right:0;color:#000;text-shadow:1px 1px 1px #fff;top:100px;z-index:9999100001;padding:10px;width:250px;height:200px;border-radius:6px;background:#f0f0f0" id="txt_info">		
					<img onclick="this.style.display='none';document.all.pay_modal.style.display='none';document.all.txt_info.style.display='none'" src="assets/img/close.png" style="cursor:hand;cursor:pointer;position:absolute;top:5px;right:5px;width:25px">
				</div>
				<div id="userso" style="display:none;right:0;margin:auto;top:10px;z-index:99999999999999;background:#333;box-shadow:0 0 20px #000;width:300px;height:40px;position:absolute;border-radius:4px;font-family:Open Sans Condensed"></div>
				<div id="usersOnline" style="display:none;position:absolute;top:0px;bottom:0px;right:-10px;z-index:99999999999;overflow-y:auto;overflow-x:hidden;width:320px;height:100%;"></div>
				<div class="modal" id="modal" onclick="this.style.display='none'" style="position:absolute;display:none;cursor:hand;cursor:pointer" onmouseover="clear_all_timers()">
					<div class="modal_bg" id="modal_bg"></div>
					<div class="modal_window" id="modal_window" style="display:block;background:none;box-shadow:none;top:0"></div>
				</div>
<script>
	var editMe=false
	function init() {
		delCookie("editImg")
	}
	function photo_filter(obj,f) {
		var osrc=document.getElementById('img').src
		document.getElementById('img').src='assets/img/snow.gif'
		document.getElementById('img').style.opacity='0.5'
		document.getElementById('img').style.width='300px'
		document.getElementById('img').style.height='300px'
		document.getElementById('loading').style.display='block'
	
		if (obj==27) {
			var annotate=prompt('Text to annotate photo with?')
		}
		//jwait('Processing...')
		var url = 'inc/x_photo_filters.php?filter=' + obj + '&filename=' + f + '&annotate=' + annotate
		console.log(url)
		var request = $.ajax({
			url: url,
			type: "GET",
			dataType: "html",
			cache: false,
			success: function(msg) {
				console.log(msg)
				document.getElementById('loading').style.display='none'
				document.getElementById('img').style.opacity='1'
				document.getElementById('img').src=osrc+'?<?=rand(111111111111,999999999999);?>'
			}
		})		
	}
	function send_sms(f) {
		var from=getCookie('long_code')
		if (!from) from='13234581949'
		var message=f
		var url = 'inc/x_send_sms_api.php?to=' + getCookie('mobile') + '&from='+from+'&message=' + message
		var request = $.ajax({
			url: url,
			type: "GET",
			dataType: "html",
			cache: false,
			success: function(msg) {
				alert('Image Sent')
				history.go(-1)
			}
		})		
	}
	function send_back() {
		location.href='http://txt.am'
	}
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
</script>

<script type="text/javascript">
	var err_img = "assets/avatars/10.png" 
	var err_img1 = 'assets/images/tie.jpg'
  WebFontConfig = {
    google: { families: [ 
		'Poiret+One::latin', 
	]}
  };
  (function() {
    var wf = document.createElement('script');
    wf.src = ('https:' == document.location.protocol ? 'https' : 'http') +
      '://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
    wf.type = 'text/javascript';
    wf.async = 'true';
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(wf, s);
  })();
  
  function toggle_class(obj) {
	obj.className='col-md-1 wb_c'
	if (obj.className=='col-md-1 wb_o') obj.style.color='#fff'
		else obj.style.color='#000'
  }
  function in_out(obj) {
	if (obj.className=='col-md-1 wb_c') obj.className='col-md-1 wb_c'
		else obj.className='col-md-1 wb'
	
	obj.style.color='#000'
	obj.style.textShadow='1px 1px 1px #fff'
}
  function out_in(obj) {
	if (obj.className=='col-md-1 wb_c') obj.className='col-md-1 wb_c'
		else obj.className='col-md-1 wb_o'

	obj.style.color='#fff'
	obj.style.textShadow='none'

		}
	</script>
	<?
		$src=$_GET["imgName"];
		$ext=$_GET["ext"];
		$xid=$src. '.' . $ext;
		$fid="'$xid'";
		
	?>
		<br><br><img id="img" style="left:0;right:0;margin:auto;position:absolute;width:300px;border:10px solid #fff;box-shadow:0 0 100px #000" src="http://txt.am/snappy/<?=$src;?>.<?=$ext;?>">
	<?
					$r=array('','red','brown','#222','#111','#535632','#003300','#000','#1c1c1c','maroon');
					$str = '<div style="margin:auto;margin-top:325px;left:0;right:0;position:absolute;width:325px;" class="container">
						<div class="row">
						<span class="col-md-4"><a class="btn btn-warning btn-xs" href="#" style="width:90px;font-size:24px;font-family:Economica;margin:0px" onclick=\'send_sms("'. $src .'")\'>Send!</a></span>
						<span class="col-md-4"><a class="btn btn-info btn-xs" href="#" style="width:90px;font-size:24px;font-family:Economica;margin:0px"  onclick="send_back()">Back</a></span>
						<span class="col-md-4"><a class="btn btn-danger btn-xs" style="width:90px;font-size:24px;font-family:Economica;margin:0px" onclick="photo_filter(15,'.$fid.'); history.go(0)">Reset</a></span>
						</div>
						</div>
						<div style="border-radius:10px;margin:auto;margin-top:400px;left:0;right:0;position:absolute;width:320px;padding:20px;background:;font-size:1.5em;background:#f0f0f0;height:700px;opacity:0.8" class="www_box5"></div>
						<div style="border-radius:0 0 5px 5px;margin:auto;margin-top:400px;left:0;right:0;position:absolute;width:320px;padding:20px;background:;font-size:1.5em" class="row">

							<span onmouseover="out_in(this)" onmouseout="in_out(this)" onmouseover="out_in(this)" class="col-md-1 wb" onclick="photo_filter(27,'.$fid.');toggle_class(this)" style=\'cursor:hand;cursor:pointer;color:#000;font-family:Nothing You Could Do;font-family:Open Sans Condensed;font-size:15px;;padding-top:35px;text-align:center;\'>ANNOTATE</span>
							<span onmouseout="in_out(this)" onmouseover="out_in(this)" class="col-md-1 wb" onclick="photo_filter(1,'.$fid.');toggle_class(this)" style=\'cursor:hand;cursor:pointer;color:#000;font-family:Kranky;font-family:Open Sans Condensed;font-size:15px;;padding-top:35px;;text-align:center;\'>ROTATE +</span>
							<span onmouseout="in_out(this)" onmouseover="out_in(this)" class="col-md-1 wb" onclick="photo_filter(2,'.$fid.');toggle_class(this)" style=\'cursor:hand;cursor:pointer;color:#000;font-family:Nixie One;font-family:Open Sans Condensed;font-size:15px;font-size:15px;padding-top:35px;;text-align:center;\'>ROTATE -</span>
							<span onmouseout="in_out(this)" onmouseover="out_in(this)" class="col-md-1 wb" onclick="photo_filter(4,'.$fid.');toggle_class(this)" style=\'cursor:hand;cursor:pointer;color:#000;font-family:Special Elite;font-family:Open Sans Condensed;font-size:18px;font-size:15px;padding-top:35px;;text-align:center;\'>INVERT</span>
							<span onmouseout="in_out(this)" onmouseover="out_in(this)" class="col-md-1 wb" onclick="photo_filter(30,'.$fid.');toggle_class(this)" style=\'cursor:hand;cursor:pointer;color:#000;font-family:Mountains of Christmas;font-family:Open Sans Condensed;font-size:18px;font-size:15px;padding-top:35px;;text-align:center;\'>SEPIA</span>
							<span onmouseout="in_out(this)" onmouseover="out_in(this)" class="col-md-1 wb" onclick="photo_filter(28,'.$fid.');toggle_class(this)" style=\'cursor:hand;cursor:pointer;color:#000;font-family:Griffy;font-family:Open Sans Condensed;font-size:18px;font-size:15px;padding-top:35px;text-align:center;\'>SOLARIZE</span>
							<span onmouseout="in_out(this)" onmouseover="out_in(this)" class="col-md-1 wb" onclick="photo_filter(2001,'.$fid.');toggle_class(this)" style=\'cursor:hand;cursor:pointer;color:#000;font-family:Miltonian;font-family:Open Sans Condensed;font-size:18px;font-size:15px;padding-top:35px;text-align:center;\'>DESPECKLE</span>
							<span onmouseout="in_out(this)" onmouseover="out_in(this)" class="col-md-1 wb" onclick="photo_filter(2002,'.$fid.');toggle_class(this)" style=\'cursor:hand;cursor:pointer;color:#000;font-family:Cabin Sketch;font-family:Open Sans Condensed;font-size:18px;font-size:15px;padding-top:35px;text-align:center;\'>EQUALIZE</span>
							<span onmouseout="in_out(this)" onmouseover="out_in(this)" class="col-md-1 wb" onclick="photo_filter(2003,'.$fid.');toggle_class(this)" style=\'cursor:hand;cursor:pointer;color:#000;font-family:Finger Paint;font-family:Open Sans Condensed;font-size:18px;font-size:15px;padding-top:35px;text-align:center;\'>EMBOSS</span>
							<span onmouseout="in_out(this)" onmouseover="out_in(this)" class="col-md-1 wb" onclick="photo_filter(1000,'.$fid.');toggle_class(this)" style=\'cursor:hand;cursor:pointer;color:#000;font-family:Love Ya Like A Sister;font-family:Open Sans Condensed;font-size:18px;font-size:15px;padding-top:35px;;text-align:center;\'>TOAST</span>
							<span onmouseout="in_out(this)" onmouseover="out_in(this)" class="col-md-1 wb" onclick="photo_filter(1001,'.$fid.');toggle_class(this)" style=\'cursor:hand;cursor:pointer;color:#000;font-family:UnifrakturMaguntia;font-family:Open Sans Condensed;font-size:18px;font-size:15px;padding-top:35px;;text-align:center;\'>NASHVILLE</span>
							<span onmouseout="in_out(this)" onmouseover="out_in(this)" class="col-md-1 wb" onclick="photo_filter(1002,'.$fid.');toggle_class(this)" style=\'cursor:hand;cursor:pointer;color:#000;font-family:Shadows Into Light Two;font-family:Open Sans Condensed;font-size:18px;font-size:15px;padding-top:35px;;text-align:center;\'>LOMO</span>
							<span onmouseout="in_out(this)" onmouseover="out_in(this)" class="col-md-1 wb" onclick="photo_filter(1003,'.$fid.');toggle_class(this)" style=\'cursor:hand;cursor:pointer;color:#000;font-family:Ravi Prakash;font-family:Open Sans Condensed;font-size:18px;font-size:15px;padding-top:35px;;text-align:center;\'>KELVIN</span>
							<span onmouseout="in_out(this)" onmouseover="out_in(this)" class="col-md-1 wb" onclick="photo_filter(25,'.$fid.');toggle_class(this)" style=\'cursor:hand;cursor:pointer;color:#000;font-family:Shadows Into Light;font-family:Open Sans Condensed;font-size:18px;font-size:15px;padding-top:35px;;text-align:center;\'>MTN-BLUR</span>
							<span onmouseout="in_out(this)" onmouseover="out_in(this)" class="col-md-1 wb" onclick="photo_filter(22,'.$fid.');toggle_class(this)" style=\'cursor:hand;cursor:pointer;color:#000;font-family:Rye;font-family:Open Sans Condensed;font-size:18px;font-size:15px;padding-top:35px;;text-align:center;\'>RAD-BLUR</span>
							<span onmouseout="in_out(this)" onmouseover="out_in(this)" class="col-md-1 wb" onclick="photo_filter(24,'.$fid.');toggle_class(this)" style=\'cursor:hand;cursor:pointer;color:#000;font-family:League Script;font-family:Open Sans Condensed;font-size:18px;font-size:15px;padding-top:35px;;text-align:center;\'>OIL</span>
							<span onmouseout="in_out(this)" onmouseover="out_in(this)" class="col-md-1 wb" onclick="photo_filter(23,'.$fid.');toggle_class(this)" style=\'cursor:hand;cursor:pointer;color:#000;font-family:Euphoria Script;font-family:Open Sans Condensed;font-size:18px;font-size:15px;padding-top:35px;;text-align:center;\'>CHARCOAL</span>
							<span onmouseout="in_out(this)" onmouseover="out_in(this)" class="col-md-1 wb" onclick="photo_filter(2007,'.$fid.');toggle_class(this)" style=\'cursor:hand;cursor:pointer;color:#000;font-family:Ravi Prakash;font-family:Open Sans Condensed;font-size:18px;font-size:15px;padding-top:35px;;text-align:center;\'>GOTHAM</span>
							<span onmouseout="in_out(this)" onmouseover="out_in(this)" class="col-md-1 wb" onclick="photo_filter(2006,'.$fid.');toggle_class(this)" style=\'cursor:hand;cursor:pointer;color:#000;font-family:Ravi Prakash;font-family:Open Sans Condensed;font-size:18px;font-size:15px;padding-top:35px;;text-align:center;\'>POLAROID</span>
							<span onmouseout="in_out(this)" onmouseover="out_in(this)" class="col-md-1 wb" onclick="photo_filter(2005,'.$fid.');toggle_class(this)" style=\'cursor:hand;cursor:pointer;color:#000;font-family:Ravi Prakash;font-family:Open Sans Condensed;font-size:18px;font-size:15px;padding-top:35px;;text-align:center;\'>ENHANCE</span>
					</div>';
				echo $str;
?>

