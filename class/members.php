<?
	session_start();
	if (isset($_SESSION['member'])) {
		$member = $_SESSION['member'];
		$name=$member['name'];
		$email=$member['email'];
		$id=$member['id'];
		$login=$member['login'];
		$photo=$member['photo'];
	} else {
	//	header("Location:http://smsgone.com");
	}
	include "class/utils.class.php";
	include "class/sms.class.php";
	$u = new utils();
	$s = new sms();
	$u->connect();
	$cv="height:94px; width:92px;right:263px;top:176px;";
	$c=$u;
	$s->connect();
	echo $s->getCallerID('13105676686');
	
	
	$pad="70px";
	$is = $u->is_mobile();
	if ($is) {
		$pad="0px";
		$cv="height:90px; width:86px;right:238px;top:175px;";
	}
	$b='b' . rand(1,155) . '.jpg';
?>
<!DOCTYPE html>
<html lang="en">
<head>
       <meta charset="utf-8">
        <title>smsgone.com - The Safest way to communicate on planet Earth.</title>
        <!-- header include -->
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta name="apple-mobile-web-app-status-bar-style" content="black" />
		<link href='https://fonts.googleapis.com/css?family=Raleway|Poiret+One|Open+Sans+Condensed:300|Kristi|Waiting+for+the+Sunrise' rel='stylesheet' type='text/css'>
		<link rel="shortcut icon" href="../Heath/flat-ui/images/favicon.ico">
		<link rel="stylesheet" href="../Heath/flat-ui/bootstrap/css/bootstrap.css">
		<link rel="stylesheet" href="../Heath/flat-ui/bootstrap/css/bootstrap-responsive.css">
		<style>
			.eInput {
				text-align:left; font-size:24px;font-family:Waiting for the sunrise;width: 100%; background: rgb(255, 255, 255); height:30px; border:none; border-radius:5px;padding:10px
			}
			.eLabel {
				font-family:Poiret One;font-size:18px;color:#333; margin-left: 15px; font-family:Poiret One;font-size:24px; margin-left: 15px;
			}
		</style>
		<link href="http://smsgone.com/css/stream.css" rel="stylesheet" type="text/css" />
		<script src="../js/jquery.min.js"></script>
		<script src="../g/aes.js"></script>
        <script src="../js/bootstrap.min.js"></script>
        <script src="../js/fastclick.js"></script>
	<script>
		function getLC() {
			if (!getCookie('mobile')) {
				document.getElementById('darks').style.display='block'
				document.getElementById('mobile_box').style.display='block'
				document.getElementById('mobile_box2').style.display='block'
			} else {
				if (!getCookie('long_code')) {
					LC(getCookie('mobile'))
				} else {
					goENC()
				}
			}
		}
		
		function checkM() {
			var m=document.getElementById('mobile').value
			if (!m) {
				alert('In order to set you up with anonymous text messaging capability, we will need your mobile number!')
				return false
			} else {
				LC(m)
			}
		}
		
		function goENC() {
			location.href='g/x_photos.php'
		}
		
		function LC(m) {
			var url = "http://smsgone.com/g/x_getLC.php?m=" + m
			var request = $.ajax({
			   type: 'GET',
				url: url,
			    success: function(msg) {
					document.getElementById('darks').style.display='none'
					document.getElementById('mobile_box').style.display='none'
					document.getElementById('mobile_box2').style.display='none'
					var long_code=msg.split('|')[0]
					m=msg.split('|')[1]
					setCookie('long_code', long_code)
					setCookie('mobile', m)
					document.getElementById('long_code').textContent=msg.split('|')[2]
					document.getElementById('table_inner').style.display='none'
					document.getElementById('prev').style.display='none'
					document.getElementById('next').style.display='block'
					document.getElementById('anon').style.display='block'
					document.getElementById('divtable').style.height='170px'
					document.getElementById('divtable').style.marginTop='10px'
					document.getElementById('divtable').innerHTML='<span style="font-size:16px">Your new phone number is <font color=orange>' + msg.split('|')[2] + '</font>. This is the number that others will see as your number when they receive a message from you</span>'
					document.getElementById('divtable').innerHTML += '<br><br><span style="font-size:16px">Click Next below to continue to the next page to send a completely <font color=orange>anonymous and encrypted</font> sms that can never be traced. Or intercepted. Guaranteed. And we stand by our guarantee with $$$.</span>'
				}
			})				
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
	<body style="overflow: none;width:100%;font-family:Open Sans Condensed;">
		<div style="background:url(images/backgrounds/<?=$b;?>) center center;background-size:cover;margin:auto;display:;position:absolute;z-index:99999;left:0px;right: 0px; top: 0px; bottom: 0px; width: 100%; height: 100%; opacity: 1;" id="tint"></div>
		<div style="margin:auto;display:none;position:absolute;z-index:999999999;left:0px;right: 0px; top: 0px; bottom: 0px; width: 100%; height: 100%; opacity: 0.8; background: #000" id="darks"></div>
		<img src="http://smsgone.com/images/bar.gif" style="margin:auto;display:none;position:absolute;z-index:999999999;left:0;right:0;top:0;bottom:0;margin:auto" id="gears">
		
		
		
		<div style="font-size:18px;font-family:Open Sans Condensed;PADDING-BOTTOM:50PX;PADDING-LEFT:120PX;width:320px;height:0px;background:;text-shadow:none;margin:auto;display:none;position:absolute;z-index:9999999999;left:0;right:0;top:0;bottom:0;margin:auto;COLOR:RED" id="mobile_box">YOUR MOBILE NUMBER<BR><BR>
		</div>
		<div style="width:150px;height:15px;background:;text-shadow:none;margin:auto;display:none;position:absolute;z-index:99999999999;left:0;right:0;top:0;bottom:0;margin:auto;padding-right:<?=$pad;?>" id="mobile_box2">
			<span><input type=text style="width:100px;box-shadow:0 0 20px rgba(255,255,255,0.3);border-radius:2px" id="mobile"></span><span><img src="images/go.png" style="width:30px;cursor:pointer;margin-top:-70px;margin-left:160px" onclick="javascript:checkM()"></span>
		</div>

		<link href="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css" rel="stylesheet" type="text/css" />
		<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
		<script src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
	</head>
	<div id='container' class="container" align=center style="font-family:Open Sans Condensed;font-size:18px;color:#dfdfd0;z-index:9999999;padding:10px;padding-top:0;border-radius:0 0 5px 5px;max-width:320px;margin:auto;left:0;right:0;position:absolute;top:0px;background:;height:300px;">
		<div class="row" align=left>
			<table style="width:100%;border-radius:0 0 5px 5px;margin:0;padding:0;background:url(http://smsgone.com/images/backgrounds/bx07.png) center center;background-size:cover">
				<tr>
					<td>
						<img src="g/logo.png" style="margin-top:0px;margin-bottom:20px;width:50px">
					</td>
					<td>
						<table style="background:none">
							<tr>
								<td align=left>
									<span style="color:orange; font-family:Poiret One;font-size:28px;color:#000;margin-left: 10px;text-shadow: 0px 0px 10px #000;">
										Encrypted Messaging
									</span>
								</td>
								</tr>
							<tr>							
								<td align=left>
									<span style="margin-left:15px;font-family:Kristi;font-size:24px;text-shadow: 0px 0px 10px #000;">
										Guaranteed unbreakable.$1000.
									</span>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<div id="main" style="background:url(images/b06.jpg) no-repeat center;background-size:cover;text-shadow:none;border-radius:5px;padding:10px;margin-top:100px;text-align:center">
			<div style="margin-left:-10px;border-radius:4px;padding:10px;width:300px;background:url(images/b140.jpg) center no-repeat;background-size:cover;opacity:0.8;margin-top:-50px;position:absolute;display:none" id="anon"><span style="font-size:24px;color:#000;text-shadow:0 0 1px #fff">MY ANONYMOUS#</span> <span style="" id="login"><span style="font-size:24px;color:#000;text-shadow:0 0 1px #fff" id="long_code"></span></div>
				<table style="background:none;width:100%;font-size:24px">
					<tr>
						<td align=left>
							<img style="margin-right:40px;width:75px;border-radius:4px;border:4px solid white" src="<?=$_SESSION['member']['photo'];?>">
						</td>
						<td align=left style="padding-left:20px">
							<span style="font-size:18px">Welcome, <?=$_SESSION['member']['name'];?></span><br>
							<span style="font-size:16px;color:orange">LOGIN</span> <span style="font-size:16px;color:#fff" id="login"><?=$_SESSION['member']['login'];?></span>
							<span style="font-size:16px;color:orange">EMAIL</span> <span style="font-size:16px;color:#fff" id="login"><?=$_SESSION['member']['email'];?></span>
							<span style="font-size:16px;color:orange">MOBILE NUMBER</span> <span style="font-size:16px;color:#fff" id="login"><?=$u->format_sms($_COOKIE['mobile']);?></span>
							<!--<span><img style="border-radius:4px;box-shadow:0 0 25px rgba(255,255,255,0.5);position:absolute;opacity:0.6;height:90px; width:86px;right:238px;top:175px;z-index:999999"src="g/panel.png"></span>-->
							<span><img style="border-radius:4px;box-shadow:0 0 25px rgba(255,255,255,0.5);position:absolute;opacity:0.6;<?=$cv;?>z-index:999999"src="g/panel.png"></span>
						</td>
					</tr>
					<tr>
						<td colspan=2>
							<div id="table_inner"><div placeholder="My Mobile Number" contentEditable id="mobile"></div></div>
							<div id="divtable"></div>
						</td>
					</tr>
					<tr>
						<td colspan=2 align=right>
							<div id='prev' onclick="javascript:getLC()" style="cursor:pointer;font-size:16px;display:block;margin:10px;color:#000;background:url(images/b23.png) no-repeat center;padding:5px;border-radius:5px;padding:5px;text-align:center;width:50px">
								NEXT
							</div>
							<div id='next' onclick="javascript:goENC()" style="cursor:pointer;font-size:16px;display:none;margin:10px;color:#000;background:url(images/b16.png) no-repeat center;padding:5px;border-radius:5px;padding:5px;text-align:center;width:50px;background-size:cover">
								NEXT
							</div>
						</td>
					</tr>
				</table>
			</div>			
		</div>
	</div>

		<a href='<?=$loginUrl;?>'>
		<img style="width:150px;top:0;bottom:0;left:0;right:0;margin:auto;position:absolute" src="face.png" style="width:100px"></a>
	</body>
</html>
