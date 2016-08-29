<?php 
ob_start();
session_start(); 

include_once("config.php"); //Include configuration file.
require_once('inc/facebook.php' ); //include fb sdk
require_once('inc/utils.class.php' ); 

$c=new utils;
$c->connect();
	$facebook = new Facebook(array(
		'appId' => $appId,
		'secret' => $appSecret,
	));
	
	$fbuser = $facebook->getUser();
	
	if ($fbuser) {
		try {
			// Proceed knowing you have a logged in user who's authenticated.
			$me = $facebook->api('/me');
			$uid = $facebook->getUser();
		}
		catch (FacebookApiException $e) 
		{
			//echo error_log($e);
			$fbuser = null;
		}
	}
	
	// redirect user to facebook login page if empty data or fresh login requires
	if (!$fbuser){
		$loginUrl = $facebook->getLoginUrl(array('redirect_uri'=>$return_url, false));
		header('Location: '.$loginUrl);
	}
	
	//user details
	$fullname = $me['first_name'].' '.$me['last_name'];
	$email = $me['email'];
	$photo = implode($me,",");
	/* connect to mysql using mysqli */
			try {


							$file_src="http://graph.facebook.com/$uid/picture?type=large";
							
							$ds = DIRECTORY_SEPARATOR;  //1
							$storeFolder = 'photos';   //2
							$targetPath = dirname( __FILE__ ) . $ds. $storeFolder . $ds;  //4

							$fname = $uid.'_'.rand(11111,99999).".jpg"; 
							$targetFile =  $targetPath. $fname;  //5
							$filename=$targetFile;

							list($w_src, $h_src, $ext) = getimagesize($file_src);  // create new dimensions, keeping aspect ratio
							switch ($ext)
								{case 1:   //   gif -> jpg
									$img_src = imagecreatefromgif($file_src);
									break;
								case 2:   //   jpeg -> jpg
									$img_src = imagecreatefromjpeg($file_src); 
									break;
								case 3:  //   png -> jpg
									$img_src = imagecreatefrompng($file_src);
									break;
							}
							if ($w_src>$h_src) {
								$new_width=$h_src;
								$new_height=$h_src;
							} else {
								$new_width=$w_src;
								$new_height=$w_src;
							}
							WideImage::load($img_src)->autocrop(-20,0,1)->crop('center','middle',$new_width,$new_height)->saveToFile($filename);

							setCookie( "mid", $mid, time() + 3600*24*30, "/");
							setCookie( "mobile", $mobile, time() + 3600*24*30, "/" );
							setCookie( "long_code", $long_code, time() + 3600*24*30, "/" );
							setCookie( "img", $filename_1, time() + 3600*24*30, "/");
							setCookie( "login", $login, time() + 3600*24*30, "/");
							setCookie( "gender", $gender, time() + 3600*24*30, "/");
							setCookie( "name", $me['first_name'], time() + 3600*24*30, "/");
							
							$c->close();	
					?>
					<script>
						location.href='chat.php'
					</script>
					<?
				} else {
					?>
					<script>
						location.href='index.php?err=We found your facebook profile and can use it to complete your registration. We just need a few more pieces of information to complete your profile&fb_str=<?=$photo?>'
					</script>
					<?
				}
			} catch (Exception $e) {
				?>
					<script>
						location.href='login.php?err=System Error'
					</script>
				<?
			}
ob_flush();
?>