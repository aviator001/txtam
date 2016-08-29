<?
	session_start();
	if (isset($_SESSION['user'])) {
		$user = $_SESSION['user'];
		$name=$user['name'];
		$email=$user['email'];
		$login = explode("@",$email)[0];
		$id=$user['id'];
		$file_src="http://graph.facebook.com/$id/picture?type=large";
		
	} else {
		header("Location:http://txt.am/login.php");
	}
	include "class/utils.class.php";
	$u = new utils();
	$u->connect();
	$cv="height:94px; width:92px;right:263px;top:176px;";
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

	<link href="assets/css/bootstrap.min.css" rel="stylesheet">		
	<link href="assets/css/layout-responsive.css" rel="stylesheet" type="text/css" />		

		<style>
				@import url(http://fonts.googleapis.com/css?family=Economica);
				@import url(http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300);
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
		<link href='https://fonts.googleapis.com/css?family=Economica:100|Nothing+You+Could+Do|Wire+One|Averia+Sans+Libre|Flavors' rel='stylesheet' type='text/css'>
		<style>

				@import url(http://fonts.googleapis.com/css?family=Economica);
				@import url(http://fonts.googleapis.com/css?family=Poiret+One);
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
</style>	

  <style>
  .ui-autocomplete-loading {
    background: white url("assets/loading05.gif") right center no-repeat;
  }
  .ui-autocomplete {
	 font-size:15px;
	 font-family: Open Sans Condensed;
	 background:#BFCFFF;
	 z-index:99999999999999999999;
  }
  .ui-autocomplete {
    max-height: 300px;
    overflow-y: auto;
    /* prevent horizontal scrollbar */
    overflow-x: hidden;
  }
  /* IE 6 doesn't support max-height
   * we use height instead, but this forces the menu to always be this tall
   */
  * html .ui-autocomplete {
    height: 300px;
  }
 
</style>	
<script>
	var port=7777
	var chatSuccess
	var now2
	var now1
	var now3 = 0
	now1 = new Date().getTime();
	//setTimeout('monitor()',2000)

	function monitor() {
		if(!now3) {
			jhide()
			get_socket_db()
		}
	}

	var html = document.documentElement;
	var jChatWn
	var	msgContainer
	var	msgHeader
	var	msgBody
	var	msgInputBox
	var Server;
	var enter = '0'
	var msg=''
	var uOnline=''  	
	var to_user
	var oColor = 'grey';
	var winCount=25
	var openWin = []
	var ctr = 0
	var mid=getCookie('mid')
	var my_login=getCookie('login')
	var arr_login=[]
	var arr_mid=[]
	var arr_cid=[]
	var arr_ip=[]
	var cid=1
	var msg_out = ''
	var labCol 
	var cData
	var x_login
	var pic_err = 'assets/avatars/a7.png'
	var err_img = 'assets/avatars/a7.png'
	var msg_out
	var old_msg_out=''
	var msg_id
	var msg_no=0
	var showWin=[]
	var posWin=25
	var actWin=0
	var txt_msg, msg, img_src, msg_attach, img_attach, div_attach, preview_count=[]
	var msg_evt
	var final_msg
	var t
	var stream = []
	var to_user
	var who, asked=[], asked2 = []
	var a = [], a2 = [], sent_to
	var xsum=1000
	var payload, msg_type, clientID, to_user, txtMsg		 
	var ctrx=0
	var a3=''
	var allowed="0";
	var out_img1
	var in_img1
	var btn1
	var webcam
	var running=0
	var video_camera
	var rc=1;
	var to_user
	var dx, dy, dz
	var to_user
	var to_user
	var who
	var mob
	var alias
	var lc
		
		function getLC() {
			if (!getCookie('mobile')) {
				location.href='login.php'
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
			location.href='members.php'
		}
		
		function LC(m) {
			var url = "inc/x_getLC.php?m=" + m
			console.log(url)
			var request = $.ajax({
			   type: 'GET',
				url: url,
			    success: function(msg) {
					document.getElementById('darks').style.display='none'
					document.getElementById('mobile_box').style.display='none'
					document.getElementById('mobile_box2').style.display='none'
					var long_code=msg.split('|')[2]
					m=msg.split('|')[1]
					setCookie('long_code', long_code)
					setCookie('mobile', m)
					if (document.getElementById('long_code')) document.getElementById('long_code').textContent=msg.split('|')[2]
				}
			})				
		}
		setTimeout('init()',100)
		setTimeout('socket_register()',1000)
		function init() {
			if (getCookie('mobile')) {
				document.getElementById('start').style.display='block'
			} else {
				getLC()
			}
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
		<link href='http://fonts.googleapis.com/css?family=Raleway|Poiret+One|Open+Sans+Condensed:300|Kristi|Waiting+for+the+Sunrise' rel='stylesheet' type='text/css'>
		<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
		<script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
		<script src="http://txt.am/assets/js/jquery.Jcrop.min.js"></script>
        <script src="http://txt.am/assets/js/bootstrap.min.js"></script>
        <script src="http://txt.am/assets/dropzone.js"></script>
		
  <script>
  $(function() {
    $( "#mobile_txt" ).autocomplete({
      source: "inc/x_ac_contacts.php",
      minLength: 2,
      select: function( event, ui ) {
			mob=ui.item.value
			//alias=ui.item.label
			$( "#mobile_txt" ).val(mob)
			init_sms()  
      }
    });
  });
var socket
var host


function replace_all(str, find, replace) {
  return str.replace(new RegExp(find.replace(/([.*+?^=!:${}()|\[\]\/\\])/g, "\\$1"), 'g'), replace);
}
function isValidMobile(mob1) {
	var num = mob1;
	num = replace_all(num, ' ', '');
	num = replace_all(num, '-', '');
	num = replace_all(num, '.', '');
	num = replace_all(num, ',', '');
	num = replace_all(num, '(', '');
	num = replace_all(num, ')', '');
	num = ((num+'').length == 11) ? num : ('1' + num);	
	if ((num.length == 11) && (num.substring(0,1) == "1")) {
		return num;
	} else {
		return false;
	}
}
login=getCookie('login')
mobile=getCookie('mobile')

function socket_register() {
	host = 'ws://199.91.65.94:1338/?login='+login+'&mobile='+mobile;
	try {
		socket = new WebSocket(host);
		socket.onopen = function(msg) {};
		socket.onmessage = function(msg) {
			console.info(msg.data)
			var data=msg.data.split('|')
			if (data[0]=='msg') {
				to_user=data[2]
				var txtMsg=data[3]
				txt_msg = document.createElement('div')
				txt_msg.innerHTML=txtMsg	
				console.log(openWin)				
				//Received a Text Message
				setCookie('chatting_with',to_user)
				if (openWin.indexOf(to_user+'') < 0) {
					var ini_msg = "<div style='width:100%;font-family:Open Sans Condensed;font-size:16px;background:none;color:#000;text-align:left'><table style='width:100%;background:none' cellspacing=2 cellpadding=0><tr><td class='btn btn-danger btn-xs' align=left style='padding:0;margin:0'>"
					var pre_msg = "</td><td align=left style='width:90%;background:none;text-align:left;padding-left:10px;word-wrap:break-word'>"
					var end_msg = "</td></tr></table></div>"
					jChatWn = document.createElement('div')
					noOpen = 'msgBody' + to_user + ''
					openWin[ctr] = '' + to_user
					jChatWn.id = 'msgOuterBox' + to_user
					html.appendChild(jChatWn)
					jChatWn.className = "msgOuterCont"
					if (rc == '1')	{
						jChatWn.style.cssText = 'position:absolute;z-index:999999999999999999999999;width:225px;max-width:225px;height:300px;bottom:0;box-shadow:0px 0px 30px rgba(0,0,0,0.3),inset 0px 0px 50px rgba(255,0,255,0.3);right:' + posWin
						$rc=0;
					} else {
						jChatWn.style.cssText = 'position:absolute;z-index:999999999999999999999999;width:225px;max-width:225px;height:300px;bottom:0;box-shadow:0px 0px 30px rgba(0,0,0,0.3),inset 0px 0px 50px rgba(0,255,255,0.3);right:' + posWin
						rc=1;
					}
					setCookie('send_to',to_user)
					jChatWn.innerHTML =
						'<div id="msgContainer" style="background:#f0f0f0;width:225px;max-width:225px" onclick="setUser(\'' + to_user + '\');">' +
							'<div class="msgHeader" style="width:225px;max-width:225px">' +
								'<div class="row">' +
									'<div class="col-xs-7" style="margin-left:5px; width:190px;min-width:190px">Chat with ' + to_user +
									'</div>' +
									'<div class="col-xs-1 pull-right" style="margin-right:25px;margin-top:-5px">' +
										'<img onclick="video_chat('+to_user+')" src="assets/video_icon.png" style="position:absolute;height:15px;margin-top:10px;margin-left:-30px"> <img src="http://gaysugardaddyfinder.com/assets/images/close.png" onclick="close_chat('+to_user+')" style="margin-top:6px;width:20px;cursor:hand;cursor:pointer">' +
									'</div>' +
								'</div>' +
							'</div>' +
							'<div id="msgBody' + to_user + '" class="msgBody" style="width:225px;margin-top:8px;margin-bottom:8px;font-family:Open Sans; font-size:12px;color:'+oColor+'">'+ini_msg + '<img src=\'assets/img/default_avatar.png\' style=\'width:30px;background:none;\'>' + pre_msg + txt_msg.innerHTML + end_msg+'</div>' +
							'<div id=\'dz\' style=\'width:100%;height:100%;background:#f0f0f0;position:absolute\'></div>'+
							'<div id="im_footer"' + to_user + '>' +
								'<textarea class="imTextarea" name="txt' + to_user + '" id="txt' + to_user + '" contentEditable="true" value="..."></textarea>' +
								'<img title="Browse and Send a photo" alt="Browse and Send a photo" src="assets/img/pclip.png" id="pclip'+to_user+'" onclick="javascript:show_attach()" style="position:absolute;width:30px;bottom:5px;z-index:999999999999999;cursor:hand;cursor:pointer">' +
								'<input id="msgFile' + to_user + '" type="file" onchange="fmy.previewFile()" style="position:absolute;bottom:1px;display:none">' +
							'</div>' +
						'</div>'
						actWin++
						ctr++
				} else {
					if (document.getElementById('msgOuterBox'+to_user)) {
						document.getElementById('msgOuterBox'+to_user).style.display='block'
						showWin[to_user] = 'block'
					}	

					var my_login=getCookie("login")
					var my_pic = "<img style=\"margin-right:0px;width:35px;border-radius:4px;border:4px solid white;box-shadow: 0 0 1px #000\" class=\"www_box\" src=\"assets/default_avatar.png\">"
					var ini_msg = "<div style='margin:5px;border-bottom:1px solid skyblue;width:100%;font-family:Open Sans Condensed;font-size:16px;background:none;color:#000;text-align:left'><table border=0 style='width:100%;background:none' cellspacing=0 cellpadding=0><tr><td align=left style='width:40px;max-width:40px;padding:0;margin:0'>"
					var pre_msg = "</td><td align=left style='background:none;text-align:left;padding-left:0px;word-wrap:break-word'>"
					var end_msg = "</td></tr></table></div>"
					var ele = document.createElement("div")
					mb = document.getElementById('msgBody' + to_user)
					ele.innerHTML = ini_msg + my_pic + pre_msg + txt_msg.innerHTML + end_msg
					mb.appendChild(ele)
					mb.scrollTop = mb.scrollHeight;
				}
				repaint()				
		}
	};
	socket.onclose   = function(msg) {};

		setTimeout('set_session()',1000)
	
}
catch(ex){ 
	console.log(ex); 
}
}

var mb
var mobile
function video_chat() { 
	OpenWindow=window.open("https://txt.am/webrtc/?"+getCookie("vroom"), "Video Chat", "status=no, height=582, width=100%, location=no, resizable=no, toolbar=no, scrollbars=hidden, scrollbars=no, menubar=no");
	self.name="video chat with" + getCookie('vroom')
}

function setUser() {
	to_user=mob
}
function init_sms(mob) {
	if (!mob) mob=document.getElementById('mobile_txt').value
	mob=isValidMobile(mob)
	setCookie('chatting_with', mob)
	mobile=isValidMobile(getCookie('mobile'))
	vid=mob*1 + mobile*1
	vd='V' + vid
	if (vrooms.indexOf(vd) < 0) {
		vrooms.push(vd)
		setCookie('vroom', vd)
	}
	initMsg(mob)
}
		var my_pic = "<img style=\"margin-right:0px;width:35px;border-radius:4px;border:4px solid white;box-shadow: 0 0 1px #000\" class=\"www_box\" src=\"<?=$file_src;?>\">"
		var ini_msg = "<div style='margin:5px;border-bottom:1px solid skyblue;width:100%;font-family:Open Sans Condensed;font-size:16px;background:none;color:#000;text-align:left'><table border=0 style='width:100%;background:none' cellspacing=0 cellpadding=0><tr><td align=left style='width:40px;max-width:40px;padding:0;margin:0'>"
		var pre_msg = "</td><td align=left style='background:none;text-align:left;padding-left:0px;word-wrap:break-word'>"
		var end_msg = "</td></tr></table></div>"
		lc=getCookie('long_code').replace(/[^0-9]/g, "");


		$(document).keyup(function(e) {
			if (e) {
				if(e.target.id.indexOf('message_text') < 1)
					if ( e.keyCode == 13 ) {
						to_user=mob
						if (!to_user) to_user=getCookie('chatting_with')
						socket.send("new_msg|"+to_user+"|"+lc+"|"+e.target.value.trim()+"|"+alias)
						var ele = document.createElement("div")
						mb = document.getElementById('msgBody' + to_user)
						ele.innerHTML = ini_msg + my_pic + pre_msg + e.target.value + end_msg
						mb.appendChild(ele)
						mb.scrollTop = mb.scrollHeight;
						e.target.value=''
						setCookie("accepted_users", to_user)
					}
				}
			});
	mobile=getCookie('mobile')		
	function initMsg(to_user) {
		to_user=isValidMobile(to_user)
		if (openWin.indexOf('' + to_user) < 0) {
			if (to_user) showWin[to_user] = 'block'
			jChatWn = document.createElement('div')
			var noOpen = 'msgBody' + to_user + ''
			openWin[ctr] = '' + to_user
			jChatWn.id = 'msgOuterBox' + to_user
			html.appendChild(jChatWn)
			jChatWn.className = "msgOuterCont"
			if (rc == '1')	{
				jChatWn.style.cssText = 'position:absolute;z-index:999999999999999999999999;background:#f0f0f0;width:225px;max-width:225px;height:300px;bottom:0;box-shadow:0px 0px 30px rgba(0,0,0,0.3),inset 0px 0px 50px rgba(255,0,255,0.3);right:' + posWin
				$rc=0;
			} else {
				jChatWn.style.cssText = 'position:absolute;z-index:999999999999999999999999;background:#f0f0f0;width:225px;max-width:225px;height:300px;bottom:0;box-shadow:0px 0px 30px rgba(0,0,0,0.3),inset 0px 0px 50px rgba(0,255,255,0.3);right:' + posWin
				rc=1;
			}
			jChatWn.innerHTML = 	'<div id="msgContainer" style="width:225px;max-width:225px" onclick="setUser(\'' + to_user.trim() + '\');">' +
										'<div class="msgHeader" style="width:225px;max-width:225px">' +
											'<div class="row">' +
												'<div class="col-xs-7" style="margin-left:5px; width:190px;min-width:190px"> Chat with ' + to_user +
												'</div>' +
												'<div class="col-xs-1 pull-right" style="margin-right:25px;margin-top:-5px">' +
														'<img onclick="video_chat('+to_user+')" src="assets/video_icon.png" style="position:absolute;height:15px;margin-top:10px;margin-left:-30px"> <img src="http://gaysugardaddyfinder.com/assets/images/close.png" onclick="close_chat('+to_user+')" style="margin-top:6px;width:20px;cursor:hand;cursor:pointer">' +
												'</div>' +
											'</div>' +
										'</div>' +
										'<div id="msgBody' + to_user + '" class="msgBody" style="width:225px;margin-top:8px;font-family:Open Sans; font-size:12px;color:'+oColor+'"></div>' +
										'<div id=\'dz\' name=\'dz\' class=\'dropzone-previews needsclick dz-clickable\' style=\'\'></div>'+
										
										'<div id="im_footer"' + to_user + '>' +
											'<textarea class="imTextarea" name="txt' + to_user + '" id="txt' + to_user + '" contentEditable="true" value="..."></textarea>' +
											'<img src="http://gaysugardaddyfinder.com/assets/images/pclip.png" id="pclip'+to_user+'" onclick="javascript:show_attach()" style="position:absolute;width:30px;bottom:5px;z-index:999999999999999;cursor:hand;cursor:pointer">' +
										'</div>' +
									'</div>'
			document.getElementById(noOpen).innerHTML = ' '
			document.getElementById(noOpen).style.zIndex = '10000'
			jChatWn.style.display="block"
			ctr++
			actWin++
		} else  {
			if (document.getElementById('msgOuterBox'+to_user)) {
				if (document.getElementById('msgOuterBox'+to_user).style.display=='none') {
					document.getElementById('msgOuterBox'+to_user).style.display='block'
					actWin++
				}
			}		
		}
		console.log(openWin)
		repaint()
	}


		var nh=25
		var i=0
		var html=document.documentElement
		var nd=[]
		var nt=[]
		var old_str
		
		function ntfy(str,xCol) {
			if (str != old_str) {
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
			old_str=str
		}

		function hide_ntfy(i) {
			nh=0
			nd[i].style.display='none'
			nt[i].innerHTML=''
		}
var vd
var vid
var vrooms = [ ]

	function get_session() {
		var url = 'inc/x_get_session.php?mobile=' + getCookie('mobile')
		console.log(url)
		var request = $.ajax({
			url: url,
			type: "GET",
			dataType: "html",
			cache: false,
			success: function(msg) {
				var link = 'http://txt.am/i/'+msg.split('|')[0]+'.'+msg.split('|')[1]
				var my_pic = "<img style=\"margin-right:0px;width:35px;border-radius:4px;border:4px solid white;box-shadow: 0 0 1px #000\" class=\"www_box\" src=\"assets/default_avatar.png\">"
				var ini_msg = "<div style='margin:5px;border-bottom:1px solid skyblue;width:100%;font-family:Open Sans Condensed;font-size:16px;background:none;color:#000;text-align:left'><table border=0 style='width:100%;background:none' cellspacing=0 cellpadding=0><tr><td align=left style='width:40px;max-width:40px;padding:0;margin:0'>"
				var pre_msg = "</td><td align=left style='background:none;text-align:left;padding-left:0px;word-wrap:break-word'>"
				var end_msg = "</td></tr></table></div>"
				var ele = document.createElement("div")
				mb = document.getElementById('msgBody' + getCookie('chatting_with'))
				ele.innerHTML = ini_msg + my_pic + pre_msg + '<img onclick=\'link_img("'+link+'")\' src="'+link + '"  class=\'www_box\' style="cursor:hand;cursor:pointer;width:100px;border:5px solid #fff;border-radius:6px;">' + end_msg
				mb.appendChild(ele)
				mb.scrollTop = mb.scrollHeight;
				ntfy('File loaded successfully!','#99CCFF')
				ntfy('Click in box below to upload another one','#99CCFF')
				$("div#dz").html("")
			}
		})
	}
	
	function link_img(obj) {
		document.getElementById('pay_modal').style.display='block'
		document.getElementById('g').style.display='block'
		document.getElementById('g').src=obj
	}
	
	function link_img_close() {
	document.getElementById('pay_modal').style.display='none'
	document.getElementById('g').style.display='none'
}
	var mob
	var to
	var from
	
	function invite_sms() {
		mob=document.getElementById('mobile_txt').value
		if (mob=='') {
			alert('enter a valid 10 digit mobile number please')
			return false;
		}
		init_sms(mob)
		to=mob
		from = mobile
		var message='Click below to start a video chat with <?=$name;?> \r\n\r\nhttps://txt.am/webrtc/?'+getCookie('vroom')+'|'+to
		var url = 'inc/x_sms_wrapper.php?to='+to+'&from='+from+'&message='+message
		console.log(url)
		var request = $.ajax({
			url: url,
			type: "GET",
			dataType: "html",
			cache: false,
			success: function(msg) {
				alert('message sent!')
			}
		})		
	}
	
	
	function set_session() {
		var m_mobile=getCookie('mobile');
		var m_data="edit";
		var url = 'inc/x_set_session.php?mobile=' + m_mobile
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
	

	var files
	var myDropzone
	var fn

	function show_attach() {
		setCookie('chatting_with',document.getElementById('mobile_txt').value)
		var url="x_upload.php"
		if (!myDropzone) {
			myDropzone = new Dropzone("div#dz", { 
				url: url
			});
		}
			myDropzone.on("addedfile", function(file) {
				$('.dz-details').hide()
				files++
				if((file.type=='video/x-ms-wmv')||(file.type=='video/avi')) {
					alert('Only .mpg .mpeg and .mp4 files allowed for now')
					return false
				} else {
					files++
				}
			});
			myDropzone.on("totaluploadprogress", function(progress) {
			})
			myDropzone.on("complete", function(file) {
				if (file.name!=fn) {
					get_session()
				}
				fn=file.name
			});
			$("div#dz").click()
	}

	function repaint() {
		posWin=10
		for (var n=0;n<openWin.length;n++) {
			if (showWin[openWin[n]] == 'block') {
				console.log(openWin[n])
				if (document.getElementById('msgOuterBox'+openWin[n])) document.getElementById('msgOuterBox'+openWin[n]).style.right = posWin+'px'
				posWin=posWin + 230
			}
		}
	}
	function start_vc() {
		document.getElementById('vc').style.background='url(assets/00_vc_bkg.png)'
		document.getElementById('vc_txt').innerHTML='<span style=\'font-size:24px;color:#fff\'>Initiate a Video Call</span>'
		document.getElementById('vc_txt2').innerHTML='<br>'
	}
	var m, n
	</script>
	<body  style="overflow: none;width:100%;font-family:Open Sans Condensed;background:#c0c0c0">
		<div style="position:absolute;width:100%;height:100%;top:0px;left:0px;right:0px;bottom:0px;background:#000;opacity:0.9;z-index:9999100000;display:none;overflow-y:auto" id="pay_modal"></div>	
		<img onclick="link_img_close()" id="g" style="position:absolute;border:20px solid #f0f0f0;box-shadow:0 0 100px #000;left:0;right:0;top:0;bottom:0;margin:auto;z-index:99999999999999999999999999;max-width:90%;max-height:90%;display:none;" class="www_box">
		<div style="display:none;margin:auto;margin-top:50%;width:300px;height:5px;background:;text-shadow:none;display:none;position:absolute;z-index:99999999999;left:0;right:0;top:0;bottom:0;padding-right:<?=$pad;?>" id="mobile_box2">
			<div style="color:#fff;z-index:9999999999999999;font-family:Nothing You Could Do;font-size:24px" id="mobile_box">Mobile Number</div>
			<span><input type=text style="height:35px;width:240px;box-shadow:0 0 20px rgba(255,255,255,0.3);border-radius:0px;border:1px solid skyblue;padding-left:10px;font-family:Economica;font-size:32px" id="mobile"><img src="http://txt.am/assets/img/next1.png" style="position:absolute;width:40px;cursor:pointer;margin-left:10px" onclick="javascript:checkM()"></span>
		</div>
		<div style="background:url(assets/cellbg.png) center center no-repeat;background-size:cover;margin:auto;display:;position:absolute;z-index:99999;left:0px;right: 0px; top: 0px;  width: 320px; height: 200px; opacity: 1;" id="tint"></div>
		<div style="margin:auto;display:none;position:absolute;z-index:999999999;left:0px;right: 0px; top: 0px; bottom: 0px; width: 100%; height: 100%; opacity: 0.9; background: #000" id="darks"></div>
		<img src="http://smsgone.com/images/bar.gif" style="margin:auto;display:none;position:absolute;z-index:999999999;left:0;right:0;top:0;bottom:0;margin:auto" id="gears">
	</head>

	<div id='container' class="container" align=center style="font-family:Open Sans Condensed;font-size:18px;color:#dfdfd0;z-index:9999999;padding:0px;padding-top:0;border-radius:0 0 5px 5px;width:320px;margin:auto;left:0;right:0;position:absolute;top:0px;background:#f0f0f0;height:620px">
		<div class="row" style="width:320px">
			<table style="width:100%;border-radius:0 0 0px 0px;margin:0;padding:0;background:url(http://gaysugardaddyfinder.com/assets/images/backgrounds/bx07.png) center center;background-size:cover">
				<tr>
					<td>
						<img src="http://smsgone.com/g/logo.png" style="margin-top:0px;margin-bottom:20px;width:50px">
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
			<div id="main" style="background:text-shadow:none;border-radius:5px;padding:10px;margin-top:0px;text-align:center">
				<table valign=top style="background:none;width:100%;font-size:24px">
					<tr>
						<td align=left>
							<img style="margin-right:40px;width:75px;border-radius:4px;border:4px solid white" src="<?=$file_src;?>">
						</td>
						<td align=left style="padding-left:20px">
							<span style="font-size:18px;color:#333"><?=$name;?></span>
							<br><span style="font-size:16px;color:#333" id="email"><?=$email;?></span><br>
							<span style="font-size:16px;color:#333" id="mobile"><?=$_COOKIE[mobile];?></span>
							<!--<span><img style="border-radius:4px;box-shadow:0 0 25px rgba(255,255,255,0.5);position:absolute;opacity:0.6;height:90px; width:86px;right:238px;top:175px;z-index:999999"src="g/panel.png"></span>-->
						</td>
					</tr>
					<tr>
						<td colspan=2>
							<div id="table_inner"><div placeholder="My Mobile Number" contentEditable id="mobile"></div></div>
							<div id="divtable"></div>
						</td>
					</tr>
					<tr>
						<td colspan=2>
							<div style="border-radius:4px;padding:0px;width:320px;background:;background-size:cover;opacity:0.8;;position:absolute;display:block" id="anon"><span style="" id="login"></div>		
							<div style="box-shadow:0 -2 10px #333;z-index:-1;position:absolute;height:160px;padding:0;margin:0;width:320px;font-size:18px;color:#333;border-top:1px solid silver;margin-top:0px;padding-top:5px;background:white;margin-left:-10px;z-index:" id="vc" class="www_box5"></div>
							<span id='vc_txt' style="margin:5px;margin-top:15px;font-size:20px;color:#000">Start Texting Now!</b></span>
							<div style="display:none;color:#333;font-size:18px;" id="start">Mobile number to msg<br>
								<input type="text" id="mobile_txt" style="background:#fff;border-radius:4px;border:1px solid lightblue;width:150px;height:35px;font-size:20px;font-family:Economica">
								<div style="margin-left:0px;margin:10px"><input type="button" class="btn btn-xs btn-warning" value="Start Now" onclick="init_sms()"> <input type="button" class="btn btn-xs btn-info" value="Send Invite" onclick="invite_sms()"> </div>
								<div id='vc_txt2'></div><div style="color:#000;font-size:16px;margin-top:15px;padding-top:25px">To be able to send messages directly to anyone in your phone book, please use our app to import your conmtacts here</div>
								<br><div><a href="assets/contactsImporter.apk"><img src="assets/android_app.png" style="width:150px"></a></div>
								<div style="color:#0093D9;font-size:15px">Download the free android contact importer app now!</div>
							</div>
							<IMG SRC="assets/00_tags3.png" STYLE="cursor:hand;cursor:pointer;width:80px;position:absolute;left:320px;top:305px" onclick="start_vc()">							
							<IMG SRC="assets/00_cam.png" STYLE="cursor:hand;cursor:pointer;width:35px;position:absolute;left:345px;top:345px" onclick="start_vc()">							
						</td>
					</tr>
					<tr>
						<td colspan=2>
						</td>
					</tr>
					
				</table>
			</div>			
		</div>
	</div>
			
<? if (empty($_COOKIE[mobile])) { ?>
		<a href='<?=$loginUrl;?>'>
		<img style="width:150px;top:0;bottom:0;left:0;right:0;margin:auto;position:absolute" src="http://smsgone.com/face.png" style="width:100px"></a>
<? } ?>
<script>

	</body>
</html>
