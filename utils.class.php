<?
require "aes_enc.class.php";
require "aesctr.class.php";

class ImageKick {
    private $Imagick = null;

    public function __construct($url = null){
        return $this->Imagick = new Imagick($url);
    } 

    public function __call($n,$p){
//		return $this->Imagick = call_user_func_array($n,$p);
		echo $n;
		echo call_user_func_array($n,$p);
    }
}

class utils {
	/**
	 * php extension
	 * @var	string
	 */
	protected $debug;
	protected $result;
	protected $r;
	public $record;
	public $secrets;
	public $logins;
	public $d;
	public $key;
	
	public function set_key($val) {
		$key=$this->key;
		$key[]=$val;
		$this->key=$key;
	}
	/**
	 * Constructs a new instance of UTILS class.
	 */
	public function __construct ($ip='') {
		error_reporting(0);
		ini_set('display_errors',1);
		
		$f="/home/gaysugar/settings";
		try {
				$iniSet = fopen($f, "r");
				while(!feof($iniSet)) {
					$xln=fgets($iniSet);
					$str .= "&".$xln;
					$v=split("=",$xln);
					${$v[0]}=$v[1];
					global $_CONSTANTS;
					$_CONSTANTS[$v[0]]=$v[1];
				}
				
				foreach($_CONSTANTS as $key => $val)
					define(trim($key), trim($val));

				parse_str($str);
				parse_str($str,$arr);
				$this->secrets=$arr;
				$this->logins=$Server;

				fclose($iniSet);
				
		} catch (Exception $e) {
			
		}
	}

	public function show_errors($obj=FALSE) {
		if ($obj===TRUE) {
			ini_set("error_reporting", 1);
			error_reporting(E_ALL); 
			ini_set("display_errors", 1); 
			ini_set("display_errors", "On"); 
		} else {
			ini_set("error_reporting", 0);
			ini_set("display_errors", 0); 
			ini_set("display_errors", "Off"); 
			
		}
	}

	public function shell($cn, $fn){
		$stream = ssh2_exec($cn, $fn);
		stream_set_blocking($stream, true);
		return stream_get_contents($stream);		
	}
		
	public function logs($x) {
		global $secrets;
		$d[]=$x;
		$this->logins=$d;
	}
	
	public function connect() {
			global $db;
			try {
				global $conn;
//				$db = new mysqli("199.91.65.85", "gautamadmin", "Shadow2015!", "gaysugardaddyforme");
				$db = new mysqli("199.91.65.82", "root", "Shadow2015!", "finggr");
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

	protected function _ip() {
		$client  = @$_SERVER['HTTP_CLIENT_IP'];
		$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
		$remote  = $_SERVER['REMOTE_ADDR'];
		if(filter_var($client, FILTER_VALIDATE_IP))
		{
			$ip = $client;
		}
		elseif(filter_var($forward, FILTER_VALIDATE_IP))
		{
			$ip = $forward;
		}
		else
		{
			$ip = $remote;
		}
		return $ip;
	}

	public function getPort() {
		setCookie("port", WS_PORT, time()+3600*10, "/");
		return WS_PORT;
	}
	
	public function getIP() {
		$client  = @$_SERVER['HTTP_CLIENT_IP'];
		$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
		$remote  = $_SERVER['REMOTE_ADDR'];
		$result  = "Unknown";
		if(filter_var($client, FILTER_VALIDATE_IP))
		{
			$ip = $client;
		}
		elseif(filter_var($forward, FILTER_VALIDATE_IP))
		{
			$ip = $forward;
		}
		else
		{
			$ip = $remote;
		}
		return $ip;
	}

	public function getCitySateFromIP($ip='') {
		$result  = "Unknown";
		if ($ip=='') $ip=$this->_ip();
		$ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));
		if($ip_data && $ip_data->geoplugin_countryName != null)	{
			$result = $ip_data->geoplugin_city . ", " . $ip_data->geoplugin_region;
		}
		return $result;
	}

