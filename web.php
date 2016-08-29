<?php ob_start(); ?>
<?php
	include_once '/home/gaysugar/public_html/twatch/api/LogRequest.php'; 
	include "inc/utils.class.php";

	include_once $_SERVER[ 'DOCUMENT_ROOT' ].'/twatch/api/LogRequest.php';
	twatchLogRequest();
	
	$c=new utils();
	$c->connect();
	
	twatchLogRequest();
	$arr=$c->secrets;
	session_start();
	require_once __DIR__ . '/vendor/autoload.php';
	$fb = new Facebook\Facebook([
	  'app_id' => '340032229516154',
	  'app_secret' => '999445c37d86e1e785e4978864474914',
	  'default_graph_version' => 'v2.4',
	  ]);
	$helper = $fb->getRedirectLoginHelper();
	$permissions = ['public_profile','email','user_birthday']; // Optional permissions
	$loginUrl = $helper->getLoginUrl('http://gaysugardaddyfinder.com/callback.php', $permissions);
	setCookie("admin",'admin',time()+3600,"/");
	  	
	$bgx=rand(1,170);	
	$ext=".jpg";
	$e=rand(0,count($bgx));

	$bg="b$f$ext";

	$display_users_online="none";
	$display_users_inline="none";
	$ht="728px";
	$overflow_y_wrapper="hidden";
	$overflow_y_section="auto";

	if (empty($_COOKIE["lat"])) {
		$x_lat=$lat;
		$x_lng=$lng;
		setCookie("lat", $lat,time()+3600*24, "/");
		setCookie("lng", $lng, time()+3600*24, "/");
	} else {
		$lat=$_COOKIE["lat"];
		$lng=$_COOKIE["lng"];
		$x_lat=$lat;
		$x_lng=$lng;
	}
	//$ip=$c->getIP();
		$show_map='none';
		$show_online='none';
		$track_user=FALSE;

	if ($_REQUEST[page]=='browse') {
		header("Location:home.php?page=browse&".$_SERVER["QUERY_STRING"]);
	}

	$result=$c->query("select id, login,cc_city, cc_state, location, lat, lng, filename_1, gender, age from dt_members where ((abs(round(lat, 4)-33.8895) < 6) and (abs(round(lng, 4) - (-118.3990)) < 6) and filename_1 !='') limit 200");
	$i=1;
	$k=0;
?>	

<!DOCTYPE html>
<html lang="en">
<head>				
	<title>GaySugarDaddyFinder.com :: Gay Sugar Daddy Dating Site</title>		
	<meta name="keywords" content="sugar daddy, gay, gay sugar daddy, sugardaddy, gay sugardaddy, wealthy gay men, twink, hot young gay men, boyz, gay dating, sugar daddy dating site, daddy dating, find daddy, find twink, GaySugarDaddyFinder" />		
	<meta name="description" content="A place that connects handsome wealthy and caring gay men to hot young gay males" />		
	<meta name="Author" content="Gautam Sharma" />		
	<!-- mobile settings -->		 
	<meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=0" />		
	<!-- Fav Icons -->		
	<link rel="icon" href="assets/img/favicon.ico">		

	<link rel="apple-touch-icon" href="assets/img/favicon.ico">		

	<!-- WEB FONTS -->		

	<link href="css/bootstrap.min.css" rel="stylesheet">		
	<!-- Font awesome CSS -->		
	<link href="css/font-awesome.min.css" rel="stylesheet">				
	<!-- Custom CSS -->		
	<link href="assets/css/essentials.css" rel="stylesheet" type="text/css" />		
	<link href="assets/css/layout.css" rel="stylesheet" type="text/css" />		
	<link href="assets/css/layout-responsive.css" rel="stylesheet" type="text/css" />		
	<link href="assets/css/color_scheme/orange.css" rel="stylesheet" type="text/css" />
	<!-- orange: default style -->		
	<link href="assets/css/shadows.css" rel="stylesheet" type="text/css" />
	<!-- orange: default style -->		
	<link href="assets/css/stream.css" rel="stylesheet" type="text/css" />
	<link href="assets/css/jquery-confirm.css" rel="stylesheet" type="text/css" />
	<!-- orange: default style -->		
	<link href="assets2/css/bootstrap.min.css" rel="stylesheet">
	<link href="assets2/css/style.min.css" rel="stylesheet">
	<link href="assets2/css/retina.min.css" rel="stylesheet">
