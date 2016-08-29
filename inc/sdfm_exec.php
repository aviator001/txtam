<?php

$url = 'http://gaysugardaddyfinder.com/lookup/index.php';
$params = "login=".$_REQUEST['login'];
$x=curl_call($url,$params);

function curl_call($url,$params) {
$ch = curl_init();
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC ) ;
curl_setopt($ch, CURLOPT_USERPWD, "procom:RR2zLxzhzBvuJoGNGH");	
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_SAFE_UPLOAD, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
return json_decode(curl_exec($ch));
curl_close($ch);
}
?>