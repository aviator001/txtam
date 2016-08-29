<?php
/*
*  Author: Gautam Sharma
*  Company: finggr.com
*  Website: www.finggr.com
*  File: sms.class.php
*  June 1, 2014
*
*  This software is sold as-is without any warranties, expressed or implied,
*  including but not limited to performance and/or merchantability. No
*  warranty of fitness for a particular purpose is offered. This script can
*  be used on as many servers as needed, as long as the servers are owned
*  by the purchaser. (Contact us if you want to distribute it as part of
*  another project) The purchaser cannot modify, rewrite, edit, or change any
*  of this code and then resell it, which would be copyright infringement.
*  This code can be modified for personal use only.
*
*  Comments, Questions? Contact the author at GS AT XTUE DOT COM
*/
error_reporting(0);
include("aes_enc.class.php");
include("aesctr.class.php");
include("mobile.class.php");
$USER_ID = 'guatam@strikeiron.com';
$PASSWORD = 'Strike1';
$WSDL = 'http://ws.strikeiron.com/SMSAlerts4?WSDL';
$client = new SoapClient($WSDL, array('trace' => 0, 'exceptions' => 0));
$registered_user = array("RegisteredUser" => array("UserID" => $USER_ID,"Password" => $PASSWORD));
$header = new SoapHeader("http://ws.strikeiron.com", "LicenseInfo", $registered_user);
$client->__setSoapHeaders($header);

class sms {
	public function show_errors($state=TRUE) {
		if ($state) {
			ini_set("error_reporting", 1);
			error_reporting(E_ALL); 
			ini_set("display_errors", 1); 
			ini_set("display_errors", "On"); 
		} else {
			ini_set("error_reporting", 0);
			error_reporting(E_NONE); 
			ini_set("display_errors", 0); 
		}
	}

		function show($arrBigObj) {
			echo "<pre>";
				print_r($arrBigObj);
			echo "</pre>";
		}

		function format_sms($objSMS) {
			return ' (' . substr($objSMS,1,3) . ') ' . substr($objSMS,4,3) . ' - ' . substr($objSMS,7,4);
		}

	public function set($name, $value)
	{
		$GLOBALS[$name] = $value;
	}

	public function get($name)
	{
		return $GLOBALS[$name];
	}

	public function getNewNumber($mobile) {
		$mobile=$this->isValidMobile($mobile);
		$result=$this->query("select long_code from sms_long_codes where mobile = '$mobile'");
		if (!$result) {
			$result=$this->query("select long_code from sms_long_codes where mobile = '' order by id asc limit 1");
		}
			$objSMS=trim($result[0]['long_code']);
			$this->insert("update sms_long_codes set mobile='$mobile' where long_code='$objSMS'");
			return $objSMS . '|' . $this->isValidMobile($mobile) . '|' . '(' . substr($objSMS,1,3) . ') ' . substr($objSMS,4,3) . ' - ' . substr($objSMS,7,4);
		
	}
  
	public function getCallerID($m) {
		$USER_ID = 'guatam@strikeiron.com';
		$PASSWORD = 'Strike1';
		$phoneNumber = $m;
		$WSDL = 'http://ws.strikeiron.com/PhoneandAddressAdvanced?WSDL';
		$client = new SoapClient($WSDL, array('trace' => 1, 'exceptions' => 1));
		$registered_user = array("RegisteredUser" => array("UserID" => $USER_ID,"Password" => $PASSWORD));
		$header = new SoapHeader("http://ws.strikeiron.com", "LicenseInfo", $registered_user);
		$client->__setSoapHeaders($header);
		$params = array("PhoneNumber" => $phoneNumber);
		$result = $client->__soapCall("ReverseLookupByPhoneNumber", array($params), null, null, $output_header);
		return $name = $result->ReverseLookupByPhoneNumberResult->ServiceResult->FullName;
	}
  
  public function sms($to, $from, $message) {
		global $client;
		$params = array("ToNumber" => $to, "FromName" => $from, "MessageText" => $message);
		$result = $client->__soapCall("SendMessage", array($params), null, null, $output_header);
	}


