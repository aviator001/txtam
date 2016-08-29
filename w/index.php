<?
ini_set('display_errors', 1);
	include "../inc/utils.class.php";
	$u = new utils();
	$is = $u->is_mobile();
	$c=$u;
	$c->connect();
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
		<link rel="stylesheet" href="../assets/css/bootstrap.css">
		<link rel="stylesheet" href="../assets/css/bootstrap-responsive.css">
		<link href="../assets/css/stream.css" rel="stylesheet" type="text/css" />
		<link href="../assets/css/dropzone.css" rel="stylesheet" type="text/css" />
		<style>
			video#bgvid { 
				position: fixed;
				top: 50%;
				left: 50%;
				min-width: 100%;
				min-height: 100%;
				width: auto;
				height: auto;
				z-index: -100;
				-ms-transform: translateX(-50%) translateY(-50%);
				-moz-transform: translateX(-50%) translateY(-50%);
				-webkit-transform: translateX(-50%) translateY(-50%);
				transform: translateX(-50%) translateY(-50%);
				object-fit:contain;
				max-width:100%;
				max-height:100%
				}		
			.eInput {
				text-align:left; font-size:24px;font-family:Waiting for the sunrise;width: 100%; background: rgb(255, 255, 255); height:30px; border:none; border-radius:5px;padding:10px
			}
			.eLabel {
				font-family:Poiret One;font-size:18px;color:#333; margin-left: 15px; font-family:Poiret One;font-size:24px; margin-left: 15px;
			}
		</style>
		<link href='https://fonts.googleapis.com/css?family=Raleway|Poiret+One|Open+Sans+Condensed:300|Kristi|Waiting+for+the+Sunrise' rel='stylesheet' type='text/css'>
		<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
		<script src="http://code.jquery.com/ui/1.8.21/jquery-ui.min.js"></script>
		<script src="http://txt.am/assets/js/jquery.Jcrop.min.js"></script>
        <script src="http://txt.am/assets/js/bootstrap.min.js"></script>
	<script type="text/javascript">

	var html = document.documentElement;
	var objText, objType, objStyle, objWait, spinner
	var alpha = document.getElementById('alpha')
	var toast = document.createElement('span');
	var jToastWn = document.createElement('div');
	var jModalWn = document.createElement('div');
	var jBoxCont = document.createElement('div');
	var spinner = document.createElement('div');
	var objWait = document.createElement('img');
	var objW = document.createElement('img');
	var objT = document.createElement('span');
	var err_img = 'assets/images/a000.png';
	var dot2 = 'http://gaysugardaddyfinder.com/assets/images/dot4.png'
	var dot1='http://gaysugardaddyfinder.com/assets/images/dot1.png'
	jModalWn.id='jModalWn'
	objW.id='objW'
	objT.id='objT'
	spinner.style.cssText = "display:none;position:fixed;width:100%;height:100%;top:0px;bottom:0px;left:0px;right:0px;margin:auto;z-index:99999999999;background:;opacity:1"
	jModalWn.style.cssText = "display:none;position:fixed;width:100%;height:100%;top:0px;bottom:0px;left:0px;right:0px;margin:auto;z-index:999999999999999999999;background:;opacity:1"
	jToastWn.style.cssText = "text-align:center;padding:10px;display:none;position:fixed;z-index:99999999999999999999999;right:10px;top:10px;font-size:18px;color:white"
	toast.style.cssText = "text-align:center;padding:10px;display:none;position:fixed;z-index:999999999999999999990;right:10px; top: 10px; font-size:18px; color: white"
	objWait.style.cssText = "width:150px;height:150px;margin:auto;position:fixed;z-index:9999999999999999999991;left:0;right:0;top:0;bottom:0;adding:20px;background:#fff;border-radius:8px;opacity:0.8;box-shadow:0px 0px 5px #333"
	objW.style.cssText = "margin:auto;position:fixed;z-index:9999999999999999999991;left:0;right:0;top:0;bottom:0;width:125px;padding:20px;padding-top:15px;background:#fff;border-radius:8px;opacity:0.9;box-shadow:0px 0px 5px #333"
	objT.style.cssText = "margin:auto;position:fixed;z-index:9999999999999999999992;left:0;right:0;top:0;bottom:0;width:125px;height:125px;padding:5px;padding-top:80px;font-size:14px;font-family:Open sans Condensed;text-align:center;word-wrap:break-word;background:none;text-shadow:1px 1px 0px #fff;border-radius:8px;opacity:1.0;"
	objWait.src='http://gaysugardaddyfinder.com/assets/img/wait.gif'
	objW.src='http://gaysugardaddyfinder.com/assets/img/wait.gif'
	
	function jwait(objTxt) {
		objT.innerHTML=objTxt
		jModalWn.appendChild(objWait)
		jModalWn.appendChild(objT)
		jModalWn.style.display='block'
	}

	function jhide() {
		document.getElementById('jModalWn').style.display=='none'
		jModalWn.style.display='none'
		objW.style.display='none'
		objT.style.display='none'
	}	
		var c=0,tg
		var timeouts = [];		
		var x=[]
		var x1=[]
		var x2=[]
		var op=[]
		var el=[]
		var v=[]
		var g		
		var xctr=0
		var selfD
		var units
		var tens
		var min_units
		var min_tens
		var hour_units
		var hour_tens
		var	set_display='none'
		var	set_media='pause'
		var	set_opacity='0'
		var hours_total
		function view_pic(obj) {
			var p = obj.split('attach/')[1]
			p=p.split('.')[0]
			var tmr=document.getElementById('timer')
			document.getElementById('jModalWn').style.display='block'
			document.getElementById('objW').style.display='block'
			document.getElementById('container').style.display='none'
			document.getElementById('objW').style.width='800px'
			document.getElementById('objW').style.height='900px'
			jModalWn.appendChild(tmr)
			document.getElementById('objW').innerHTML="<img title='Click to close me' onclick='hide_pic()' src='"+obj+"' class='www_box' style='cursor:hand;cursor:pointer;max-width:800px;max-height:800px;width:800px;height:800px;border:10px solid #fff'><br><div style='color:#fff'>HIDE ME</div>"
		}
	
		var ut

		var html = document.documentElement;
		var jModalWn = document.createElement('div');
		var objW = document.createElement('div');
		jModalWn.id='jModalWn'
		objW.id='objW'
		jModalWn.style.cssText = "display:none;position:absolute;width:100%;height:100%;top:0p;bottom:0px;left:0px;right:0px;margin:auto;z-index:999999999999999999999;background:#;opacity:1"
		objW.style.cssText = "display:none;margin:auto;position:absolute;z-index:9999999999999999999991;left:0;right:0;top:0;bottom:0;width:125px;padding:20px;padding-top:10px;background:#;border-radius:8px;opacity:0.9;box-shadow:0px 0px 5px #333"
		html.appendChild(jModalWn)
		html.appendChild(objW)

	</script>