<style>
		video#bgvid {
			position: fixed; right: 0; bottom: 0;
			min-width: 100%; min-height: 100%;
			width: 100%; height: 100%; z-index: -100;
		}
		.bg1 {
			  background:url(assets/images/mask1.png);
			  background-repeat: no-repeat;
			  background-position: center;
			  background-size: cover;
			  z-index:99999999
			}
		.bg2 {
			  background:url(assets/images/mb11.png);
			  background-repeat: no-repeat;
			  background-position: center;
			  background-size: cover;
			  z-index:99999999
			}
		.tooltipsy {
			padding: 10px;
			max-width: 200px;
			color: #0093D9;
			background: url(assets/images/xxx_big.png) center;
			background-size: cover;
			border: none;
			border-radius:4px;
			text-shadow:0px 0px 1px #333;
			opacity:1
		}

		html, body, #map-canvas {
        height: 100%;
        margin: 0px;
        padding: 0px
      }

      #panel {
        position: absolute;
        top: 5px;
        left: 50%;
        margin-left: -180px;
        z-index: 5;
        background-color: #fff;
        padding: 5px;
		border: 1px solid #999;
      }
	  label{
		font-size:18px
	  }
	  
      #markerLayer img {
        border: 0px solid red !important;
        border-radius: 50px;
		opacity:1
      }

    </style>

	</head>
	<body>
	<img style="position:absolute;z-index:9999999999999999;left:0;width:100%" src="assets/images/wide_logo.png">
	<input class="btn btn-xs btn-danger" type="button" value="Login" onclick="location.href='login.php'" style="position:absolute;bottom:50px;right:0px;z-index:999999999999999">
	<input class="btn btn-xs btn-danger" type="button" value="Show Heatmap" onclick="show_heat()" style="position:absolute;bottom:50px;right:50px;z-index:999999999999999">
	<div id="hld" style="position:fixed;z-index:9999;top:0;bottom:0;right:0;height:100%;width:100%;display:block;background:#0093D9;opacity:0.6">		</div>    
	<div id="hld_txt" class="col-md-6" style="display:block;z-index:9999;position:absolute;top:20px;left:5px;bottom:0px;margin:auto;margin-top:0px;width:70%"> 
			<table style="width:100%">
				<tr>
					<td style="text-align:left;font-family:Economica;padding:5px;padding-top:0px;font-size:20px;text-shadow: none;color:#000">
						<h1 style="font-size:20px;color:#fff"><b>WHY US?</b></h1>
						<b>ANON SMS</b><br>Immediate access to users - we use anonymous sms technology for real time communications
						<br><br><b>Speed Browsing</b><br>Browse hundreds of more profiles in the same time as compared to other dating sites
					</td>
				</tr>
			</table>

	</div>
	
<!--
		<img src="assets/images/gay_tab_home.png" style="position:fixed;left:-15px;top:200px;z-index:99999999999999999;opacity:0.8">
		<img src="assets/images/gay_tab_login.png" style="position:fixed;left:-15px;top:250px;z-index:99999999999999999;opacity:0.8">
-->
<!--
				<video style="background:transparent;opacity:0.3;position:absolute;z-index:9;top:0px;left:-400px;height:90%" id="myVideo" autoplay>
					<source id="s1" src="assets/videos/low_01.mp4" type="video/mp4">
				</video>
