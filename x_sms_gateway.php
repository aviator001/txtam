<?
	/* * * * * * * * * * * * * * * * * * * * * * 
	 Copyright 2014 ALL RIGHTS RESERVERD!
    --------------------------------------------
		1.	AUTHOR		:	Gautam Sharma
		2.	COMPANY		:	shadowSMS
		2.	APP			:	shadowsms.com
		3.	MODULE		:	SMS Gateway
		4.	PAGE		:	x_sms_gateway.php
		5.	DESCRIPTION	:	SMS Relay Services
		6.	DATE		:	July 5th 2014
		7.	EMAIL		:	xtue.web@gmail.com
	--------------------------------------------
	**/
	include("inc/utils.class.php");
	$utils = new utils;
	$utils->connect();
	$utils->show_errors(0);
	$host=$_SERVER['SERVER_NAME'];
	
	$recycle_long_codes = true;
	$num_anon = "13234581949";
	$num_encp = "13234581949";
	$default_pswd = '123456';
	$key = date('YmdH');
	$user_token = substr(abs(rand(1111,9999)),0,4).substr(abs(rand(1111,9999)),0,4).substr(abs(rand(1111,9999)),0,4).substr(abs(rand(1111,9999)),0,4);
	$bin_user_token = $utils->strToBin($user_token);
	$aes_user_token = $utils->encrypt($bin_user_token, $key);
	
	$USER_ID = 'guatam@strikeiron.com';
	$PASSWORD = 'Strike1';
	$WSDL = 'http://ws.strikeiron.com/SMSAlerts4?WSDL';
	$client = new SoapClient($WSDL, array('trace' => 0, 'exceptions' => 0));
	$registered_user = array("RegisteredUser" => array("UserID" => $USER_ID,"Password" => $PASSWORD));
	$header = new SoapHeader("http://ws.strikeiron.com", "LicenseInfo", $registered_user);
	$client->__setSoapHeaders($header);
	
	$x=stripslashes($_POST['XMLDATA']);
	mailme($x);
	$xml = simplexml_load_string($x);
	$arr = json_decode(json_encode($xml),true);
	$data = $arr[MessageList][Message];
	$date=date('Y-m-d H:i:s');
	$avatar = "default_avatar.png";
	$id = $data[MessageID];
	$refTag=$data[OriginalReferenceTag];
	$country_code = '1';
	$member_id_s = '';
	$member_id_r = '';
	$free_sms_count = 10;
	$a_r1=array('(',')','-',' ');
	$a_r2=array('','','','');
	$to_long_code = trim($data[ToNumber]);
	$to_long_code = str_replace($a_r1,$a_r2,$to_long_code);
	$from_real_number = trim($data[FromNumber]);
	$from_real_number = str_replace($a_r1,$a_r2,$from_real_number);
	$system_to_long = $to_long_code;
	$sms_message = trim($data[MessageText]);
	$from_real_number=$utils->isValidMobile($from_real_number);
		if (strstr($sms_message,'##')) {
			$msg=str_replace('##','',$sms_message);
			$key=trim(file_get_contents("/home/smsgone/f/".$from_real_number."_pwd"));
			$to=trim(file_get_contents("/home/smsgone/f/".$from_real_number."_mob"));
			mailme("encrypt_message('$to','$from_real_number','$key','$msg');");
			encrypt_message($to,$from_real_number,$key,$msg);
			$str="Congratulations. Your encrypted message was sent succesfully";
			$params = array("ToNumber" => $from_real_number, "FromName" => $to_long_code, "MessageText" => $str);
			$result = $client->__soapCall("SendMessage", array($params), null, null, $output_header);
			exit;
		}
		if (strstr($sms_message,'**')) {
			$sms_message=str_replace('**','',$sms_message);
				$str="Great! Now enter the message you want encrypted and sent. Start message with 2 '#' symbols, for e.g., ##my secret message goes here";
				$file = "/home/smsgone/f/".$from_real_number."_pwd";
				file_put_contents($file, $sms_message);
				$params = array("ToNumber" => $from_real_number, "FromName" => $to_long_code, "MessageText" => $str);
				$result = $client->__soapCall("SendMessage", array($params), null, null, $output_header);
				exit;
		}
		if (strstr($sms_message,'@@encrypt')) {
				$to=trim(str_replace("@@encrypt","",$sms_message));
				$file = "/home/smsgone/f/".$from_real_number."_mob";
				file_put_contents($file, $to);
				$from=$from_real_number;
				$str="Looks like you want to send an encrypted message. Ok. First, you need to create a passphrase that b with '**'. E.g., '**myPhrase'. Reply with passphrase now.";
				$params = array("ToNumber" => $from_real_number, "FromName" => $to_long_code, "MessageText" => $str);
				$result = $client->__soapCall("SendMessage", array($params), null, null, $output_header);
				exit;
			}
		if (strstr($sms_message,'food')) {
			$sql="SELECT * from menu_detail WHERE ";
			$sms_message=str_replace('@@','',$sms_message);
			$sms_message=str_replace('search','',$sms_message);
			$sms_message=str_replace('food','',$sms_message);
			$sms_message=trim($sms_message);
			if (strstr($sms_message,' ')) {
				$sms_message=explode(' ', $sms_message);
			} else if (strstr($sms_message,',')) {
				$sms_message=explode(',', $sms_message);
			} else if (strstr($sms_message,'+')) {
				$sms_message=explode('+', $sms_message);
			} else if (strstr($sms_message,'and')) {
				$sms_message=explode('and', $sms_message);
			}
			for ($i=0;$i<=count($sms_message)-1;$i++){
				$sql .= "item_desc like '%".trim($sms_message[$i])."%' AND ";
			}
			$sql = trim(substr($sql,0,strlen($sql)-4)) . ' LIMIT 1';
			$res=$utils->query($sql);
			foreach ($res as $row) {
				$str .= strtoupper($row['item_name']);
				$str .= " - ** $" . $row['item_price'] . "**\r\n";
				$str .= substr($row['item_desc'],0,50) . "\r\n";
				$str .= strtoupper($row['store_name']) . "\r\n";
				$str .= str_replace("<br>","\r\n", $row['store_address']) . "\r\n";;
			}
			$params = array("ToNumber" => $from_real_number, "FromName" => $to_long_code, "MessageText" => $str);
			$result = $client->__soapCall("SendMessage", array($params), null, null, $output_header);
			exit;
		}
	
	if ($to_long_code==$num_anon) {
		$to=extract_number_from_message($sms_message)[0];
		$from=$from_real_number;
		$message=extract_number_from_message($sms_message)[1];
		$prepend="To start texting $to anonymously, just start typing and click 'Send' right here. Your number will always remain hidden. ";
	}	else {
		$to_long_code=$utils->isValidMobile($to_long_code);
		$from=$from_real_number;
		$message=$sms_message;
		$to=$utils->query("select mobile from sms_long_codes where long_code = '$to_long_code'")[0]['mobile'];
		if (!$to) {
			$to=$utils->query("select sender from sms_tracker where long_code = '$to_long_code'")[0]['sender'];
		}
	}
	$c=$utils->query("select id from sms_logs where to='$to', from='$from' and message='$sms_message'");
	if (!empty($c[0]['id'])) {
		echo 'Duplicate Message';
		exit;
	}
 	$utils->insert("INSERT INTO `sms_logs` VALUES ('NULL','$to', '$from', '$sms_message', '".date('Y-m-d H:i:s')."','".json_encode($data)."','$id','$refTag')");
	if ((!$utils->isValidMobile($to_long_code))||(!$utils->isValidMobile($from_real_number))||!$to) {
		echo "FAIL: Invalid Number(s)";
		exit;
	}
		//	mailme(json_encode($data));
			//User Initiating a message
			if ($to_long_code == $num_anon) {
				//User initiates a message to a another user anonymously
				$r = $utils->query("select long_code from sms_long_codes where mobile = '$from_real_number'");
				//If this is a first time sender, auto-register the user and give him 10 free credits
				if (empty($r[0][long_code])) {
					//New User
					$mobile_number = $utils->isValidMobile($from_real_number);
					$q = $utils->query("select long_code from sms_long_codes where mobile = '$mobile_number'");
					if (empty($q[0][long_code])) {
						//Auto Register user
						$from_long_code=$utils->query("select long_code from sms_long_codes where mobile='' and long_code not in (select long_code from sms_tracker) order by id asc limit 1")[0]['long_code'];		
						$utils->insert("UPDATE `sms_long_codes` SET `mobile`='$mobile_number', `active`='1' WHERE (`long_code`='$from_long_code') AND (`mobile`='')");
					} else {
						$from_long_code=$q[0][long_code];
					}
				} else { 
					//Existing User
					$from_long_code = $r[0][long_code];
				}

				$to_real_number = $utils->isValidMobile($to);
				$sms_message = $message;

				//Check if recepient is a previously registered user? If so, we just use his previously assigned long_code
				$r = $utils->query("select long_code from sms_long_codes where mobile = '$to_real_number'");

				if (count($r) < 1) {
					//Recepient is NOT a registered user
					//$to_long_code = randomly assigned long_code with requirements below;
					
					//RULE 1: it should not have been used to send the originator a message in the past.
					//RULE 2: it should not belong to a registered member (that is, exists in the sms_long_codes table)

					//Therefore:
					//Look in the sms_tracker table first to see if sender has sent the recepient a message before?
					// AND 
					//Also Look if a long code has already been used to talk to sender in the past?
					$t=$utils->query("select long_code from sms_tracker where mobile='$from' and sender='$to_real_number' order by id asc limit 1");
					if (empty($t[0]['long_code'])) {
						//Never communicated before!
						//Find a temp long code that meets above requirements
						$to_long_code=$utils->query("select long_code from sms_long_codes where mobile='' and long_code not in (select long_code from sms_tracker where mobile='$from') order by id asc limit 1")[0]['long_code'];
						//Insert it in the sms_tracker table for future lookups
						$utils->insert("INSERT into sms_tracker VALUES('NULL','$from','$to_long_code','$to_real_number')");
					} else {
						//They have spoken before!
						$to_long_code=$t[0]['long_code'];
					}	
				} else {
					//Recepient is a registered user
					$to_long_code = $r[0][long_code];
				}

				//Send initial/prepend message to sender as reminder/thread starter
				$params = array("ToNumber" => $from, "FromName" => $to_long_code, "MessageText" => $prepend . 'Thats it. Power to the People!');
				$result = $client->__soapCall("SendMessage", array($params), null, null, $output_header);
		
				//send real message to the intended original recepient
				$params = array("ToNumber" => $to, "FromName" => $from_long_code, "MessageText" => $sms_message);
				$result = $client->__soapCall("SendMessage", array($params), null, null, $output_header);
						
			} else {
				/********************** PROCESS MESSAGES BETWEEN REGISTERED SENDER AND RECEIVER ***************************/
				/*
				We have the 'to' number as a guaranteed long_code and we also know that its not the anon trigger number.
				Which means that its either:

				1. A previously registered user who alraedy has registered for this service in the past - that is, users mobile can be found in the sms_long_codes table
					ACTION: 
						1.	TO [XML]: WILL BE THE INTENDED RECEPIENT'S REGISTERED LONG_CODE
						2.	FROM_LONG_CODE: THIS MUST BE SET TO THE USER'S REGISTERED LONG_CODE FOUND IN THE SMS_LONG_CODES TABLE
				2. An un-registered user sending a message to a registered user as a REPLY - that is, users mobile can be found in the sms_tracker table
					ACTION:
						1.	TO [XML]: WILL BE THE INTENDED RECEPIENT'S REGISTERED LONG_CODE
						2.	FROM_LONG_CODE: THIS MUST BE SET TO THE SECONDARY USER'S TEMP LONG_CODE FOUND IN THE SMS_TRACKER TABLE
				3. An un-registered user sending a message to a registered user for the FIRST time (Eg., responding to number found on an ad online) that is, users mobile can NOT be found in the sms_tracker table
					ACTION:
						1.	TO [XML]: WILL BE THE INTENDED RECEPIENT'S REGISTERED LONG_CODE
						2.	FROM_LONG_CODE: MUST BE TEMPORARILY ASSIGINED BASED ON PREVIOUS RULES AND MUST BE INSERTED IN THE SMS_TRACKER TABLE
							2.1		RULE 1: it should not have been used to send the originator a message in the past.
							2.2		RULE 2: it should not belong to a registered member (that is, exists in the sms_long_codes table)
				*/
				
				$q1=$utils->query("select mobile, long_code from sms_long_codes where mobile = '$to'");
				$q2=$utils->query("select sender, mobile, long_code from sms_tracker where sender = '$to'");
				
				//need to compare new users real number + long code sending to, in order to find real recepient
				
				if (count($q1)>0) {
					//	SCENARIO 1: LOOKUP REGISTERED
					$r=$utils->query("select mobile from sms_long_codes where long_code = '$to_long_code'");
					if ($r) {
						$toNumber=$r[0]['mobile'];
					} else {
						$toNumber=$utils->query("select mobile from sms_tracker where sender = '$to_long_code'")[0]['mobile'];
					}
					
					$f=$utils->query("select long_code from sms_long_codes where mobile='$from'");
					if (!empty($f[0]['long_code'])) {
						$from_long_code=$f[0]['long_code'];
					} else {
						$from_long_code=$utils->query("select long_code from sms_tracker where mobile='$to' and sender='$from' limit 1")[0]['long_code'];
					}
					$params = array("ToNumber" => $toNumber, "FromName" => $from_long_code, "MessageText" => $sms_message);
					$result = $client->__soapCall("SendMessage", array($params), null, null, $output_header);
					exit;
				}
				if (count($q2)>0) {
					//	SCENARIO 2: LOOKUP TEMP REGISTERED
					$r=$utils->query("select mobile from sms_long_codes where long_code = '$to_long_code'");
					if (!empty($r[0]['mobile'])) {
						$toNumber=$r[0]['mobile'];
					} else {
						$qn=$utils->query("select * from sms_tracker where long_code = '$to_long_code' and mobile='$from' order by id asc limit 1");
						$tn=$qn[0]['sender'];
						$toNumber=$tn;
					}
					//	$from_long_code=$q2[0]['long_code'];
					$f=$utils->query("select long_code from sms_long_codes where mobile='$from'");
					if (!empty($f[0][long_code])) {
						$from_long_code=$f[0]['long_code'];
					} else {
						$from_long_code=$utils->query("select long_code from sms_tracker where mobile='$to' and sender='$from' limit 1")[0]['long_code'];
					}
					//	Send!
					$params = array("ToNumber" => $toNumber, "FromName" => $from_long_code, "MessageText" => $sms_message);
					$result = $client->__soapCall("SendMessage", array($params), null, null, $output_header);
						mailme("SN2: to->$to :: from->$from :: to_long_code->$to_long_code :: from_long_code->$from_long_code :: message->$sms_message");
				} else {
					//	SCENARIO 3: GENERATE TEMP REGISTRATION
					$r=$utils->query("select mobile from sms_long_codes where long_code = '$to'");
					$toNumber = $r[0]['mobile'];
					
					//GENERATE A TEMP LONG CODE FOR USER
					$from_long_code=$utils->query("select long_code from sms_long_codes where mobile='' and long_code not in (select long_code from sms_tracker where mobile='$toNumber') order by id asc limit 1")[0]['long_code'];

					//Insert it in the sms_tracker table for future lookups
					$utils->insert("INSERT into sms_tracker VALUES('NULL','$from','$from_long_code','$toNumber')");

					//Send!
					$params = array("ToNumber" => $toNumber, "FromName" => $from_long_code, "MessageText" => $sms_message);
					$result = $client->__soapCall("SendMessage", array($params), null, null, $output_header);
				}
			}

		function get_long_codes($to, $from) {
			global $utils;
			$from_real_number = $utils->isValidMobile($from);
			$q = $utils->query("select long_code from sms_long_codes where mobile = '$from_real_number'");
			if (empty($q[0][long_code])) {
				$from_long_code=$utils->query("select long_code from sms_long_codes where mobile='' and long_code not in (select long_code from sms_tracker) order by id asc limit 1")[0]['long_code'];		
				$utils->insert("UPDATE `sms_long_codes` SET `mobile`='$from_real_number', `active`='1' WHERE (`long_code`='$from_long_code') AND (`mobile`='')");
			} else {
				$from_long_code=$q[0][long_code];
			}

		$to_real_number = $utils->isValidMobile($to);
		$r = $utils->query("select long_code from sms_long_codes where mobile = '$to_real_number'");
		if (count($r) < 1) {
			$t=$utils->query("select long_code from sms_tracker where mobile='$from_real_number' and sender='$to_real_number' order by id asc limit 1");
			if (empty($t[0]['long_code'])) {
				$to_long_code=$utils->query("select long_code from sms_long_codes where mobile='' and long_code not in (select long_code from sms_tracker where mobile='$from_real_number') order by id asc limit 1")[0]['long_code'];
				$utils->insert("INSERT into sms_tracker VALUES('NULL','$from_real_number','$to_long_code','$to_real_number')");
			} else {
				$to_long_code=$t[0]['long_code'];
			}	
		} else {
			$to_long_code = $r[0][long_code];
		}
		return array($to_long_code, $from_long_code);
	}

	function output_SendMessage_result( $svcResult ) {
		$b .=  $svcResult->ToNumber . ',';
		$b .=  $svcResult->Ticket . ',';
		$b .=  $svcResult->StatusExtra . ',';
		$b .=  $svcResult->WelcomeMessageSent . ',';
		return $b;
	}
	
	function extract_number_from_message($message) {
		$a1 = array('+','-','(',')','.');
		$a2 = array('','','','','');
		$message = trim(str_replace($a1, $a2, trim($message)));
		if (substr($message,0,1)=="1") {
			$message = trim(str_replace(' ', '', trim($message)));
		}
		$match = is_numeric(substr($message,0,11)) ? substr($message,0,11) : (is_numeric(substr($message,0,10)) ? substr($message,0,10) : (str_replace(' ','',substr(trim($message),0,11))));
		$message=str_replace($match,"",$message);
		return Array($match, $message);
	}
	
	function extract_key_from_message($message) {
		$a1 = array('+',' ','-','(',')','.');
		$a2 = array('','','','','','');
		$message = str_replace($a1, $a2, trim($message));
		$key_msg = strAB('[', ']', $message, true);
		$key = $key_msg[0];
		$msg = $key_msg[1];
		return Array($key, $msg);
	}
		
	function strAB($a, $b, $str, $destructive=false) {
		if ($destructive) {
			$key = substr($str, (strPos($str, $a) + strlen($a)), (strPos($str, $b) - (strPos($str, $a) + strlen($a))));
			$key_str = '[' . $key . ']';
			$str_min = trim(str_replace($key_str, '', $str));
			$arr = array($key, $str_min);
		} else {
			$key = substr($str, (strPos($str, $a) + strlen($a)), (strPos($str, $b) - (strPos($str, $a) + strlen($a))));
			$arr = array($key, $str);
		}
		return $arr;
	}
	
	function encrypt_message($to_real_number, $from_real_number, $raw_key, $raw_msg) {
		global $utils;
		$arr_rep1 = array('[',']','"',"'");
		$arr_rep2 = array('(',')','','');
		$raw_msg = str_replace($arr_rep1, $arr_rep2, $raw_msg);
		$a = range('A','Z');
		$n = range(0,9);
		$s1= array(':)',':(',":'(",':-|',':{',':x',':-)',':-(');
		$s=array('{11}','{12}','{13}','{14}','{15}','{16}','{11}','{12}');
		$p1 = array(' ','!','@','#','$','%','&','*','(',')','_','+','-','=','[',']','','','|',':',';','"',"'",'.',',','?','<','>','^','~','/');
		$p = array('{SP}','{EX}','{AT}','{HS}','{DL}','{PC}','{AM}','{ST}','{BL}','{BR}','{US}','{PS}','{MN}','{EQ}','{SL}','{SR}','','','{PI}','{CN}','{SN}','{QD}','{QS}','{PD}','{CM}','{QM}','{LT}','{GT}','{CT}','{SG}','{FS}');
		$raw_msg=str_replace($s1,$s,$raw_msg);
		$raw_msg=str_replace($p1,$p,$raw_msg);
		$arr_a = array_merge($a, $n, $p, $s);
		
		$to_real_number = $utils->isValidMobile($to_real_number);
		$from_real_number = $utils->isValidMobile($from_real_number);
		$lcs=get_long_codes($to_real_number,$from_real_number);
		$to_long_code=$lcs[0];
		$from_long_code=$lcs[1];
		mailme(json_encode($lcs));

		$user_token = substr(abs(rand(1111,9999)),0,4).substr(abs(rand(1111,9999)),0,4).substr(abs(rand(1111,9999)),0,4).substr(abs(rand(1111,9999)),0,4);
		$bin_user_token = $utils->strToBin($user_token);
		$alpha = "";
		$dir=$bin_user_token;
		$src = "../img";
		$dst = "xsr/$dir";
		mkdir("xsr/$dir", 0777, true);
		
		for ($i=1; $i <= count($arr_a); $i++) {
			$fn = substr(abs(rand(1111,6666)),0,4).'x'.substr(abs(rand(1111,6666)),0,4).'x'.substr(abs(rand(1111,6666)),0,4).'x'.substr(abs(rand(1111,6666)),0,4);
			$f = '"'.$fn.'"';
			$f3 = "$dst/$fn.png";
			$f_b = '"'.$f3.'"';
			copy("$src/z$i.png", "$dst/$fn.png");
			$img_arr[$arr_a[$i-1]] = $fn;
		}
		for ($i=0; $i < strlen($raw_msg); $i++) {
			if (substr(strtoupper($raw_msg), $i, 1)=="{") {
				$alpha .= $img_arr[substr(strtoupper($raw_msg), $i, 4)].'|';
				$i=$i+3;
			} else {
				$alpha .= $img_arr[substr(strtoupper($raw_msg), $i, 1)].'|';
			}
		}
		
		$alpha = substr($alpha,0,strlen($alpha)-1);
		$message = $utils->encrypt(($alpha), $raw_key, 256);

		$file = "/home/smsgone/f/".$bin_user_token;
		file_put_contents($file, $message);
		$link = "http://smsgone.com/x/?i=".$bin_user_token;
		mailme($link);
		sms_notify($to_real_number, $link, $from_long_code);
	}	

	function sms_notify($mobile, $system_message, $from = 'ShadowSMS') {
		global $client;
		$params = array("ToNumber" => $mobile, "FromName" => $from, "MessageText" => $system_message);
		$result = $client->__soapCall("SendMessage", array($params), null, null, $output_header);
	}

	function mailme($str) {
		mail('xtue.web@gmail.com','',$str,"From: ShadowSMS Debug <sms@finggr.com>");
	}

	function textme($str) {
		mail('3105676686@messaging.sprintpcs.com','',$str,"From: Gautam Sharma <3105676686@messaging.sprintpcs.com>");
	}
	
	
?>