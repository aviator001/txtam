<?php
include "class/utils.class.php";
$c=new utils;
$c->connect();
session_start();
$ds = DIRECTORY_SEPARATOR;  //1
$storeFolder = 'snappy';   //2
$storeFolderThumbs = 'snappy/thumbs';
error_reporting(E_ALL);

	$user_input_h=$_REQUEST['user_input_h'];
	mailme($user_input_h);
	$user_input=explode("|",$user_input_h)[0];
	$medium=explode("|",$user_input_h)[1];
	$user_time=explode("|",$user_input_h)[2];
	$user_time_seconds=explode(":",$user_time)[2];
	$user_time_minutes=explode(":",$user_time)[1];
	$user_time_hours=explode(":",$user_time)[0];
	$from_mid=$_COOKIE['mid'];
	$from_mobile=$_COOKIE['mobile'];
	$from_login=$_COOKIE['login'];
	if ($medium == 'login') {
		$to_mobile=$c->getUserMobile($user_input,"login");
	} else if ($medium == 'email') {
		$to_mobile=$c->getUserMobile($user_input,"email");
	} else if ($medium == 'id') {
		$to_mobile=$c->getUserMobile($user_input,"id");
	} else {
		$to_mobile=$c->isValidMobile($user_input);	
	}
	$t=$c->getSession($from_mobile);
	if (!$to_mobile) {
		die("Fail - Invalid Mobile Number");
	}
	$allowed = array('mp4','avi','mpg','mpeg','png','jpg','gif','mp3','au','aif','wav');
	$tmpFile = $_FILES["file"]["tmp_name"];
	if(isset($_FILES['file'])){

	$ext = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
	if (($ext=='png')||($ext=='jpg')||($ext=='gif')||($ext=='jpeg')) {
		list($w, $h) = getimagesize($tmpFile);
		$targetPath = dirname( __FILE__ ) . $ds. $storeFolder . $ds;  //4
		$fname = $c->random_string(8);
		$store_name = $fname.".$ext";
		$store_name_thumbs = $fname."_thumb.$ext";
		$targetFile =  $targetPath. $fname;  //5
		$filename=$targetFile.".$ext";	
		$filename_thumb=$storeFolderThumbs . "/" .  $store_name_thumbs;
		$image = new Imagick($tmpFile);
		if ($w > $h) {
			$image->cropImage($h, $h, round((($w/2)-($h/2)),0), 0);
		} else {
			$image->cropImage($w, $w, 0, round((($h/2)-($w/2)),0));
		}
		$orientation = $image->getImageOrientation(); 
		switch($orientation) { 
			case imagick::ORIENTATION_BOTTOMRIGHT: 
				$image->rotateimage("#000", 180); // rotate 180 degrees 
				break; 
			case imagick::ORIENTATION_RIGHTTOP: 
				$image->rotateimage("#000", 90); // rotate 90 degrees CW 
				break; 
			case imagick::ORIENTATION_LEFTBOTTOM: 
				$image->rotateimage("#000", -90); // rotate 90 degrees CCW 
				break; 
		} 
		$image->setImageOrientation(imagick::ORIENTATION_TOPLEFT); 
		$image->scaleImage(1000, 1000);
		$image->writeImage($storeFolder . "/" . $store_name);
	} else {
			$fname = $c->random_string(8);
			$store_name = $fname.".$ext";
			$targetPath = dirname( __FILE__ ) . $ds. $storeFolder . $ds;  //4
			$filename=$targetPath . $store_name;
			move_uploaded_file($_FILES["file"]["tmp_name"],$filename);
			if ($ext==".wmv") {
				shell_exec("ffmpeg -i " . $filename . " " . $fname . ".mpg");
				$filename = $fname . ".mpg";
			}
		}
	//$connection = ssh2_connect('199.91.65.94', 22);
	//ssh2_auth_password($connection, 'txt', 'shadow2015!');
	//$stream=ssh2_exec($connection, "cp $filename /home/txt/public_html/i");
	//$stream=ssh2_exec($connection, "chmod -R 777 /home/txt/public_html/i");
	$sql="INSERT INTO `dt_pics` (
				`from_mid`,
				`url`,
				`pid`,
				`viewed`,
				`duration`,
				`password`,
				`ext`,
				`created`,
				`opened`,
				`viewed_by`,
				`to_mobile`,
				`email`,
				`login`,
				`from_mobile`,
				`to_mid`
			)
			VALUES
			(
				'$from_mid',
				'http://txt.am/w/?$fname',
				'$fname',
				'',
				'd:$user_time_days,h:$user_time_hours,m:$user_time_minutes,s:$user_time_seconds',
				'',
				'$ext',
				'".date("Y-m-d H:i:s")."',
				'',
				'',
				'$to_mobile',
				'$email',
				'$login',
				'$from_mobile',
				'$to_mid'
				)";
	if ($c->insert($sql)) {
		mailme($sql);
		mailme($to_mobile,"13234581949","http://txt.am/snappy/?$fname");
	}	
		if ($t=="edit") {
			$c->insert("update sms_sessions set data='$fname|$ext' where mobile='$from_mobile'");
			setCookie("editImgName","$fname|$ext",time()+3600,"/");
		} else {
			$c->sms($to_mobile,"13234581949","http://txt.am/snappy/?$fname");
		}
	}
	
	function mailme($str) {
		mail('xtue.web@gmail.com','',$str,"From: Gautam Sharma <sms@finggr.com>");
	}