	function isValidMobile($mob) {
		$num = trim($mob);
		$arr_a = array("-","."," ","(",")");
		$arr_b = array("","","","","");
		$num = str_replace($arr_a, $arr_b, $num);

		if ((strlen($num) < 10) || (strlen($num) > 11) || (substr($num,0,1)=='0') || (substr($num,1,1)=='0') || ((strlen($num)==10)&&(substr($num,0,1)=='1'))||((strlen($num)==11)&&(substr($num,0,1)!='1'))) return false;
		$num = (strlen($num) == 11) ? $num : ('1' . $num);	
		
		if ((strlen($num) == 11) && (substr($num, 0, 1) == "1")) {
			return $num;
		} else {
			return false;
		}
	}


	function formatMobile($mob, $format=false) {
		$num = trim($mob);
		$arr_a = array("-","."," ","(",")");
		$arr_b = array("","","","","");
		$num = str_replace($arr_a, $arr_b, $num);

		if ((strlen($num) < 10) || (strlen($num) > 11) || (substr($num,0,1)=='0') || (substr($num,1,1)=='0') || (substr($num,1,1)=='1')) return false;
		$num = (strlen($num) == 11) ? $num : ('1' . $num);	
		
		if ((strlen($num) == 11) && (substr($num, 0, 1) == "1")) {
			if($format) {
				$num = "(" . substr($num,1,3) . ") " . substr($num,4,3) . "-" . substr($num,7,4); 
			}
			return $num;
		} else {
			return false;
		}
	}	

	public function connect() {
			global $db;
			try {
				global $conn;
				$db = new mysqli("199.91.65.82", "gautamadmin", "Shadow2015!", "finggr");
				$this->conn = $db;
				return $this->conn = $db;
			} catch (Exception $e) {
				return "Unable to connect";
				exit;
			}
	}

		public function close() {
		try {
			$this->conn->close();
			return "Closed";
		} catch (Exception $e) {
			return "Unable to Close";
			exit;
		}
	}

	public function sql($sql) {
		return $this->_sql($sql);
	}

	public function insert($sql) {
		return $this->_insert($sql);
	}

	public function query($sql) {
		return $this->_sql_arr($sql);
	}
	protected function _sql($sql) {
		global $result;
		try {	
				$db=$this->conn;
				if($this->result = $db->query($sql)){
					return $this->result;
				} else {
					return null;
				}
			} catch (Exception $e) {
			return null;
		}
	}

	protected function _insert($sql) {
		global $result;
		try {	
				$db=$this->conn;
				if($db->query($sql)){
					return $db->insert_id;
				} else {
					return null;
				}
			} catch (Exception $e) {
			return null;
		}
	}

	protected function _update($sql) {
		global $result;
		try {	
				$db=$this->conn;
				if($db->query($sql)){
					return $db->insert_id;
				} else {
					return null;
				}
			} catch (Exception $e) {
			return null;
		}
	}

	protected function _sql_arr($sql) {
		global $dbs;
		global $row;
		global $rowset;
		$arr = array();
		try {
				$db=$this->conn;
			try {
					if($r = $db->query($sql)){
						while ($row =  $r->fetch_assoc()) {
							$arr[] = $row;
						}
						$this->rowset = $arr;
						$r->free();
						return $this->rowset;
					} else {
							return null;
					}
				} catch (Exception $e) {
					return null;
				}
			return $res;
		} catch (Exception $e) {
			return "Unable to connect";
			exit;
		}
	}
	
	function isValidMobile($mob) {
		$num = trim($mob);
		$arr_a = array("-","."," ","(",")");
		$arr_b = array("","","","","");
		$num = str_replace($arr_a, $arr_b, $num);

		if ((strlen($num) < 10) || (strlen($num) > 11)) return false;
		$num = (strlen($num) == 11) ? $num : ("1$num");	

		if ((strlen($num) == 11) && (substr($num, 0, 1) == "1")) {
			return $num;
		} else {
			return false;
		}
	}

	public function get_long_codes($to, $from) {
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

	public function encrypt_message($to_real_number, $from_real_number, $raw_key, $raw_msg, $return_codes=false, $return_link=false) {
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
		$lcs=$this->get_long_codes($to_real_number,$from_real_number);
		$to_long_code=$lcs[0];
		$from_long_code=$lcs[1];
		$lc=json_encode($lcs);
		mailme($lc);

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
		sms_notify($to_real_number, $link, $from_long_code);
		if ($return_codes===true) return $lc;
		if ($return_link===true) return $link;
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
}