<body style="background:url(../assets/img/b1000.jpg) no-repeat;background-size:cover cover;text-align:center">
<?	
	$qs=$_SERVER["QUERY_STRING"];
	$p=$c->query("select * from dt_pics where pid='$qs'");
	if (count($p)>0) {
		echo "<span style=\"font-family:Kristi;font-size:50px;text-shadow: 0px 0px 10px #000;color:#000\">
				<br>Self Destruct In<br><br>
			</span>";
?>
		<div>
			<div id="timer"  align=center style="margin-left:-100px;display:none;z-index:999999999999999999999999999;background:none;opacity:1">
				<span><img id="hour_tens" style="width:30px;height:39px;display:;position:absolute;right:280px;top:12px;z-index:9999999;opacity:1;" src="../assets/img/0.png"></span>
				<span><img id="hour_units" style="width:30px;height:39px;display:;position:absolute;right:250px;top:12px;z-index:9999999;opacity:1" src="../assets/img/0.png"></span>
				<span><img id="min_tens" style="width:30px;height:39px;display:;position:absolute;right:215px;top:12px;z-index:9999999;opacity:1" src="../assets/img/0.png"></span>
				<span><img id="min_units" style="width:30px;height:39px;display:;position:absolute;right:185px;top:12px;z-index:9999999;opacity:1" src="../assets/img/0.png"></span>
				<span><img id="tens" style="width:30px;height:39px;display:;position:absolute;right:150px;top:12px;z-index:9999999;opacity:1" src="../assets/img/0.png"></span>
				<span><img id="units" style="width:30px;height:39px;display:;position:absolute;right:120px;top:12px;z-index:9999999;opacity:1" src="../assets/img/0.png"></span>
				<span><img style="display:block;border-radius:8px;width:213px;height:60px;position:absolute;right:10px;top:2px;z-index:9999999;opacity:0;" src="../assets/img/panel.png"></span>
				<span style="width:30px;height:20px;position:absolute;right:145px;top:60px;z-index:9999999;opacity:1;color:#fff;font-family:Poiret One;font-size:12px;">SECONDS</span>
				<span style="width:30px;height:20px;position:absolute;right:210px;top:60px;z-index:9999999;opacity:1;color:#fff;font-family:Poiret One;font-size:12px;">MINUTES</span>
				<span style="width:30px;height:20px;position:absolute;right:270px;top:60px;z-index:9999999;opacity:1;color:#fff;font-family:Poiret One;font-size:12px;">HOURS</span>
			</div>
		</div>
		<div id="logz" style="display:none"><img src='http://gaysugardaddyfinder.com/assets/img/wait.gif'></div>
		<div style="display:none" id="logx"></div>
<?
		$ext=$p[0]['ext'];
		$file=$qs . "." . $ext;
		$user_time_secs=explode(":",explode(",",$p[0]['duration'])[3])[1];
		$user_time_mins=explode(":",explode(",",$p[0]['duration'])[2])[1];
		$user_time_hours=explode(":",explode(",",$p[0]['duration'])[1])[1];
		$user_time_days=explode(":",explode(",",$p[0]['duration'])[0])[1];
		echo "<div id='press' style='background:none;width:100%;height:250px;position:fixed;bottom:-100px;left:0;right:0;margin:auto;z-index:-1;color:#fff;font-family:Poiret One;font-size:18px'>PRESS AND HOLD DOWN FOR IMAGE</div>";
		if (($ext=='mp4')||($ext=='mpg')||($ext=='mpeg')||($ext=='avi')) {
			$el="bgvid";
			$strImg="<video loop id=\"bgvid\">
							<source src=\"../stream/?$qs\" type=\"video/mp4\">
						</video>";
		} else if (($ext=='png')||($ext=='jpg')||($ext=='gif')||($ext=='jpeg')) {
			$el="g_img";
			$strImg="<img style=\"position:absolute;opacity:0;width:30%;min-width:300px;margin:auto;left:0;right:0;top:80px;border:10px solid white;\" class=\"www_box\" id=\"g_img\" src='../snappy/$qs.$ext'>";
		} else {
			$strImg="<audio></audio>";
		}
		echo $strImg;
		echo "<img id=\"iplay\" src=\"btx1.png\" style='border:none;position:fixed;margin:auto;left:0;right:0;bottom:5px;width:120px;height:120px'>";
		$ip=$c->getIP();
		$dt=date("Y-m-d H:i:s");
		echo "<div id='timer'></div><a href=''><img id='banner' style='display:none;position:absolute;z-index:9;left:0;right:0;top:0;bottom:0;margin:auto;width:100%;height:100%' src='../assets/img/b5.jpg'></a>";
	} else {
		echo "<div id='timer'></div><a href=''><img id='banner' style='position:absolute;z-index:9;left:0;right:0;top:0;bottom:0;margin:auto;width:100%;height:100%'src='../assets/img/err.png'></a>";
	}
