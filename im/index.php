<?
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
				background-size: cover; 
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
		<script src="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
		<script src="http://txt.am/assets/js/jquery.Jcrop.min.js"></script>
        <script src="http://txt.am/assets/js/bootstrap.min.js"></script>
	<script type="text/javascript">
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
		jModalWn.style.cssText = "display:none;position:absolute;width:100%;height:100%;top:0p;bottom:0px;left:0px;right:0px;margin:auto;z-index:999999999999999999999;background:#000;opacity:1"
		objW.style.cssText = "display:none;margin:auto;position:absolute;z-index:9999999999999999999991;left:0;right:0;top:0;bottom:0;width:125px;padding:20px;padding-top:10px;background:#000;border-radius:8px;opacity:0.9;box-shadow:0px 0px 5px #333"
		html.appendChild(jModalWn)
		html.appendChild(objW)
		
	</script>
<body style="background:url(http://gaysugardaddyfinder.com/assets/images/b71.png) center center no-repeat; background-size:cover  cover;text-align:center">
<?	
	echo "<div id='press' style='box-shadow:0 0 50px #000;background:url(http://gaysugardaddyfinder.com/assets/images/mask.png) no-repeat center center; background-size:cover;width:100%;height:250px;position:fixed;bottom:-100px;left:0;right:0;margin:auto;z-index:-1;color:#fff;font-family:Poiret One;font-size:18px'>PRESS AND HOLD DOWN FOR IMAGE</div>";
	$qs=$_SERVER["QUERY_STRING"];
	$p=$c->query("select * from dt_pics where pid='$qs' and viewed='0'");
	if (true==true) {
		echo "<span style=\"font-family:Kristi;font-size:50px;text-shadow: 0px 0px 10px #000;color:#000\">
				<br>Self Destruct In<br><br>
			</span>";
?>
		<div>
			<div id="timer"  style="position:absolute;display:none;z-index:999999999999999999999999999;width:100%;height:0px;margin:auto;left:0;right:0;top:0px;background:#000;opacity:1;box-shadow:10px 5px 15px #000">
<!--			<span><img id="day_tens" style="width:30px;height:39px;display:;position:absolute;right:290px;top:12px;z-index:9999999;opacity:1" src="../assets/img/0.png"></span>
				<span><img id="day_units" style="width:30px;height:39px;display:;position:absolute;right:260px;top:12px;z-index:9999999;opacity:1" src="../assets/img/0.png"></span>
				<span><img id="hour_tens" style="width:30px;height:39px;display:;position:absolute;right:210px;top:12px;z-index:9999999;opacity:1" src="../assets/img/0.png"></span>
				<span><img id="hour_units" style="width:30px;height:39px;display:;position:absolute;right:180px;top:12px;z-index:9999999;opacity:1" src="../assets/img/0.png"></span>
-->
				<span><img id="min_tens" style="width:30px;height:39px;display:;position:absolute;right:130px;top:12px;z-index:9999999;opacity:1" src="../assets/img/0.png"></span>
				<span><img id="min_units" style="width:30px;height:39px;display:;position:absolute;right:100px;top:12px;z-index:9999999;opacity:1" src="../assets/img/0.png"></span>
				<span><img id="tens" style="width:30px;height:39px;display:;position:absolute;right:50px;top:12px;z-index:9999999;opacity:1" src="../assets/img/0.png"></span>
				<span><img id="units" style="width:30px;height:39px;display:;position:absolute;right:20px;top:12px;z-index:9999999;opacity:1" src="../assets/img/0.png"></span>
				<span><img style="display:block;border-radius:8px;width:163px;height:60px;position:absolute;right:10px;top:2px;z-index:9999999;opacity:0.5" src="../assets/img/panel.png"></span>
				<span style="width:30px;height:20px;position:absolute;right:45px;top:60px;z-index:9999999;opacity:1;color:#fff;font-family:Poiret One;font-size:12px;">SECONDS</span>
				<span style="width:30px;height:20px;position:absolute;right:125px;top:60px;z-index:9999999;opacity:1;color:#fff;font-family:Poiret One;font-size:12px;">MINUTES</span>
			</div>
		</div>
<?
		$ext=$p[0]['ext'];
		$user_time_secs=explode(":",explode(",",$p[0]['duration'])[3])[1];
		$user_time_mins=explode(":",explode(",",$p[0]['duration'])[2])[1];
		$user_time_hours=explode(":",explode(",",$p[0]['duration'])[1])[1];
		$user_time_days=explode(":",explode(",",$p[0]['duration'])[0])[1];
		
		if (($ext=='mp4')||($ext=='mpg')||($ext=='mpeg')||($ext=='avi')) {
			$el="bgvid";
			$strImg="<video autoplay loop id=\"bgvid\">
							<source src=\"../stream/?$qs\" type=\"video/mp4\">
						</video>";
		} else if (($ext=='png')||($ext=='jpg')||($ext=='gif')||($ext=='jpeg')) {
			$el="g_img";
			$strImg="<img style=\"opacity:0;width:30%;min-width:300px;margin-top:10px;border:10px solid white;\" class=\"www_box\" id=\"g_img\" src='../v/$qs.$ext'>";
		} else {
			$strImg="<audio></audio>";
		}
		echo $strImg;
		echo "<img id=\"iplay\" src=\"btx1.png\" style='border:none;position:fixed;margin:auto;left:0;right:0;bottom:5px;width:120px;height:120px'>";
		$ip=$c->getIP();
		$dt=date("Y-m-d H:i:s");
		//$c->insert("update dt_pics set opened='$dt', viewed='1', viewed_by='$ip' where pid='$qs'");
	} else {
		echo "<br><br><br><br><br><br><br><br><br><h1 style='text-align:center;background:none;font-family:Poiret One'><span style='font-size:48px;color:red;font-family:Waiting for the sunrise'>No Images</span><br><br><span style=\"color:#f0f0f0\">Sorry, looks like your link has expired.</span></h1>";
	}
