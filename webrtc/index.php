<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="webrtc.css">
    </head>
    <body style="overflow:hidden;background:url(templates/fav_bg_02.png)">
        <div class="videoContainer" onclick="fs()" >
        </div>
        <video id="localVideo" style="position:absolute;width:100px;height:100px" oncontextmenu="return false;"></video>
        <div style="width:100%" id="remotes"></div>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
        <script src="latest.js"></script>
    </body>
</html>
<script>
function fs() {
var el = document.documentElement
, rfs = // for newer Webkit and Firefox
       el.requestFullScreen
    || el.webkitRequestFullScreen
    || el.mozRequestFullScreen
    || el.msRequestFullScreen
;
if(typeof rfs!="undefined" && rfs){
  rfs.call(el);
} else if(typeof window.ActiveXObject!="undefined"){
  // for Internet Explorer
  var wscript = new ActiveXObject("WScript.Shell");
  if (wscript!=null) {
     wscript.SendKeys("{F11}");
  }
}
}
</script>