?> 
			</div>
		</div>
	<script>
		var timerStarted='No'
		var timer = document.getElementById('timer')
		var el = document.getElementById('iplay');
		var original_start
		var a_secs
		set_mins('<?=$user_time_mins;?>')
		set_secs('<?=$user_time_secs;?>')
		set_hours('<?=$user_time_hours;?>')
		//set_hours('17')
		document.getElementById('timer').style.display='block'
		<?	if ($el=="bgvid") { ?>
		var media=document.getElementById("bgvid")
		var media_type='video'
		el.addEventListener( 'touchstart', touchStart, false);
		el.addEventListener( 'touchend', touchEnd, false);
		media.style.opacity='0'
		media.load()
		media.addEventListener('loadstart', function() {
			jwait()
			el.style.display='block'
		});
		media.addEventListener('canplay', function() {
			media.style.opacity='1'
			jhide()
			document.getElementById("logz").style.display='none'
		});

		media.addEventListener('playing', function() {
			media.style.opacity='1'
			jhide()
		});
<? } else if ($el=="g_img") { ?>	
	var media=document.getElementById("g_img")
	var media_type='image'
	el.addEventListener( 'touchstart', touchPStart, false);
	el.addEventListener( 'touchend', touchPEnd, false);
<? } ?>
 
	function touchStart(event){
		media.style.opacity='0'
		jwait()
		timer.style.display='block'
	        event.preventDefault();
		original_start=a_secs
		$('#iPlay').click()
		$('#bgvid').click()
		media.play();
		el.src='btx3.png'
		if (timerStarted=='No') {
			timerStarted='Yes'
			setTimeout('start_timer()',0)
		}
    };
    
	function touchPStart(event){
        event.preventDefault();
		original_start=a_secs
		timer.style.display='block'
		document.getElementById("g_img").style.opacity='1'
		document.getElementById("g_img").style.boxShadow='0 0 50px #000'
		document.getElementById("g_img").style.borderBottom='25px solid white'
		document.getElementById("g_img").style.borderRadius='5px 5px 5px 5px'
		el.src='btx3.png'   
		if (timerStarted=='No') {
			timerStarted='Yes'
			setTimeout('start_timer()',0)
		}
    };
	
	function touchPEnd(event){
        event.preventDefault();
		tt=0
		if (!release) {
			timer.style.display='none'
			document.getElementById("g_img").style.opacity='0'
			el.src='btx1.png'
		}
	};
		
    function touchEnd(event){
        event.preventDefault();
		tt=0
		if (!release) {
			timer.style.display=set_display
			el.src='btx1.png'
			if (set_media=='pause') {
				media.pause();
			} else {
				media.play();
			}
			media.style.opacity=set_opacity
		}
    };

	function garbage()	{
		var url = 'g_garbage.php?file=<?=$file;?>'
		media.style.display='none'
		document.getElementById('banner').style.display='block'
		var request = $.ajax({
			url: url,
			type: "GET",
			dataType: "html",
			cache: false,
			success: function(msg) {
				xctr=1
				clearTimeout(g)
				console.log(msg+'Garbage Removed')
				return false
			}
		})
	}
	function set_secs(obj)	{
		tens=Math.floor(obj/10)
		units=obj*1-tens*10
		original_start=tens*10+units*1
		total_seconds=obj
	}
	function set_mins(obj) {
		min_tens = Math.floor(obj/10)
		min_units = obj*1-min_tens*10
		total_minutes=obj
	}

	function set_hours(obj) {
		hour_tens = Math.floor(obj/10)
		hour_units = obj*1-hour_tens*10
		total_hours=obj
	}
	