-->
	<div id="login_table" style="width:100%;display:none;position:fixed">
		<table border=0>
			<tr>
				<td colspan=2>
					<div  id="mini_box" class=""> <!-- alert failed --> 
						<div class="row"> 
							<div class="form-group"> 
								<? if($_GET['err']) { ?>
									<div class="col-md-6 alert alert-danger" style="text-align:left"> 
										<div id="user_input_err_txt">
										<?=$_GET['err'];?>
										</div>
									</div>
								<? } ?>
								<div class="col-md-12" style="text-align:left">
									<label class="" title="">Login|Mobile|Email</label> 
									<input onclick="clear_login_error(this)" type="text"  name="user_input" value="<?=$_SESSION['user_input']?>"  id="user_input" placeholder="Enter any of the above" class="form-control input-box" style="color:grey;padding-left:50px"> <i id='meh' class='fa fa-eye' onmouseover="this.className='fa fa-eye-slash'" onmouseout="this.className='fa fa-eye'" style="font-size:24px;margin-top:-35px;margin-left:10px;color:#CACAD9;position:absolute"></i>
									<div id="user_input_err_txt"></div>
								</div>
							</div>
						</div> 
						<div class="row">
							<div class="form-group"> 
								<div class="col-md-12" style="text-align:left">
									<input onclick="clear_login_error(this)" type="password" name="pswd" id="pswd" placeholder="Password" class="form-control input-box" style="color:grey;padding-left:50px"> <i class='fa fa-signal' style="font-size:24px;margin-top:-35px;margin-left:10px;color:#CACAD9;position:absolute"></i>
									<label class="" title="">Password</label> 
									<div id="pswd_err_txt"></div>
								</div> 
							</div>
						</div> 	
						<div class="row">
							<div class="col-md-6">
							</div>
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<td style="width:50%">
					<button onclick="login_new()" class="btn btn-primary pull-left">
					<span  style="text-align:center;font-size:12px;text-shadow:none">Sign In</span></button>
				</td>  					
				<td style="width:50%">
					<input type="button" name="forgot_button" id="forgot_button" class="btn btn-danger pull-right" onclick="forgot_pass_new()" value="Forgot password">
				</td>
			</tr>
		</table>
	</div>
	<div id="bottom_right" style="position:absolute;z-index:99999">
		<a href="<?=$loginUrl?>"><div style="opacity:0.5;background:#f0f0f0;width:50%;border-radius:0 40px 0 0;padding:5px;height:80px;color:#000;font-size:18px;position:fixed;z-index:99;font-family:Economica;bottom:40px;padding-left:45px;bottom:20px" class=""><span style="">Register using<br><b>FACEBOOK</b></span></div></a>
		<a href="<?=$loginUrl?>"><div id="LoginButton" style="position:fixed;z-index:9999999;bottom:50px" class=""><img src="assets/images/fb_blue.png" style="width:40px;cursor:pointer"></div></a>
		<img onclick="location.href='register.php'" src="assets/boy_sign.png" style="cursor:hand;cursor:pointer;height:100%;opacity:1;top:0px;position:fixed;z-index:99;right:-30px"></div>
		<div id="photo_feed" style="max-height:60px;opacity:1;bottom:-10PX;position:fixed;z-index:9999999;"></div>
	</div>
	
</body>

		<script type="text/javascript" src="assets/js/jquery.min.js"></script>
		<script type="text/javascript" src="assets/js/jquery-ui.min.js"></script>
		<script type="text/javascript" src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>		
		<script type="text/javascript" src="assets/js/framework.js"></script>		
		<script type="text/javascript" src="assets/js/js_tracker.js"></script>		
		<script type="text/javascript" src="assets/js/tooltipsy.min.js"></script>
		<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&libraries=visualization"></script>
		<script src="https://google-maps-utility-library-v3.googlecode.com/svn-history/r391/trunk/markerwithlabel/src/markerwithlabel.js"></script>