	public function getLatLngFromIP($ip='') {
		$result  = "Unknown";
		if ($ip=='') $ip=$this->_ip();
		$ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));
		if($ip_data && $ip_data->geoplugin_countryName != null)	{
			$result = $ip_data->geoplugin_latitude . ", " . $ip_data->geoplugin_longitude;
		}
		return $result;
	}

	public function icrop($filename) {
		list($current_width, $current_height) = getimagesize($filename);
		$left = 0;
		$top = 0;
		$crop_width = 200;
		$crop_height = 200;
		$canvas = imagecreatetruecolor($crop_width, $crop_height);
		$current_image = imagecreatefromjpeg($filename);
		imagecopy($canvas, $im, 0, 0, $left, $top, $current_width, $current_height);
		imagejpeg($canvas, $filename, 100);
	}

	protected function imagecolorf($im,$co){ 
		$height = imagesy($im); 
		$width = imagesx($im); 
		for($x=0; $x<$width; $x++){ 
			for($y=0; $y<$height; $y++){ 
				$rgb = ImageColorAt($im, $x, $y); 
				$r = ($rgb >> 16) & 0xFF; 
				$g = ($rgb >> 8) & 0xFF; 
				$b = $rgb & 0xFF; 
				$c=($r+$g+$b)/3; 
					
		if ($co=="G") if($g<$r || $g<$b+20){$r=$c;$g=$c; $b=$c;}//leaves only green 
		if ($co=="B") if($b<$r || $b<$g){$r=$c;$g=$c; $b=$c;}//only blue 
		if ($co=="R") if($r<$g+30 || $r<$b){$r=$c;$g=$c; $b=$c;}//only red 
		if ($co=="Y") if($r<$g-1 || $r>$g+60 || $b>$g-50){$r=$c;$g=$c; $b=$c;}//only yellow 
					
		imagesetpixel($im, $x, $y,imagecolorallocate($im, $r,$g,$b)); 
				} 
			} 
		} 

	public function adjustImage($filename1,$mode=0, $annotate="No Text Given!") {
		require 'instagraph.php';
		require("/home/txt/public_html/assets/wi/WideImage.php");
		$filename="/home/txt/public_html/i/" . $filename1;
		$filename_p="/home/txt/public_html/i/" . rand(1111,9999) . $filename1;
		$filename_o="/home/txt/public_html/i/original_" . $filename1;
		$filename_r="i/" . $filename1; 
		$im=WideImage::load($filename);
		if (!getimagesize("/home/txt/public_html/i/original_" . $filename1)) $im->saveToFile($filename_o);
		switch ( $mode ) {
			case "CCW":
						$img = new Imagick($filename);
						$img->rotateImage(new ImagickPixel(), 270); 
						break;
			case "CW":
						$img = new Imagick($filename);
						$img->rotateImage(new ImagickPixel(), 90); 
						break;
			case "MIR":
						$img = new Imagick($filename);
						$img->rotateImage(new ImagickPixel(), 180); 
						break;
			case "NEG":
						$img = new Imagick($filename);
						$img->negateImage(FALSE); 
				 		break;
			case "GREYSCALE":
						break;
			case "TRIM":
						$img = new Imagick($filename);
						$img->trimImage(0.5); 
						break;
			case "SHARPEN":
						$img = new Imagick($filename);
						$img->sharpenImage(3,5); 
						break;
			case "SEPIA":
						$img = new Imagick($filename);
						$img->sepiaToneImage(70);
						break;
			case "MBLUR":
						$img = new Imagick($filename);
						$img->motionBlurImage(50,30,30);
						break;
			case "OIL":
						$img = new Imagick($filename);
						$img->oilPaintImage( 10 ); 
						break;
			case "SOLARIZE":
						$img = new Imagick($filename);
						$img->solarizeImage( 30000 ); 
						break;
			case "EMB":
						$img = new Imagick($filename);
						$img->shadeImage(100,10,20); 
						break;
			case "CHAR":
						$img = new Imagick($filename);
						$img->charcoalImage(5,2); 
						break;
			case "EN":
						$img = new Imagick($filename);
						$img->enhanceImage();
						break;
			case "EQ":
						$img = new Imagick($filename);
						$img->equalizeImage();
						break;
			case "ED":
						$img = new Imagick($filename);
						$img->despeckleImage(); 
						break;
			case "E":
						$img = new Imagick($filename);
					    $img->radialBlurImage(10);
						$img->radialBlurImage(5);
						break;
			case "CONTRAST":
						$img = new Imagick($filename);
						$img->enhanceImage();
						break;
			case "ANNOTATE":
						$off=getimagesize($filename)[1]-50;
						$img = new Imagick($filename);
						$draw = new ImagickDraw();
						$pixel = new ImagickPixel( 'gray' );
						/* Black text */
						$draw->setFillColor('white');
						/* Font properties */
						$draw->setFont('Bookman-DemiItalic');
						$draw->setFontSize( 28 );
						/* Create text */
						$img->annotateImage($draw, 10, $off, 0, $annotate);
						break;
			case "RESET":
						$img=WideImage::load($filename_o)->saveToFile($filename);
						return 'i/' . explode('/',$filename)[(count(explode('/',$filename))-1)];
		
			case "RESET":
			   $instagraph = Instagraph::factory('i/J5mf8HRHm.jpg', 'i/J5mf8HRHm.jpg');
			   $instagraph->toaster(); // name of the filter
			}
		
		$img->writeImage($filename_p);
		$img->writeImage($filename);
		imagedestroy($im);
		return 'i/' . explode('/',$filename_p)[(count(explode('/',$filename_p))-1)];
	}

	public function fix($filename, $x=100) {
		require("/home/gaysugar/public_html/assets/wi/WideImage.php");
		$dir = "/home/gaysugar/public_html/photos/";
			$image=$dir.$filename;
			$targetFile = $dir."thumbs/".$filename;
			WideImage::load($image)->resize($x)->saveToFile($targetFile);
			$i="http://gaysugardaddyfinder.com/thumbs/".$filename;
			if (getimagesize($i)) return $i;
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
	public function setSession($mobile,$data) {
		error_reporting('E_NONE');
		$this->_insert("insert into sms_sessions values('NULL','$mobile','$data')");
		return "insert into sms_sessions values('NULL','$mobile','$data')";
	}
	
	public function getSession ($mobile) {
		return $this->query("select data from sms_sessions where mobile='$mobile'")[0]['data'];
	}
	
	public function send_mail($to, $from_login, $type, $subject='', $urgent='', $from='', $msg='', $attachments='',$header='', $footer='', $from_name='') {
		$str="http://gaysugardaddyfinder.com/email/send_mail.php?to_mid=$to&type=$type&from_login=$from_login&subject=$subject&msg=$msg&header=$header&footer=$footer&from=$from&from_name=$from_name&attachments=$attachments&x=" . rand(11111111,99999999);
		return file_get_contents($str);   
	}
	
	public function send_password_sms($mid) {
		$message=$this->login_info($mid);
		$this->sms($mid,'GaySD Admin',$message); 
	}

	public function sms($to, $from, $message) {
		echo file_get_contents("http://gaysugardaddyfinder.com/inc/x_send_api.php?to=$to&from=$from&message=$message");
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

	public function show($obj) {
		echo "<pre>";
			print_r($obj);
		echo "</pre>";
	}
	
	public function user_info($id) {
		$arr=$this->_sql_arr('select * from dt_members where id='.$id);
		return $arr;
	}

	public function user_login($id) {
		$arr=$this->_sql_arr('select login, filename_1, email, mobile, pswd from dt_members where id='.$id);
		return $arr[0];
	}

	public function login_info($mid) {
		$arr=$this->query("select email, login, pswd from dt_members where id='".$mid."'");
		$str = "\r\n\r\nPASS: " . $arr[0][pswd] . "\r\nLOGIN: " . $arr[0][login] . "\r\nEMAIL: " . $arr[0][email];
		return $str;
	}

	public function user_photo($id) {
		$arr=$this->_sql_arr('select filename_1 from dt_photos where member_id=$id');
		return array( "<img src='photos/" . $arr[filename_1] , $arr[filename_1] );
	}


	public function user_thumb($id, $size=30) {
		$arr=$this->_sql_arr('select filename_1 from dt_members where id='.$id.' limit 1');
		$f=$arr[0][filename_1];
		$f=explode('.',$f);
		$f1=$f[0];
		$f2=$f[1];
		$x=$size . "px";
	return array("<img onError='this.src=no_photo' style='width:$x; height:$x' src='photos/" . $f1 . "." . $f2 . "'>",$f1.".".$f2);
	}
	
	public function user_thumb_search($f='axx.png', $size=30) {
		if (empty($f)) $f='axx.png';
		$f=explode('.',$f);
		$f1=$f[0];
		$f2=$f[1];
		$x=$size . "px";
		$x_src='"photos/a29.png"';
		return array("<img onError='this.src=$x_src' class='' style='border:2px solid skyblue;width:$x; max-height:$x; height:$x; border-radius: 150px; --moz-corner-radius: 150px; margin:0px;' src='photos/" . $f1 . "." . $f2 . "'>",$f1.".".$f2);
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

	public function add_date($date, $days, $unit="Days"){
		$date = strtotime("+".$days." ".$unit, strtotime($date));
		return  date("Y-m-d H:i:s", $date);
	}

	public function query2HTML($query, $c) {
		echo '<style>';
		echo'.table_custom{font:Open Sans Condensed;color:#333; background:#f0f0f0; text-shadow:1px 1px 1px #fff} .table_header{color:#fff;background:#000;text-shadow:none} .table_row{color:#333; text-shadow:2px 2px 0px #fff} .table_row_alt{color:#000;background:lightblue}';
		echo '</style>';
		$x=0; $first_row = true;
		$header = "<table class='table_custom' cellpadding=10 cellspacing=0><tr id='th'>";	
		$f = $this->strAB('select','from', $query);
		$f = explode(',', $f);
		$f = $this->_sql_arr($query);
		$table  = $header;
		$rows = count($f);
			for($c=0; $c <= $rows; $c++){
				$class = ($x==0) ? 'table_row' : 'table_row_alt';
				$x = ($x == 1) ? 0 : 1;
				 if ($c == 0) $table .= "<tr class='$class' id='th'>";
					else $table .= "<tr class='$class' id='r$x'>";
					foreach($f[$c] as $_key => $_value)
						{
							if ($c == 0) $table .= "<td class='table_header'>" . strtoupper($_key) . "</td>";
								else $table .= "<td class='$class'>" . ($_value) . "</td>";
						}
			}
		$table .= "</table>";
		echo $table;
	}
	
	public function fixMobile($mob) {
		$num = trim($mob);
		$arr_a = array("-","."," ","(",")");
		$arr_b = array("","","","","");
		$num = str_replace($arr_a, $arr_b, $num);

		if ((strlen($num) < 10) || (strlen($num) > 11) || (substr($num,0,1)=='0') || (substr($num,1,1)=='0') || (substr($num,1,1)=='1')) return false;
		$num = (strlen($num) == 11) ? $num : ("1$num");	
		
		if ((strlen($num) == 11) && (substr($num, 0, 1) == "1")) {
			return $num;
		} else {
			return false;
		}
	}

	function checkMobile($mob, $format=false) {
		$num = trim($mob);
		$arr_a = array("-","."," ","(",",");
		$arr_b = array("","","","","","");
		$num = str_replace($arr_a, $arr_b, $num);
		if ((strlen($num) < 10) || (strlen($num) > 11)) {
			return false;
		} else {
			if (strlen($num) == 10) {
				if ((substr($num,0,1)=='0') || (substr($num,1,1)=='0') || (substr($num,0,1)=='1') || (substr($num,3,1)*1 < 2) || (substr($num,4,2)=="11")) {
					return "false";
				} else {
					if($format) {
						$num = "(" . substr($num,0,3) . ") " . substr($num,3,3) . "-" . substr($num,6,4); 
					} else {
						$num = (strlen($num) == 11) ? $num : ("1$num");
					}
					return $num;
				}
			} else {
				if ((substr($num,0,1) != '1') || (substr($num,1,1)=='0') || (substr($num,1,1)=='1') || (substr($num,4,1)*1 < 2) || (substr($num,5,2)=="11")) {
					return "false";
				} else {
					if($format) {
						$num = "(" . substr($num,1,3) . ") " . substr($num,4,3) . "-" . substr($num,7,4); 
					} else {
						$num = (strlen($num) == 11) ? $num : ("1$num");
					}
					return $num;
				}
			}
		}
	}

	public function cpu_load() {
		$output = `vmstat`;
		$s = strpos(trim($output),'wa st');
			return str_replace(" ","",trim(substr($output, $s+7, 5)));
	}

	public function latlng($ip) {
			$latLng = json_decode(file_get_contents("http://freegeoip.net/json/$ip"));
			$lat=$latLng->latitude;
			$lng=$latLng->longitude;
			return "$lat|$lng";
	}
	
	public function get_lat_lng_zip($zip) {
		$arr=$this->_sql_arr('select latn, longw from dt_zips where zipcode='.$zip.' limit 1');
			return array($arr[0][latn], $arr[0][longw]);
	}
	
	public function getLatLngFromCityState($city, $state) {
		$r = json_decode(file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?address=$city+$state"));
			return array($r->results[0]->geometry->location->lat,$r->results[0]->geometry->location->lng);
	}

	public function getZip($city_state) {
		$r = json_decode(file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?address=$city_state"));
			return $r->results[0]->postal_code;
	}
	public function street($lat,$lng) {
		$r = json_decode(file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$lng&sensor=true"));
			return $r->results[0]->formatted_address;
	}
	
	public function cityState($lat,$lng) {
		error_reporting(1);
		$r = json_decode(file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$lng&sensor=true"));
		$r=$r->results[0]->address_components;
		$r=json_decode((json_encode($r)));
		for ($i = 0; $i < count($r); $i++) {
			$addr = $r[$i];
			$types=$addr->types[0];
			if ($types == 'street_number') $street = $addr->short_name;
			if ($types == 'route') $street .= " " . $addr->short_name;
			if ($types == 'postal_code') $zip = $addr->short_name;
			if ($types == 'administrative_area_level_1') $state = $addr->short_name;
			if ($types == 'locality') $city = $addr->long_name;
		}		
		return ($city . ", " . $state);
	}

	public function getUserLocation() {
		$ip=$this->_ip();
		$latLng = json_decode(file_get_contents("http://freegeoip.net/json/$ip"));
		$lat=$latLng->latitude;
		$lng=$latLng->longitude;
		$r = json_decode(file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$lng&sensor=true"));
		$r=$r->results[0]->address_components;
		$r=json_decode((json_encode($r)));
		for ($i = 0; $i < count($r); $i++) {
			$addr = $r[$i];
			$types=$addr->types[0];
			if ($types == 'street_number') $street = $addr->short_name;
			if ($types == 'route') $street .= " " . $addr->short_name;
			if ($types == 'postal_code') $zip = $addr->short_name;
			if ($types == 'administrative_area_level_1') $state = $addr->short_name;
			if ($types == 'locality') $city = $addr->long_name;
		}		
		return json_encode(array($lat,$lng,$street,$city,$state,$zip));
	}
	public function getLocation() {
		$ip=$this->_ip();
		$latLng = json_decode(file_get_contents("http://terrawire.com:8080/json/$ip"));
		$lat=$latLng->latitude;
		$lng=$latLng->longitude;
		$r = json_decode(file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$lng&sensor=true"));
		$r=$r->results[0]->address_components;
		$r=json_decode((json_encode($r)));
		for ($i = 0; $i < count($r); $i++) {
			$addr = $r[$i];
			$types=$addr->types[0];
			if ($types == 'street_number') $street = $addr->short_name;
			if ($types == 'route') $street .= " " . $addr->short_name;
			if ($types == 'postal_code') $zip = $addr->short_name;
			if ($types == 'administrative_area_level_1') $state = $addr->short_name;
			if ($types == 'locality') $city = $addr->long_name;
		}		
		return json_encode(array("lat"=>$lat,"lng"=>$lng));
	}

	public function cityStateToLatLng($city, $state) {
		$sql="select * from geo_city where city='$city' and state='$state' limit 1";      
		$result=$this->_sql_arr($sql);
		if ($result) {
			$lat=$result[0]['lat'];
			$lng=$result[0]['lng'];
		}
		return array($lat,$lng);
	}

	public function zipToLatLng($zip) {
		$sql="select * from geo_city where zip='$zip' limit 1";      
		$result=$this->_sql_arr($sql);
		if ($result) {
			$lat=$result[0]['lat'];
			$lng=$result[0]['lng'];
		}
		return array($lat,$lng);
	}

	public function latLngtoLocation($lat,$lng) {
		$sql="select zipcode from dt_zips where ((abs(round(latn, 4)-round(".$lat." ,4)) < 0.001)  and  (abs(round(longw, 4) - round(".$lng.", 4)) < 0.001))";
		$result=$this->_sql_arr($sql);
		if ($result) {
			$zip=$result[0][zipcode];
			$factor="0.001";
		} else {
			$sql="select zipcode, abs(round(latn, 4)-round(".$lat." ,4)) as lat, abs(round(longw, 4) - round(".$lng.", 4)) as lng from dt_zips where ((abs(round(latn, 4)-round(".$lat." ,4)) < 0.1)  and  (abs(round(longw, 4) - round(".$lng.", 4)) < 0.1)) order by lat asc";
			$result=$this->_sql_arr($sql);
				if ($result) {
					$zip=$result[0][zipcode];
					$factor="0.1";
				} else {
					$sql="select zipcode, abs(round(latn, 4)-round(".$lat." ,4)) as lat, abs(round(longw, 4) - round(".$lng.", 4)) as lng from dt_zips where ((abs(round(latn, 4)-round(".$lat." ,4)) < 1)  and  (abs(round(longw, 4) - round(".$lng.", 4)) < 1)) order by lat asc";
					$result=$this->_sql_arr($sql);
					if ($result) {
						$zip=$result[0][zipcode];
						$factor="1";
					}				
				}
		}
		$sql="select * from geo_city where zip='$zip' limit 1";
		$result=$this->_sql_arr($sql);
			if ($result) {
				$zip=$result[0][zip];
				$city=$result[0][city];
				$state=$result[0][state];
				$areacode=$result[0][areacode];
			}
		return json_encode(array("city"=>$city,"state"=>$state,"zip"=>$zip,"areacode"=>$areacode,"lat"=>$lat,"lng"=>$lng));
	}

		static public function set($name, $value)
		{
			$GLOBALS[$name] = $value;
		}

		static public function get($name)
		{
			return $GLOBALS[$name];
		}

       /** 256 Bit AES Encryption
        *   @returns Encrypted Text
        **/
		public function encrypt($string, $pass, $depth=256)	{	
			return AesCtr::encrypt($string, $pass, $depth);
		}
 
       /** 256 Bit AES Decryption
        *   @returns Decrypted Text
        **/
		public function decrypt($enc_string, $enc_pass, $depth=256)	{	
			return AesCtr::decrypt($enc_string, $enc_pass, $depth);
		}
 
       /** Coverts ASCII to Binary
        *   @returns void
        **/
		public function strToBin($string)	{	
			$bin='';
			for ($i=0; $i < strlen($string); $i++)
			{
				$string[$i] = str_replace("a","@",$string[$i]);
				$string[$i] = str_replace("b","!",$string[$i]);
				$string[$i] = str_replace("c","|",$string[$i]);
				$string[$i] = str_replace("d","$",$string[$i]);
				$string[$i] = str_replace("e","#",$string[$i]);
				$string[$i] = str_replace("f","O",$string[$i]);
				if (!($string[$i] == "|") && !($string[$i]=="@") && !($string[$i]=="#") && !($string[$i]=="$") && !($string[$i]=="O") && !($string[$i]=="!")){
					$bx4 .= (strlen(decbin($string[$i])) == 4) ? decbin($string[$i]) : ((strlen(decbin($string[$i])) == 3) ? "0".decbin($string[$i]) : ((strlen(decbin($string[$i])) == 2) ? "00".decbin($string[$i]) : "000".decbin($string[$i])));
				}
				else
				{
					$bx4 .= $string[$i];
				}
			}
			return $bx4;
		}
 
 
        /** Coverts Binary to ASCII
        *   @returns void
        **/
		public function BinToHex($string) {
			$string = str_replace("O","f",$string);
			$hex='';
			for ($i=0; $i < strlen($string); $i++)
			{
				if (in_array($string[$i], array("a","b","c","d","e","f"))) {
					$hex .= $string[$i];
				}
				else
				{
					$hex .= bindec(substr($string, $i, 4));
					$i = $i + 3;
				}
			}
			return $hex;
		}


		
	
 function getUrlContents($url, $maximumRedirections = null, $currentRedirection = 0) {
     $result = false;
     $contents = @file_get_contents($url);
     // Check if we need to go somewhere else
     if (isset($contents) && is_string($contents)){
         preg_match_all('/<[\s]*meta[\s]*http-equiv="?REFRESH"?' . '[\s]*content="?[0-9]*;[\s]*URL[\s]*=[\s]*([^>"]*)"?' . '[\s]*[\/]?[\s]*>/si', $contents, $match);
         if (isset($match) && is_array($match) && count($match) == 2 && count($match[1]) == 1)
         {
             if (!isset($maximumRedirections) || $currentRedirection < $maximumRedirections)
             {
                 return getUrlContents($match[1][0], $maximumRedirections, ++$currentRedirection);
             }
             
             $result = false;
         }
         else
         {
             $result = $contents;
         }
     }
     return $contents;
 }
	
	public function make_string($str_length,$str="",$fill)	{
		$str="";
		for ($i=1; $i<=($str_length-strlen($str)*1); $i++) 
		{
			$str .= $fill;
		}
		return $str;
	}
	
	function IP2Loc($ip="") {
		require_once("geoipcity.inc");
		require_once("geoipregionvars.php");

		if (empty($ip)) {
			$ip=$this->_ip();
		}
		
		$gi	= geoip_open("GeoLiteCity.dat", GEOIP_STANDARD);	
		$latLng=geoip_record_by_addr($gi, $ip);
		$lat=$latLng->latitude;
		$lng=$latLng->longitude;

		$sql="select zipcode from dt_zips where ((abs(round(latn, 4)-round(".$lat." ,4)) < 0.001)  and  (abs(round(longw, 4) - round(".$lng.", 4)) < 0.001))";
		$result=$this->_sql_arr($sql);
		if ($result) {
			$zip=$result[0][zipcode];
			$factor="0.001";
		} else {
			$sql="select zipcode, abs(round(latn, 4)-round(".$lat." ,4)) as lat, abs(round(longw, 4) - round(".$lng.", 4)) as lng from dt_zips where ((abs(round(latn, 4)-round(".$lat." ,4)) < 0.1)  and  (abs(round(longw, 4) - round(".$lng.", 4)) < 0.1)) order by lat asc";
			$result=$this->_sql_arr($sql);
			if ($result) {
				$zip=$result[0][zipcode];
				$factor="0.1";
			} else {
				$sql="select zipcode, abs(round(latn, 4)-round(".$lat." ,4)) as lat, abs(round(longw, 4) - round(".$lng.", 4)) as lng from dt_zips where ((abs(round(latn, 4)-round(".$lat." ,4)) < 1)  and  (abs(round(longw, 4) - round(".$lng.", 4)) < 1)) order by lat asc";
				$result=$this->_sql_arr($sql);
				if ($result) {
					$zip=$result[0][zipcode];
					$factor="1";
				}				
			}
		}
		$sql="select * from geo_city where zip='$zip' limit 1";
		$result=$this->_sql_arr($sql);
			if ($result) {
				$zip=$result[0][zip];
				$city=$result[0][city];
				$state=$result[0][state];
				$areacode=$result[0][areacode];
			}
		return json_encode(array("city"=>$city,"state"=>$state,"zip"=>$zip,"areacode"=>$areacode,"lat"=>$lat,"lng"=>$lng));
	}
	
	public function zip($lat,$lng) {
		$r = json_decode(file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$lng&sensor=true"));
			return $r->results[1]->address_components[0]->long_name;
	}
	
	public function city($lat,$lng) {
		$r = json_decode(file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$lng&sensor=true"));
			return $r->results[1]->address_components[1]->long_name;
	}

	protected function strAB($a, $b, $str)	{
		return substr($str, (strPos($str, $a) + strlen($a)), (strPos($str, $b) - (strPos($str, $a) + strlen($a))));
	}
	
	public function strGetAB($a, $b, $str)	{
		return substr($str, (strPos($str, $a) + strlen($a)), (strPos($str, $b) - (strPos($str, $a) + strlen($a))));
	}

	//Get all Characters between 2 strings or tags in a URL
	public function urlGetAB($a, $b, $url) {
		$str = file_get_contents($url);
		return substr($str, (strPos($str, $a) + strlen($a)), (strPos($str, $b) - (strPos($str, $a) + strlen($a))));
	}

	public function clean($objHTMLText){
		$event_desc = preg_replace("/(\\t|\\r|\\n)/","",trim($objHTMLText));  //recursively remove new lines \\n, tabs and \\r
	}

	public function strDate($strDate, $format = "D jS \of M \[h A\]") {
		return date_format(date_create($strDate), $format);
	}
	public function dateadd($objstr,$unit,$interval,$op='+') {
		$date=date_create($objstr);
		date_add($date,date_interval_create_from_date_string("$op$unit $interval"));
		return $date;
	}

	public function date_add($hours) {
		$ts1 = strtotime(date("Y")."-".date("m")."-".date("d"));
		return $ts1+$hours*60;
	}
	 function date_to_words($strDate) {
		$ts1 = strtotime(date("Y")."-".date("m")."-".date("d"));
		$ts2 = strtotime($strDate);
		$dateDiff    = $ts1 - $ts2;
		$units="Day";
		$fd    = floor($dateDiff/(60*60*24));
		$plurl = ($fd==1)?"":"s";
		
		if ($fd == 0) $rt = "Today!";
			
		if ($fd > 0) {
			$rt = "$fd $units{$plurl} Ago";
		}
			
		if ($fd < 0) $rt = "In ".$fd*(-1)." ".$units{$plurl};

		if ($fd==7){
			$datap = "1";
			$prefx = "";
			$units = "Week";
			$plurl = "";
			$tense = "Ago";
			$rt="$prefx $datap $units{$plural} $tense";

		} elseif (($fd>7)&&($fd<31)) {
			$datap = round($fd/7,0);
			$prefx = "Approx";
			$units = "Week";
			$plurl = ($datap*1 < 2)?"":"s";
			$tense = "Ago";
			$rt="$prefx $datap $units{$plurl} $tense";

		} elseif (($fd>31)&&($fd<=365)) {
			$datap = round($fd/30.5,0);
			$prefx = "Approx";
			$units = "Month";
			$plurl = ($datap*1 < 2)?"":"s";
			$tense = "Ago";
			if ($datap*1 >= 12) {
				$e_months = $datap-12;
				$datap=1;
				$units='year';
				$plurl = ($datap*1 < 2)?"":"s";
				if ($e_months > 0) {
					$extra=' and ';
					$rt="$prefx $datap $units{$plurl} $extra $e_months{$e_plurl} $tense";		
				} else {
					$rt="$prefx $datap $units{$plurl} $tense";
				}
			} else {
				$rt="$prefx $datap $units{$plurl} $tense";
			}
		} elseif ($fd>=365) {
			$bal_days = $fd%365;
			$datap = round($fd/365,0);
			$prefx = "Approx";
			$units = "Year";
			$plurl = ($datap*1 < 2)?"":"s";
			$tense = "Ago";

			if ($bal_days > 15) {
				$extra = "and";
				$e_month="1";
				$e_unit="Month";
				$e_plurl=($e_month>1)?"s":"";
			}

			if ($bal_days > 29) {
				$extra = "and";
				$e_month=round($bal_days/30.5,0);
				$e_unit="Month";
				$e_plurl=($e_month*1 > 1) ? "s" : "";
				if ($e_month>11) {
					$e_month='';
					$e_unit='';
					$e_plurl='';
					$extra='';
					$datap++;
				}
			}
			$rt="$prefx $datap $units{$plurl} $extra $e_month $e_unit{$e_plurl} $tense";
		}
			return $rt;
	}

	public function is_mobile() {
		if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
			$tablet_browser++;
		}
		 
		if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
			$mobile_browser++;
		}
		 
		if ((strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
			$mobile_browser++;
		}
		 
		$mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
		$mobile_agents = array(
			'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
			'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
			'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
			'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
			'newt','noki','palm','pana','pant','phil','play','port','prox',
			'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
			'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
			'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
			'wapr','webc','winw','winw','xda ','xda-');
		 
		if (in_array($mobile_ua,$mobile_agents)) {
			$mobile_browser++;
		}
		 
		if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'opera mini') > 0) {
			$mobile_browser++;
			//Check for tablets on opera mini alternative headers
			$stock_ua = strtolower(isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA'])?$_SERVER['HTTP_X_OPERAMINI_PHONE_UA']:(isset($_SERVER['HTTP_DEVICE_STOCK_UA'])?$_SERVER['HTTP_DEVICE_STOCK_UA']:''));
			if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $stock_ua)) {
			  $tablet_browser++;
			}
		}
	
		if ($tablet_browser > 0) {
		   // do something for tablet devices
		  return false;
		}
		else if ($mobile_browser > 0) {
			setCookie("is_mobile",$mobile_browser, time() + 3600 * 24, "/");
		   return true;
		}
		else {
		   // do something for everything else
		   return false;
		}   		
	}	
	
    public function background($Command, $Priority = 0){
  //     if($Priority)
//           $PID = shell_exec("nohup nice -n $Priority $Command > /dev/null & echo $!");
  //     else
 //          $PID = shell_exec("nohup $Command > /dev/null & echo $!");
  $PID = shell_exec("nohup $Command > /dev/null & echo $!");
  return($PID);
   }
   /**
    * Check if the Application running !
    *
    * @param     unknown_type $PID
    * @return     boolean
    */
   public function is_running($PID){
       exec("ps $PID", $ProcessState);
       return(count($ProcessState) >= 2);
   }
   /**
    * Kill Application PID
    *
    * @param  unknown_type $PID
    * @return boolean
    */
   public function kill($PID){
       if(exec::is_running($PID)){
           exec("kill -KILL $PID");
           return true;
       }else return false;
   }
   
   public function getStateCode($state) {
		$state = str_replace("Outside United States","Non US",$state);
		$arr['California']="CA";
		$arr['Georgia']="GA";
		$arr['New Jersey']="NJ";
		$arr['New York']="NY";
		$arr['Arizona']="AZ";
		$arr['Texas']="TX";
		$arr['Oregon']="OR";
		$arr['Florida']="FL";
		$arr['Wyoming']="WY";
		$arr['Nevada']="NV";
		$arr['Pennsylvania']="PA";
		$arr['Illinois']="IA";
		$arr['Indiana']="IN";
		$arr['Nebraska']="NB";
		$arr['Wisconsin']="WI";
		$arr['Michigan']="MI";
		$arr['New Hampshire']="NH";
		$arr['North Carolina']="NC";
		$arr['South Carolina']="SC";
		$arr['Connecticut']="CN";
		$arr['Washington']="WA";
		$arr['North Dakota']="ND";
		$arr['South Dakota']="SD";
		$arr['Colorado']="CO";
		$arr['Oklahoma']="OK";
		$arr['Tennessee']="TN";
		$arr['Mississippi']="MS";
		$arr['Arkansas']="AK";
		$arr['New Mexico']="NM";
		$arr['Maryland']="MD";
		$arr['Delaware']="DA";
		$arr['Virginia']="VA";
		$arr['Massachusetts']="MA";
		$arr['Louisiana']="LA";
		$arr['Missouri']="MO"; 
		$arr['Montana']="MT"; 
		$arr['Utah']="UT";
		$arr['Kentucky']="KY";
		$arr['Alabama']="AL";
		$arr['Hawaii']="HI";
		$arr['West Virginia']="WV";
		$arr['Minnesota']="MN";
		$arr['Ohio']="OH";
		return(empty($arr[$state]))?$state:$arr[$state];
	}

	function getUserName($mid) {
		$q = $this->query("select login from dt_members where id=$mid");
		return $q[0][login];
	}
	
	function getUserEmail($mid) {
		$q = $this->query("select email from dt_members where id=$mid");
		return $q[0][email];
	}
	
	function getUserMobile($input,$medium) {
		$q = $this->query("select mobile from dt_members where $medium='$input' limit 1");
		return $this->isValidMobile($q[0]['mobile']);
	}
	
	function get_user_data($mid) {
		$sql="select login, ip, age,  lat, lng, cc_zip as zip, id as member_id, filename_1, cc_city as city, cc_state as state, general_info from dt_members where id='$mid'";
		$q = $this->query($sql);
			foreach($q as $r){
				$login = $r['login']; 
				$id = $r['member_id'];
				$pic = $r['filename_1'];
				$lat = $r['lat'];
				$lng = $r['lng'];
				$age = $r['age'];
				$gen = $r['gender'];
				$city = $r['city'];
				$state = str_replace("Outside United States","Non US",$r['state']);
				$ip = $r['ip'];
				$loc = "$city, $state";
				$p2['lat']=$lat;
				$p2['long']=$lng;
				$dist=calc_distance($p1,$p2);
				try {
					$s=getimagesize("../photos/".$pic);
					if ($s[0]!=$s[1]) {
						$filename="/home/gaysugar/public_html/photos/".$pic;
					}
					$x_img=$this->user_thumb_search($id, 65)[0];
				} Catch (Exception $e) {}
			}
			$x_d="$dist Miles";
			if ($dist*1 > 7000) $x_d = "Far, far away";
		
			$arr=array('mask1','mask2','mask3','mask4','mask5','mask6','mask7','mask8','mask9','mask10','mask11','mask12');
			$mask = $arr[rand(0,12)];
			
			$x_d="$dist Mls";
			$x_login = '"'.$login.'"';
			$str[$ctr] = " 
				<div class = 'www_box2 row' style='width:420px;border-bottom:1px solid lightblue;font-family:Open Sans Condensed;font-size:18px;padding:10px;background:url(assets/images/$mask.png) center center;margin-top:-5px;margin-bottom:5px;padding-bottom:2px;padding-top:2px'>
					<div class='col-sm-2'>
						<a href='page.php?page=view_profile&member_id=$id'>$x_img</a>
					</div>
					<div class='col-sm-4' style='position:absolute;left:100px;margin-left:0;margin-right:0;margin-left:0;margin-right:0;padding:0;top:10px'> 
						<div>
							".substr($login,0,14).", $agex 
						</div>
						<div>
							<span>
								
							</span>
							<span>
								$x_d
							</span>
							<span>
								<a href='page.php?page=3dmap&track=$id'>
									<i class='fa fa-location-arrow' style='cursor:hand; cursor:pointer;color:#0093D9'></i>
								</a>
							</span>
						</div>
					</div>
					<div style='position:absolute;top:10px;right:0;margin-right:10px;padding:0;padding-right:15px;color:gold;'>
						<span>
							$loc
						</span>
					</div>
					<div style='position:absolute;top:50%;right:0;margin-right:10px;padding:0;padding-right:15px'>

						<span>
							<a onclick='initMsg($id,$x_login)' href='#'><img src='assets/images/i27g.png' style='height:30px; opacity:0.8'></a>
						</span>
						<span>
							<img src='assets/images/i31g.png' style='height:30px; opacity:0.8'>
						</span>
						<span>
							<img src='assets/images/i32g.png' style='height:30px; opacity:0.8'>
						</span>
						<span>
							<img src='assets/images/i04g.png' style='height:30px; opacity:0.8'>
						</span>
						<span>
							<img src='assets/images/i10g.png' style='height:30px; opacity:0.8'>
						</span>
					</div>						
				</div>";	
			return $str[$ctr];
	}

	public function track_user() {
		/* 1. Get Affiliate */
		if (($_COOKIE['aff'] !='') && (!empty($_COOKIE['aff']))){
			$aff=$_COOKIE['aff'];
		} else {
			if (!isset($_REQUEST[aff])) {
				$aff=$_SESSION['aff'];	
			} else {
				$aff = $_REQUEST[aff];
			}
		}
		
		/* 1. Get User Agent */
		$ua=$_SERVER['HTTP_USER_AGENT'];
		
		/* 1. Get IP Address */
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
	
		/* 1. Get keyword used to search */
		$url=$_SERVER['REQUEST_URI'];
		$ref = $this->get_keywords($url);
		
		$host=$_SERVER['HTTP_REFERER']; 
		$keyword=$ref['keyword']; 

		if (empty($keyword)) $keyword=$_GET['q'];
		if (empty($keyword)) $keyword=$_GET['P'];
		if (empty($keyword)) $keyword=$_GET['wd'];
		if (empty($keyword)) $keyword=$_GET['query'];
		if (empty($keyword)) $keyword=$_GET['encquery'];
		
		if (!empty($aff)) setCookie("aff",$aff,time+3600*24,"/");
		if (!empty($host)) setCookie("HTTP_REFERER",$host,time+3600*24,"/");
		if (!empty($keyword)) setCookie("X_SOURCE",$keyword,time+3600*24,"/");
		if (!empty($ua)) setCookie("HTTP_USER_AGENT",$ua,time+3600*24,"/");
		if (!empty($ip)) setCookie("REMOTE_ADDR",$ip,time+3600*24,"/");
		
		return "$host|$keyword|$ua|$ip|$aff";
	}
	
	public function get_keywords($url = '') {
		// Get the referrer
		$referrer = (!empty($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : '';
		$referrer = (!empty($url)) ? $url : $referrer;
		if (empty($referrer))
			return false;
	 
		// Parse the referrer URL
		$parsed_url = parse_url($referrer);
		if (empty($parsed_url['host']))
			return false;
		$host = $parsed_url['host'];
		$query_str = (!empty($parsed_url['query'])) ? $parsed_url['query'] : '';
		$query_str = (empty($query_str) && !empty($parsed_url['fragment'])) ? $parsed_url['fragment'] : $query_str;
		if (empty($query_str))
			return false;
	 
		// Parse the query string into a query array
		parse_str($query_str, $query);
	 
		// Check some major search engines to get the correct query var
		$search_engines = array(
			'q' => 'alltheweb|aol|ask|ask|bing|google',
			'p' => 'yahoo',
			'wd' => 'baidu'
		);
		foreach ($search_engines as $query_var => $se)
		{
			$se = trim($se);
			preg_match('/(' . $se . ')\./', $host, $matches);
			if (!empty($matches[1]) && !empty($query[$query_var]))
				return $query[$query_var];
		}
		return false;
	}

	public function random_string($str_length=32) {
		$s=65;
		$r=97;
		$p=38;
		$length=128;
		if (1==1) {
			for ($i=48;$i<=57;$i++){
				$numbers[]=$i;
			}
		}

		if (1==1) {
			for ($i=$s;$i<$s+26;$i++){
				$upper[]=$i;
			}
		}
		if (1==1) {
			for ($i=$r;$i<$r+26;$i++){
				$lower[]=$i;
			}
		}
		if (1==1) {
			for ($i=176;$i<=179;$i++){
				$fractions[]=$i;
			}
		}
		if (1==1) {
			for ($i=188;$i<=190;$i++){
				$power[]=$i;
			}
			$power[]=137;
		}
		$symbols=array(58,59,60,61,62,63,64,153,169,174,156,167,176);
		$greek=array(140,166,167,164,133,134,135,181,182);
		$curr=array(128,131,142,154,158,159,161,162,163,165,169);

		$con=$numbers;
		$con=array_merge($con,$upper);
		$con=array_merge($con,$lower);
		//$con=array_merge($con,$fractions);
		//$con=array_merge($con,$power);
		//$con=array_merge($con,$symbols);
		//$con=array_merge($con,$greek);
		//$con=array_merge($con,$curr);
		for ($i=0;$i<=count($con)-1;$i++) {
			$arr[]=chr($con[$i]);
		}
		for ($i=0;$i<=$str_length;$i++){
			$str .= $arr[rand(0,count($arr)-1)];
		}
		return $str;
	}
}