var total_minutes
var total_seconds
var total_hours
var set_media
var set_display
var set_opacity
var tt=0
var t1
var t2
	function start_timer() {
		if (total_seconds==0) {
			if ((total_minutes==0)) {
				if ((total_hours==0)) {
					if (media_type=='video') {
						media.pause();
						media.contentLength=0
						media.type='img'
					} else {
						media=document.getElementById('g_img')
					}
					media.src='snow.gif'
					setTimeout('garbage()',3000)
					return false
				} else {
					total_hours=total_hours*1-1
					setTimeout('set_hours('+total_hours+')',0)
					setTimeout('set_mins(59)',0)
					setTimeout('set_secs(59)',0)
				}
			} else {
				total_minutes=total_minutes*1-1
				setTimeout('set_mins('+total_minutes+')',0)
				setTimeout('set_secs(59)',0)
			}
		} else {	
			if (total_seconds >0) {
				total_seconds=total_seconds*1-1
				setTimeout('set_secs('+total_seconds+')',0)
			}
		}
		t1=setTimeout('refresh_display()',0)
		t2=setTimeout('start_timer()',1000)
	}
	var release
	function refresh_display() {
		tt++
		log(tt)
		if (tt>5) {
			release=true
			set_display='block'
			set_media='play'
			set_opacity='1'
			document.getElementById('press').style.display='none'
			document.getElementById('iplay').style.display='none'
		}
		if ((total_seconds <10) && (total_minutes==0) && (total_hours==0)) {
			document.getElementById('tens').src = "../assets/digits/" + tens + ".png"
			document.getElementById('units').src = "../assets/digits/" + units + ".png"
			document.getElementById('min_tens').src = "../assets/digits/" + min_tens + ".png"
			document.getElementById('min_units').src = "../assets/digits/" + min_units + ".png"
			document.getElementById('hour_tens').src = "../assets/digits/" + hour_tens + ".png"
			document.getElementById('hour_units').src = "../assets/digits/" + hour_units + ".png"
		} else {
			document.getElementById('tens').src = "../assets/img/" + tens + ".png"
			document.getElementById('units').src = "../assets/img/" + units + ".png"
			document.getElementById('min_tens').src = "../assets/img/" + min_tens + ".png"
			document.getElementById('min_units').src = "../assets/img/" + min_units + ".png"
			document.getElementById('hour_tens').src = "../assets/img/" + hour_tens + ".png"
			document.getElementById('hour_units').src = "../assets/img/" + hour_units + ".png"
		}
	}
	function log(obj) {
		document.getElementById('logx').innerHTML=obj
	}
	function set_days(obj) {
		
	}

	</script>
	</body>
</html>