<script>
		var j=0
		var login = []
		var gender = []
		var age = []
		var photo = []
		var latLng = []
		var lat = []
		var lng = []
		var marker = []
		var contentString = []
		var infowindow = []
		var lat
		var lng
		var opa1=0, opa2=0,opa3=0,city,state,zip
		var hld=document.getElementById('hld')
		var html=document.documentElement
		var mc=document.createElement("div")
		mc.id="map-canvas"
		mc.style.cssText="overflow:none;width:100%;height:100%;top:0;left:0;right:0;bottom:0;position:fixed;z-index:-1"
		html.appendChild(mc)
		var start_magic=true
		  $('.hastip').tooltipsy({
			alignTo: 'element',
			offset:[-10,0],
			show: 	function (e, $el) {
						$el.slideDown(300); 
					},
			hide: function (e, $el) {
				$el.slideUp(100);
			},
			css:	{   'padding': '10px',
						'max-width': '200px',
						'color': 'orange',
						'background-color': '#f5f5b5',
						'border': '1px solid transparent',
						'-moz-box-shadow': '0 0 10px rgba(0, 0, 0, .7)',
						'-webkit-box-shadow': '0 0 10px rgba(0, 0, 0, .7)',
						'box-shadow': '0 0 10px rgba(0, 0, 0, .7)',
						'text-shadow': 'none',
						'opacity': '0.9',
					},
			});

	function clear_login_error(f) {
		validate = true
		var r = f.id + '_err'
		var t = r + '_txt'
		var p = f.value
		f.style.background = '#fff'
		f.style.color='grey'
		t = document.getElementById(t)
		t.innerHTML = ''
		form_errors = document.getElementById('form_login_errors')
		form_errors.style.display='none'
	}

	$(document).ready(function() {
		$('.hastip').tooltipsy({
			alignTo: 'element',
			offset:[-10,0],
			show: 	function (e, $el) {
					$el.slideDown(300);
				},
			hide: function (e, $el) {
				$el.slideUp(100);
			},
			css:	{   'padding': '10px',
						'max-width': '200px',
						'color': 'orange',
						'background-color': '#f5f5b5',
						'border': '1px solid transparent',
						'-moz-box-shadow': '0 0 10px rgba(0, 0, 0, .7)',
						'-webkit-box-shadow': '0 0 10px rgba(0, 0, 0, .7)',
						'box-shadow': '0 0 10px rgba(0, 0, 0, .7)',
						'text-shadow': 'none',
						'opacity': '0.9',
					},
				});
			});

	// Adding 100 Data Points
<?
	foreach ($result as $res) {
		$k++;
		$app = ($i<200) ? "," : "";
		$coords .= "new google.maps.LatLng(".$res['lat'].",".$res['lng'].")".$app;
?>
		login[j] = "<?=strtoupper($res['login']);?>"
		lat[j] = "<?=$res['lat'];?>"
		lng[j] = "<?=$res['lng'];?>"
		photo[j] = "http://gaysugardaddyfinder.com/photos/<?=$res['filename_1'];?>"
		gender[j] = "<?=$res['gender'];?>"
		age[j] = "<?=$res['age'];?>"
		location[j] = "<?=$res['cc_city'] . ', ' . $res['cc_state'];?>"
		j++ 
<?
		$i++;
	}
