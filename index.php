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
		<link href="assets/css/bootstrap.min.css" rel="stylesheet">		
		<link href="assets/css/layout-responsive.css" rel="stylesheet" type="text/css" />		
		<link href="assets/css/stream.css" rel="stylesheet" type="text/css" />
		<link href="assets/css/bootstrap.min.css" rel="stylesheet">
		<link href="assets/css/jquery-confirm.css" rel="stylesheet" type="text/css" />		
		<link href="assets/css/tooltipster.css" rel="stylesheet" type="text/css" />		
		<link href="assets/css/layout-responsive.css" rel="stylesheet" type="text/css" />		
		<link href="assets/css/dropzones.css" rel="stylesheet" type="text/css" />		
		<link href="assets/css/dropzone.css" rel="stylesheet" type="text/css" />		
		<link href="assets/css/filetypes.css" rel="stylesheet" type="text/css" />		
		<link href="http://gaysugardaddyfinder.com/assets/css/style-20.css" rel="stylesheet">	
		<link href="http://gaysugardaddyfinder.com/assets2/css/retina.min.css" rel="stylesheet">
		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
		<link href='https://fonts.googleapis.com/css?family=Economica|Wire+One|Averia+Sans+Libre|Flavors' rel='stylesheet' type='text/css'>
		<style>
				@import url(https://fonts.googleapis.com/css?family=Economica);
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
		</style>

		<script>
	var err_img = "assets/avatars/10.png" 
	var err_img1 = 'assets/images/tie.jpg'
	</script>
	<!-- Latest compiled and minified JavaScript -->
	
			<script type="text/javascript" src="assets/js/jquery.js"></script>
			<script type="text/javascript" src="assets/js/jquery-ui.min.js"></script>
			<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js" integrity="sha512-K1qjQ+NcF2TYO/eI3M6v8EiNYZfA95pQumfvcVrTHtwQVDG+aHRqLi/ETn2uB+1JqwYqVG3LIvdm9lj6imS/pQ==" crossorigin="anonymous"></script>
			<script src="assets/js/jquery-confirm.min.js"></script>
			<script type="text/javascript" src="assets/js/framework.js"></script>		
			<script type="text/javascript" src="assets/js/tooltipsy.min.js"></script>

	<script src="assets/js/dropzone.min.js"></script>	 
	<body onload="init()" style="background:url(../assets/img/b152.jpg) center center no-repeat;background-size:100% 100%">
		<div style="position:absolute;width:100%;height:100%;top:0px;left:0px;right:0px;bottom:0px;background:#000;opacity:0.9;z-index:9999100000;display:none;overflow-y:auto" id="pay_modal"></div>	
		<div style="display:none;opacity:1;color:#000;box-shadow: 10px 10px 50px #000;display:;margin:auto;position:absolute;left:0;right:0;color:#000;text-shadow:1px 1px 1px #fff;top:100px;z-index:9999100001;padding:10px;width:280px;height:140px;border-radius:6px;background:#f0f0f0" id="txt_info">		
			<img onclick="this.style.display='none';document.all.pay_modal.style.display='none';document.all.txt_info.style.display='none'" src="assets/img/close.png" style="cursor:hand;cursor:pointer;position:absolute;top:5px;right:5px;width:25px">
			<br><div style="color:#00BFFF;z-index:9999999999999999;font-family:Economica;font-size:24px" id="mobile_box">Mobile Number</div>
			<span><input type=text style="height:35px;width:200px;box-shadow:0 0 20px rgba(255,255,255,0.3);border-radius:4px;border:1px solid skyblue;padding-left:10px;font-family:Economica;font-size:32px" id="mobile_no"><img src="assets/img/next1.png" style="position:absolute;width:40px;cursor:pointer;margin-left:10px" onclick="javascript:checkM()"></span>
		</div>
				<div id="userso" style="display:none;right:0;margin:auto;top:10px;z-index:99999999999999;background:#333;box-shadow:0 0 20px #000;width:300px;height:40px;position:absolute;border-radius:4px;font-family:Open Sans Condensed"></div>
			<div id="usersOnline" style="display:none;position:absolute;top:0px;bottom:0px;right:-10px;z-index:99999999999;overflow-y:auto;overflow-x:hidden;width:320px;height:100%;"></div>
			<div class="modal" id="modal" onclick="this.style.display='none'" style="position:absolute;display:none;cursor:hand;cursor:pointer" onmouseover="clear_all_timers()">
				<div class="modal_bg" id="modal_bg"></div>
				<div class="modal_window" id="modal_window" style="display:block;background:none;box-shadow:none;top:0"></div>
			</div>
<script>

<? if ($_GET['show_send']==1) { ?>
	setTimeout('show_send()',10)
<? } ?>

	var editMe=false
	function init() {
		delCookie("editImg")
		//set_session(getCookie('mobile'),"")
	}
	function getMobile() {
		document.getElementById('pay_modal').style.display='block'
		document.getElementById('txt_info').style.display='block'
		document.getElementById('sms_div').style.display='none'
		document.getElementById('sms_div1').style.display='none'
		document.getElementById('mobile_box2').style.display='block'
	}
	
	function checkM() {
			var m=document.getElementById('mobile_no').value
				setCookie('mobile',m)
				alert(getCookie('mobile'))
			if (m=="") {
				alert('In order to set you up with anonymous text messaging capability, we will need your mobile number!')
				return false
			} else {
				document.getElementById('pay_modal').style.display='none'
				document.getElementById('txt_info').style.display='none'
				document.getElementById('sms_div').style.display='block'
				document.getElementById('sms_div1').style.display='block'
				document.getElementById('mobile_box2').style.display='none'
			}
	}
</script>
<?
echo '<div id=\'sms_div\' class="col-md-4" style="width:100%">
		<div id=\'sms_div1\' class="col-md-4 www_box2" style=\'opacity:1;margin-top:-15px;padding:10px;display:none;position:absolute;z-index:999999999999999;font-family:Economica;font-size:18px;background:url(../assets/img/box_08b.png);margin:auto;left:0;right:0;width:300px;height:520px\'>
			<div style=\'display:none;margin-top:0px\' id=\'sms_label\'>
				<table style=\'margin-top:-5px;font-family:Economica;text-align:left;margin-left:20px;width:100%;font-size:24px\'>
					<tr>
						<td style=\'font-size:24px\'>
							Enter the Mobile Number<br><span><input id="user_input" type=text placeholder=\'Enter Recepient Mobile \' style=\'margin-top:10px;background:#fff;border:1px solid skyblue;font-family:Economica;padding-left:5px;font-size:22px;background:#fff;width:250px;height:35px;border-radius:4px;border:1px solid skyblue\'></span>
						</td>
					</tr>
					<tr>
						<td style=\'font-size:22px\'>
							<br>
						</td>
					</tr>
					<tr>
						<td style=\'font-size:24px\'>
							<table WIDTH=300PX>
								<tr>
									<td colspan=3 style=\'font-size:24px\'>
										Hide Image/Video after:<br>
									</td>
								</tr>
								<tr>
									<td WIDTH=33%>
										<span><input id="user_time_hours" type=text placeholder=\'Seconds to display image for \' style=\'margin-top:10px;background:#fff;border:1px solid skyblue;font-family:Economica;padding-left:5px;font-size:18px;background:#fff;padding-left:5px;font-size:18px;background:#fff;width:50px;height:35px;border-radius:4px;border:1px solid skyblue\' value=\'0\'><BR>HOURS</span>
									</td>
									<td WIDTH=33%>
										<span><input id="user_time_minutes" type=text placeholder=\'Seconds to display image for \' style=\'margin-top:10px;background:#fff;border:1px solid skyblue;font-family:Economica;padding-left:5px;font-size:18px;background:#fff;padding-left:5px;font-size:18px;background:#fff;width:50px;height:35px;border-radius:4px;border:1px solid skyblue\' value=\'1\'><BR>MINUTES</span>
									</td>
									<td WIDTH=33%>
										<span><input id="user_time_seconds" type=text placeholder=\'Seconds to display image for \' style=\'margin-top:10px;background:#fff;border:1px solid skyblue;font-family:Economica;padding-left:5px;font-size:18px;background:#fff;padding-left:5px;font-size:18px;background:#fff;width:50px;height:35px;border-radius:4px;border:1px solid skyblue\' value=\'30\'><BR>SECONDS</span>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td>
							<br>
						</td>
					</tr>
					<tr>
						<td>
							<span><img src=\'assets/img/x01.png\' id=\'edit_img\' style=\'cursor:hand;cursor:pointer;width:30px\' onclick=\'toggle_edit()\'></span><span> EDIT BEFORE SENDING</span>
						</td>
					</tr>
				</table>
			</div>
			
			<img id=\'prog_bar_huns\' style=\'position:absolute;display:none;width:25px;left:150px;top:290px;opacity:1\' src=\'assets/digits/0.png\'>
			<img id=\'prog_bar_tens\' style=\'position:absolute;display:none;width:25px;left:175px;top:290px;opacity:1\' src=\'assets/digits/0.png\'>
			<img id=\'prog_bar_units\' style=\'position:absolute;display:none;width:25px;left:200px;top:290px;opacity:1\' src=\'assets/digits/0.png\'>
			
			<div id=\'browse_icon\' style=\'text-align:center;display:;margin-top:5px;\'>
			<table style=\'text-align:left;margin:0;width:100%;margin-left:20px\'>
					<tr>
						<td width=100% align=left>
							<div onclick=\'toggle_photo()\' id=\'triggerDrop\' style=\'left:15px;padding-left:5px;width:245px;height:150px;background:#fff;border:1px dashed red;font-size:16px;position:absolute;z-index:9999999999999;border-radius:5px;margin-left:5px\'><br><br>CLICK HERE TO BROWSE FOR A SELF DESTRUCTING PHOTO TO SEND VIA SMS</div>
							<form id=\'myDropZoneForm\' name=\'myDropZoneForm\' class=\'dropzone-previews needsclick dz-clickable\' align=left style=\'display:none;width:275px;height:50px;background:#fff;border:1px dashed red;font-size:16px;margin-left:-100px;padding-left:-75px\'>
							</form>
						</td>
					</tr>
				</table>
				<table id="cancel" style="margin-top:110px">
					<tr>
						<td>
							<input type=button class=\'btn btn-xs btn-info\' style=\'position:absolute;bottom:10px;left:20px;text-shadow:none\' value=\'Main Menu\' onclick=\'cancel_sms()\'>
						</td>
					</tr>
				</table>
			</div>
		</div>
			<table id=\'sub_header\' class=\'www_box2\' align=center style="background:;width:300px">
				<tr>
					<td colspan=2 style="width:100%;height:40px;border-radius:30px;background:;margin:10px">
						<span style="color:#333;font-size:18px;font-family:Averia Sans Libre">Anything</span>
						<span style="color:skyblue;font-size:22px;font-family:Averia Sans Libre">Any Size</span>
						<span class=\'btn btn-inverse\' style="background:#000;color:#fff;font-size:16px;opacity:0.3;font-family:Averia Sans Libre">Anonymously</span>
					</td>
				</tr>
			</table>
			<br>
			<table class=\'www_box2\' id=\'sms_table\' align=center style="opacity:1;border-radius:5px;background:url(../assets/img/box_03.png);width:300px;padding-bottom:30px">
				<tr>
					<td align=center colspan=2 style="width:50%;border-radius:30px;background:;">
						<a href=\'javascript:show_send()\'><div id=\'sms_icon\'><img src =\'http://gaysugardaddyfinder.com/assets/images/i12g.png\'></div>
						<div style="color:#000;font-size:22px;font-family:Economica;color:Orange">Send a large Video or Photo Now!*
					</td>
				</tr>
				<tr>
					<td colspan=2 align=center style="width:50%;border-radius:30px;background:;">
						<a href="'.$loginUrl.'"><div><img src =\'http://smsgone.com/face.png\' style=\'width:40px\'></div>
						<div style="color:#000;font-size:18px;font-family:Economica">LOGIN or REGISTER</a></div>
					</td>
				</tr>
				<tr>
					<td colspan=2 align=center style="width:50%;border-radius:30px;background:;">
						<br>
					</td>
				</tr>
			</table>
			<br>
			<table class=\'www_box2 www_box2\' id=\'sms_table2\' align=center style="background:url(../assets/img/box_05c.png);border-radius:5px;width:300px;padding-bottom:30px">
				<tr>
					<td align=center colspan=2 style="width:50%;border-radius:30px;background:;">
						<div id=\'sms_icon\' onclick=\'show_send()\'><img src =\'http://gaysugardaddyfinder.com/assets/images/i19g.png\'></div>
						<div style="color:#000;font-size:18px;font-family:Economica">What we do
					</td>
				</tr>
				<tr>
					<td colspan=2 align=center style="width:50%;border-radius:30px;background:;">
						<div><img src =\'http://gaysugardaddyfinder.com/assets/images/i17g.png\'></div>
						<div style="color:#000;font-size:18px;font-family:Economica">API</div>
					</td>
				</tr>
				<tr>
					<td colspan=2 align=center style="width:50%;border-radius:30px;background:;">
						<div><img src =\'http://gaysugardaddyfinder.com/assets/images/i09g.png\'></div>
						<div style="color:#000;font-size:18px;font-family:Economica">FAQs Help & Contact</div>
					</td>
				</tr>
				<tr>
					<td colspan=2 align=center style="width:50%;border-radius:30px;background:;">
						<br>
					</td>
				</tr>
			</table>
			</div>
		</div>
	</div>
</div>
';
?>
<script>
		var editImg
		function toggle_edit() {
			if (document.getElementById('edit_img').src=="http://txt.am/assets/img/x01.png") {
				document.getElementById('edit_img').src="http://txt.am/assets/img/x04.png"
				editImg=true
				setCookie('editImg',"edit")
				if (!getCookie('mobile')) getMobile()
				if (getCookie('mobile')) set_session(getCookie('mobile'),"edit")
			} else {
				document.getElementById('edit_img').src="http://txt.am/assets/img/x01.png"	
				editImg=false
				delCookie('editImg')
				set_session(getCookie('mobile'),"")
			}
		}
		
		function jalert(txt,type,modal) {
			if (!type) type='dark'
			if (!modal) modal=0
			if (modal && !type) type='dark'
			var obj3=$.confirm({
				title: false, // hides the title.
				closeIcon: false,
				theme: type,
				columnClass: 'col-md-6 col-md-offset-3',
				confirmButtonClass: 'btn-info',
				cancelButtonClass: 'btn-danger',
				confirm: function(){
					setCookie('k','yes')
					return true
				},
				cancel: function(){
					delCookie('k')
					return false
				},				
				});
				obj3.setContent(txt)
				obj3.setDialogCenter()
		}
		
		function jconfirm2(txt,type,modal) {
			if (!type) type='hololight'
			if (!modal) modal=1
			if (modal && !type) type='hololight'
			var obj1=$.confirm({
				title: false, // hides the title.
				cancelButton: true, // hides the cancel button.
				confirmButton: true, // hides the confirm button.
				closeIcon: true,
				theme: 'light',
				columnClass: 'col-md-6',
				confirm: function(){
					return true
				},
				cancel: function(){
					return false
				},
				confirmButtonClass: 'btn-info',
				cancelButtonClass: 'btn-danger'				
			});
				obj1.setContent(txt)
				obj1.setDialogCenter()
		}
	

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
	
		function set_default(my_mid, my_pic) {
			var url = 'inc/x_default.php?mid=' + my_mid + '&f=' + my_pic
			jwait('Setting Default...')
			var request = $.ajax({
				url: url,
				type: "GET",
				dataType: "html",
				cache: false,
				success: function(msg) {
					document.getElementById('jModalWn').style.display=='none'
					document.getElementById('objW').style.display=='none'
					document.getElementById('objT').style.display=='none'
					jalert('Photo set as Default','Set',"location.href='page.php?page=edit_album'")
				}
			})		
		}

	function view_photo(m_id, p_id) {
		location.href='page.php?page=view_photo&member_id=' + m_id + '&id=' + p_id
	}
		var nh=25
		var i=0
		var html=document.documentElement
		var nd=[]
		var nt=[]
		
		function ntfy(str,xCol) {
			if(!xCol) xCol='#333'
			nd[i]=document.createElement("div")
			nt[i]=document.createElement("div")
			nd[i].className="notify"
			nd[i].style.background=xCol
			nd[i].style.top=nh+'px'
			nd[i].style.display='block'
			nd[i].innerHTML='<table cellspacing=5 cellpadding=5><tr><td><img src="assets/check.png" style="width:25px;margin:"></td><td><div id="notify_txt" style="font-size:18px;color:#0093D9;margin:5px">'+str+'</div></td></tr></table>'
			nd[i].appendChild(nt[i])
			html.appendChild(nd[i])
			setTimeout('hide_ntfy('+i+')',5000)
			nh=nh+50
			i++
		}

		function hide_ntfy(i) {
			nh=0
			nd[i].style.display='none'
			nt[i].innerHTML=''
		}

		my_mid=getCookie('mid')

	function show_send() {
		if (document.getElementById('u_msg')) document.getElementById('u_msg').style.display='block'
		document.getElementById('sms_table').style.display='none'
		document.getElementById('sms_table2').style.display='none'
		document.getElementById('browse_icon').style.display='block'
		document.getElementById('sms_div1').style.display='block'
		document.getElementById('sms_label').style.display='block'
		
		document.getElementById('sub_header').style.marginTop='0px'
		document.getElementById('sms_div1').style.marginTop='50px'
		document.getElementById('sms_div').style.height='500px'
		document.getElementById('sms_div1').style.height='500px'
		document.getElementById('myDropZoneForm').style.background='#fff'
		document.getElementById('triggerDrop').style.background='#fff'
	}
	function choose_mobile() {
		document.getElementById('c1').className='btn btn-xs btn-danger'
		document.getElementById('c2').className='btn btn-xs btn-os'
		document.getElementById('c3').className='btn btn-xs btn-os'
		document.getElementById('user_input').placeholder='Using Recepients Mobile No'
		medium='mobile'
		ui_msg='Must enter a mobile number to send the photo/video to'
	}

	function choose_login() {
			document.getElementById('c1').className='btn btn-xs btn-os'
			document.getElementById('c2').className='btn btn-xs btn-danger'
			document.getElementById('c3').className='btn btn-xs btn-os'
			document.getElementById('user_input').placeholder='Using Recepients Login Name'
			medium='login'
			ui_msg='Must enter a login name to send the photo/video to'
		}

	function choose_email() {
		document.getElementById('c1').className='btn btn-xs btn-os'
		document.getElementById('c2').className='btn btn-xs btn-os'
		document.getElementById('c3').className='btn btn-xs btn-danger'
		document.getElementById('user_input').placeholder='Using Recepients Email Add'
		medium='email'
		ui_msg='Must enter an email address to send the photo/video to'
}
	var www_box5='www_box'
	var www_box2='www_box2'
	var cs
	var myDropzone
	var user_input
	var medium='mobile'
	var triggerDrop
	var ui_msg='Must enter a mobile number to send the photo/video to'
	if (getCookie('mobile')) document.getElementById('user_input').value=getCookie('mobile')
	if (document.getElementById('user_input').value=='') {
		if (getCookie('last_mobile')) document.getElementById('user_input').value=getCookie('last_mobile')
	}
	var files=0
	var url
	var medium='mobile'
	var files
	var p0=document.getElementById('prog_bar_units')
	var p1=document.getElementById('prog_bar_tens')
	var p2=document.getElementById('prog_bar_huns')
	var tp
	var data

	function get_session() {
		var url = 'inc/x_get_session.php?mobile=' + getCookie('mobile')
		var request = $.ajax({
			url: url,
			type: "GET",
			dataType: "html",
			cache: false,
			success: function(msg) {
				location.href='editImg.php?imgName='+msg.split('|')[0]+'&ext='+msg.split('|')[1]
			}
		})		
	}
	
	function set_session(mobile,data) {
		var url = 'inc/x_set_session.php?mobile=' + mobile + '&data=' + data
		console.log(url)
		var request = $.ajax({
			url: url,
			type: "GET",
			dataType: "html",
			cache: false,
			success: function(msg) {
				console.log(msg)
			}
		})		
	}
	
	function toggle_photo() {
		if (!medium) medium = 'mobile'
		if (document.getElementById('user_input').value !='') {
			if (!getCookie('last_mobile')) setCookie('last_mobile', document.getElementById('user_input').value)
		} else {
			if (getCookie('last_mobile')) document.getElementById('user_input').value=getCookie('last_mobile')
		}
		setCookie('user_input_h', document.getElementById('user_input').value + '|' + medium + '|' + document.getElementById('user_time_hours').value + ':' + document.getElementById('user_time_minutes').value + ':' + document.getElementById('user_time_seconds').value)

		if (document.getElementById('u_msg')) document.getElementById('u_msg').style.display='block'
		if (!document.getElementById('user_input').value) {
			ntfy(ui_msg)
			document.getElementById('user_input').style.border='1px solid orange'
			return false
		} else {
			if (document.getElementById('dzMsgAfter')) document.getElementById('dzMsgAfter').style.display='none'
			user_input=document.getElementById('user_input').value
			url="upload_snapchat.php?user_input="+user_input+'&medium='+medium,
			document.getElementById('myDropZoneForm').style.display='block'
			document.getElementById('myDropZoneForm').style.border='none'
			document.getElementById('myDropZoneForm').style.marginLeft='0px'
			document.getElementById('myDropZoneForm').style.background=''
			if (!myDropzone) {
				myDropzone = new Dropzone("form#myDropZoneForm", { 
					url: url,
					thumbnailWidth: 250,
					thumbnailHeight: 250
				});
			}
			myDropzone.on("addedfile", function(file) {
			if (document.getElementById('dzMsgAfter')) document.getElementById('dzMsgAfter').style.display='none'
				document.getElementById('myDropZoneForm').style.display='block'
				document.getElementById('myDropZoneForm').style.border='none'
				document.getElementById('myDropZoneForm').style.marginLeft='0px'
				document.getElementById('triggerDrop').style.background=''
				document.getElementById('myDropZoneForm').style.background=''
				files++
				if((file.type=='video/x-ms-wmv')||(file.type=='video/avi')) {
					alert('Only .mpg .mpeg and .mp4 files allowed for now')
					return false
				} else {
					files++
				}
			});
		myDropzone.on("totaluploadprogress", function(progress) {
		tp=progress
		document.getElementById('triggerDrop').style.display='none'
		if (p0.style.display=='none') {
			p0.style.display='block'
			p1.style.display='block'
			p2.style.display='block'			
		}
		var w = Math.round(progress,0) + ''
		  if (Math.round(progress,0)<10) {
			  var u=w.substring(0,1)
			  var t='0'
		  } else {
			  var u=w.substring(1,2)
			  var t=w.substring(0,1)
		  }
		  p0.src='assets/digits/' + u + '.png'
		  p1.src='assets/digits/' + t + '.png'
		  if (Math.round(progress,0)==100) {
			  p2.src='assets/digits/1.png'
			  p1.src='assets/digits/0.png'
			  p0.src='assets/digits/0.png'
		  }
		  if (Math.round(progress,0)<100) {
			  p2.src='assets/digits/0.png'
		  }
		});

		  myDropzone.on("complete", function(file) {
			  	document.getElementById('triggerDrop').style.display='block'
			  	document.getElementById('triggerDrop').style.background='#fff'
				document.getElementById('triggerDrop').innerHTML='<div id=\'dzMsgAfter\'><span><img src=\'assets/img/x04.png\' style=\'width:50px\'></span><span style=\'font-family:Economica;font-size:32px;margin-top:5px;padding-top:15px\'>SEND AGAIN!</span><BR>CLICK HERE TO BROWSE FOR A SELF DESTRUCTING PHOTO TO SEND VIA SMS</div>'
				document.getElementById('myDropZoneForm').innerHTML=''
				p0.style.display='none'
				p1.style.display='none'
				p2.style.display='none'				
				document.getElementById('triggerDrop').style.left='25px'
				document.getElementById('triggerDrop').style.height='125px'
				document.getElementById('myDropZoneForm').style.display='block'
				document.getElementById('myDropZoneForm').style.cssText=document.getElementById('triggerDrop').style.cssText
				document.getElementById('myDropZoneForm').style.background='none'
				document.getElementById('triggerDrop').style.zIndex='99999999999999999999999999'
				document.getElementById('triggerDrop').style.border='none'
				//document.getElementById('myDropZoneForm').style.display='none'
				ntfy('File loaded successfully!','#99CCFF')
				ntfy('Click in box below to upload another one','#99CCFF')
				if (getCookie('editImg')=='edit'){
					get_session()
				}
			});
			document.getElementById('myDropZoneForm').style.marginLeft='0px'
			document.getElementById('triggerDrop').style.left='10px'
			//document.getElementById('triggerDrop').style.marginLeft='5px'
			document.getElementById('myDropZoneForm').style.left='0px'
			$('#myDropZoneForm').click()
		}
	}		  
	
	function cancel_sms() {
		document.getElementById('myDropZoneForm').style.display='none'
		document.getElementById('sms_table').style.display=''
		document.getElementById('sms_table2').style.display=''
		document.getElementById('browse_icon').style.display='none'
		document.getElementById('sms_div1').style.display='none'
		document.getElementById('sms_label').style.display='none'		
	}

//detectswipe('img_profile', myfunction);	
</script>
<?php ob_flush(); ?>