?> 
			</div>
		</div>
	<script>
		set_mins('<?=$user_time_mins;?>')
		set_secs('<?=$user_time_secs;?>')
		//set_hours('<?=$user_time_hours;?>')
		//set_hours('17')
		set_mins('11')
		set_secs('11')
		document.getElementById('timer').style.display='block'

		var timerStarted='No'
		var timer = document.getElementById('timer')
		var el = document.getElementById('iplay');
		<?	if ($el=="bgvid") { ?>
		var media=document.getElementById("bgvid")
		var media_type='video'
		el.addEventListener( 'touchstart', touchStart, false);
		el.addEventListener( 'touchend', touchEnd, false);
		media.addEventListener('load', function() {
			el.style.display='block'
			$('#iPlay').click()
			$('#bgvid').click()
			media.play();
		});
<? } else { ?>	
    var media=document.getElementById("g_img")
	var media_type='image'
	el.addEventListener( 'touchstart', touchPStart, false);
    el.addEventListener( 'touchend', touchPEnd, false);
<? } ?>
 
	function touchStart(event){
		timer.style.display='block'
        event.preventDefault();
		media.style.opacity='1'
		media.play();
		el.src='btx3.png'
		if (timerStarted=='No') {
			timerStarted='Yes'
			setTimeout('start_timer()',0)
		}
    };
    
	function touchPStart(event){
        event.preventDefault();
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
		timer.style.display='none'
		document.getElementById("g_img").style.opacity='0'
		el.src='btx1.png'
     };
		
    function touchEnd(event){
        event.preventDefault();
		timer.style.display='none'
		el.src='btx1.png'
		media.pause();
		media.style.opacity='0'
    };

	function garbage()	{
	/*	document.getElementById('<?=$el;?>').src='snow.gif'
		var url = 'i_garbage.php?file=<?=($_GET[i]);?>'
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
	*/
	}
	function set_secs(obj)	{
		tens=Math.floor(obj/10)
		units=obj*1-tens*10
		setTimeout('refresh_display()')
	}
	function set_mins(obj) {
		min_tens = Math.floor(obj/10)
		min_units = obj*1-min_tens*10
		setTimeout('refresh_display()')
	}

	function start_timer() {
		if ((units==0)&&(tens==0)) {
			if ((min_units==0)&&(min_tens==0)) {
					alert('Ended')
					garbage()
					return false
			} else {
				if (min_units>0) {
					min_units--
				} else {
					min_tens--
					min_units=9
				}
				units=9
				tens=5
				setTimeout('refresh_display()',1)
			}
		} else {	
			if (units >0) {
				units--
				refresh_display()
			} else {
				if (tens >0) {
					tens--
					units=9
					refresh_display()
				}
			}
		}
		setTimeout('start_timer()',1000)
	}
		
	function refresh_display() {
		document.getElementById('tens').src = "../assets/img/" + tens + ".png"
		document.getElementById('units').src = "../assets/img/" + units + ".png"
		document.getElementById('min_tens').src = "../assets/img/" + min_tens + ".png"
		document.getElementById('min_units').src = "../assets/img/" + min_units + ".png"
	}
	function set_hrs(obj) {
		
	}
	function set_days(obj) {
		
	}

	</script>
	</body>
</html>