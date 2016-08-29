<!DOCTYPE html>
    <head>
        <link rel="stylesheet" href="webrtc.css">
			<link href='https://fonts.googleapis.com/css?family=Economica' rel='stylesheet' type='text/css'>

    </head>
	<? if ($_GET[self]=='1') { ?>
    <body style="overflow:hidden;background:url(http://sugardaddydays.com/assets2/b5.jpg) no-repeat;background-size:cover">
		<div class="videoContainer" style="background:url(http://sugardaddydays.com/assets2/screen_w.png)" >
       </div>
         <video id="localVideo" style="top:0;left:0;position:absolute;width:95%;height:95%;z-index:999999999999;border-radius:10px;border:10px solid #f0f0f0;box-shadow:0px 0px 15px #333 inset;" oncontextmenu="return false;"></video>
        <div id="remotes"></div>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
        <script src="latest.js"></script>
        <div style="background:url(bg16.png) no-repeat;background-size:cover;opacity:0.8;font-family:Economica;font-size:72;color:#000;width:100%;height:20px;position:absolute;top:0;left:0;z-index:99999999999999999999;padding-top:10px;padding-bottom:0;padding-left:10px;opacity:0.9">MY LIVE BROADCAST URL <span style="color:white;padding:10px">http://txt.am/v2/<?=explode("&",$_SERVER[QUERY_STRING])[0];?></span></div>
	</body>
	<? } else { ?>
    <body style="overflow:hidden;background:url(http://sugardaddydays.com/assets2/b5.jpg) no-repeat;background-size:cover">
        <div class="videoContainer" style="background:url(http://sugardaddydays.com/assets2/screen_w.png)" >
       </div>
         <video id="localVideo" style="border-radius:200px;position:;width:100px;height:100px;z-index:999999999999;border:10px solid #f0f0f0;box-shadow:10px 10px 15px #333 inset;" oncontextmenu="return false;"></video>
        <div id="remotes"></div>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
        <script src="latest.js"></script>
	</body>
	<? } ?>
</html>