?>
	setTimeout('get_new_members()',10)
	var red_border="5px solid red"
	var none="none"
	var map, pointarray, heatmap;
	var userData = [<?=$coords;?>];
	console.log(userData);
	var myLatlng = new google.maps.LatLng(getCookie('lat'),getCookie('lng'))
	var MY_MAPTYPE_ID = 'custom_style';
	function initialize() {
		var mapOptions = {
			zoom: 7,
			center: myLatlng,
			mapTypeId: google.maps.MapTypeId.satellite
		};
		var featureOpts = [
			{
			  stylers: [
				{ gamma: 1 },
				{ weight: 0 }
			  ]
			},
			{
			  featureType: 'water', 
			  stylers: [
				{ color: '#890000' }
			  ]
			}
		];
			map = new google.maps.Map(document.getElementById('map-canvas'),
			mapOptions);
			var pointArray = new google.maps.MVCArray(userData);
			var styledMapOptions = {
			name: 'Custom Style'
		};
			var customMapType = new google.maps.StyledMapType(featureOpts, styledMapOptions);
			heatmap = new google.maps.visualization.HeatmapLayer({
			data: pointArray
		});

		var mult=-1
		for ( k=0; k <= userData.length; k++ ) {
			mult=(mult<0)?1:-1
			var degRotate=0;
			var pxTop = Math.round(Math.random()*1000, 0)
			var pxRight = Math.round(Math.random()*500, 0)
			var degRotate = Math.round(Math.random()*15, 0) * mult
			var hld=document.getElementById('hld')
/*			var elm=document.createElement('div')
			elm.innerHTML="<img src='" + photo[k] + "' style='cursor:pointer;cursor:hand;opacity:0.8;background:transparent;width:75px;box-shadow:0 0 50px #333;border:5px solid white;' onmouseover='this.style.opacity=1;this.style.zIndex=999999;this.style.border=red_border'  onclick='this.style.display=none'>"
			elm.style.cssText="position:absolute;z-index:900;top:" + pxTop + "px;right:" + pxRight + "px;-webkit-transform:rotate(" + degRotate + "deg);transform:rotate(-" + degRotate + "deg)"
			hld.appendChild(elm);
*/	
				var myLatLng = new google.maps.LatLng(lat[k], lng[k]);
				var marker = new MarkerWithLabel({
				position: myLatLng,
				map: map,
				class: 'hastip',
				title: 'NAME: ' + login[k] + '\r\nI AM A: ' + gender[k] + '\r\nAGE: ' + age[k] + '\r\nFROM: ' + location[k],
				zIndex: k+100,
				icon: { url:photo[k], scaledSize: new google.maps.Size(20, 20)},
				optimized:false
			});

			var myoverlay = new google.maps.OverlayView();
			myoverlay.draw = function () {
				this.getPanes().markerLayer.id='markerLayer';
			};
			
			myoverlay.setMap(map);
			 
			infowindow = new google.maps.InfoWindow({
				content: "<div class='hastip' style='background:red'>" + age[k] + ' ' + gender[k] + ' ' + login[k] + "</div>"
			});

	  }
	
		map.set('styles', [
  		   {
		  featureType: "all",
		  elementType: "all",
		  stylers: [
			{ saturation: -100 } // <-- THIS
		  ]}
		]);	
	  heatmap.setMap(map);
	  heatmap.set('radius',50)
	}

	function toggleHeatmap() {
		heatmap.setMap(heatmap.getMap() ? null : map);
	}

	function show_heat() {
		document.getElementById('hld').style.display='none'
		document.getElementById('hld_txt').style.display='none'
	}

	function changeGradient() {
	  var gradient = [
		'rgba(0, 255, 255, 1)',
		'rgba(0, 255, 255, 1)',
		'rgba(0, 191, 255, 1)',
		'rgba(0, 127, 255, 1)',
		'rgba(0, 63, 255, 1)',
		'rgba(0, 0, 255, 1)',
		'rgba(0, 0, 223, 1)',
		'rgba(0, 0, 191, 1)',
		'rgba(0, 0, 159, 1)',
		'rgba(0, 0, 127, 1)',
		'rgba(63, 0, 91, 1)',
		'rgba(127, 0, 63, 1)',
		'rgba(191, 0, 31, 1)',
		'rgba(255, 0, 0, 1)'
	  ]
		heatmap.set('gradient', heatmap.get('gradient') ? null : gradient);
	}
	
	function changeRadius() {
	  heatmap.set('radius', heatmap.get('radius') ? null : 20);
	}

	function changeOpacity() {
	  heatmap.set('opacity', heatmap.get('opacity') ? null : 1);
	}
	mc.style.zIndex='-1'
	mc.style.position='absolute'
	google.maps.event.addDomListener(window, 'load', initialize);
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


	
  WebFontConfig = {
    google: { families: ['Open+Sans+Condensed:300:latin', 'Economica::latin' ] }
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
		function window_height() {
			if (document.body) {
			 winH = document.body.offsetHeight;
			}

			if (document.compatMode=='CSS1Compat' &&
				document.documentElement &&
				document.documentElement.offsetHeight ) {
				winH = document.documentElement.offsetHeight;
				return winH
			}

			if (window.innerHeight && window.innerHeight) {
				 winH = window.innerHeight;
				 return winH;
			}
		}		

	
var wh=screen.height

var playing=false
function play_me() {
		document.getElementById("s1").src = "assets/videos/low_01.mp4";
		document.getElementById("myVideo").load();
		document.getElementById("myVideo").loop="true"
		document.getElementById("pme").style.display='none'
		document.getElementById("btn_panel").style.display='block'
 }
  function toggle_me(objImg) {
					if (objImg.style.zIndex=='0') objImg.style.zIndex='101'
						else objImg.style.zIndex='0'
				}
			
				function forgot_pass_new() {
					document.getElementById('modal').style.display='block'
					document.getElementById('modal_window').style.display='block'
					document.getElementById('modal').style.zIndex='999'
					var fs=document.getElementById('forgot_send')
					document.getElementById('modal_window').appendChild(fs)
				}
				setTimeout('get_new_members()',10)
				function get_new_members() {
					var ft=document.getElementById('photo_feed')
					var url = 'inc/x_get_photo_feed.php'
					var request = $.ajax({
					url: url,
					type: "GET",
					dataType:'html',
					cache: false,
					success: function(msg) {
							ft.innerHTML=msg
						}
					})
				}
				
				function send_pass_new() {
					var ft=document.getElementById('forgot_txt')
					var e = '<span style="color:orange">' + ft.value + '</span> not found in our database'
					var url = 'inc/x_validate_forgot.php?i=' + ft.value
					console.log(url)
					var request = $.ajax({
					url: url,
					type: "GET",
					dataType:'html',
					cache: false,
					success: function(msg) {
							if(!msg.trim()) {
								document.getElementById('forgot_success_txt').style.display='none'
								document.getElementById('forgot_err_txt').style.display='block'
								document.getElementById('forgot_err_txt').innerHTML='<span style="color:orange">' + msg
							} else {
								document.getElementById('forgot_err_txt').style.display='none'
								document.getElementById('forgot_success_txt').style.display='block'
								document.getElementById('forgot_success_txt').innerHTML=msg+'<br><input type="button" value="Close Me" onclick="close_this()"><br>'
							}
						}
					})
				}
				
				function close_this() {
					document.getElementById('modal').style.display='none'
					document.getElementById('modal_window').style.display='none'
				}

  function toggle_me(objImg) {
					if (objImg.style.zIndex=='0') objImg.style.zIndex='101'
						else objImg.style.zIndex='0'
				}
			
				function forgot_pass_new() {
					document.getElementById('modal').style.display='block'
					document.getElementById('modal_window').style.display='block'
					document.getElementById('modal').style.zIndex='999'
					var fs=document.getElementById('forgot_send')
					document.getElementById('modal_window').appendChild(fs)
				}

				function get_new_members() {
					var ft=document.getElementById('photo_feed')
					var url = 'inc/x_get_photo_feed.php'
					var request = $.ajax({
					url: url,
					type: "GET",
					dataType:'html',
					cache: false,
					success: function(msg) {
							ft.innerHTML=msg
						}
					})
				}
				
				function send_pass_new() {
					var ft=document.getElementById('forgot_txt')
					var e = '<span style="color:orange">' + ft.value + '</span> not found in our database'
					var url = 'inc/x_validate_forgot.php?i=' + ft.value
					console.log(url)
					var request = $.ajax({
					url: url,
					type: "GET",
					dataType:'html',
					cache: false,
					success: function(msg) {
							if(!msg.trim()) {
								document.getElementById('forgot_success_txt').style.display='none'
								document.getElementById('forgot_err_txt').style.display='block'
								document.getElementById('forgot_err_txt').innerHTML='<span style="color:orange">' + msg
							} else {
								document.getElementById('forgot_err_txt').style.display='none'
								document.getElementById('forgot_success_txt').style.display='block'
								document.getElementById('forgot_success_txt').innerHTML=msg+'<br><input type="button" value="Close Me" onclick="close_this()"><br>'
							}
						}
					})
				}
				
				function close_this() {
					document.getElementById('modal').style.display='none'
					document.getElementById('modal_window').style.display='none'
				}
</script>

</html>