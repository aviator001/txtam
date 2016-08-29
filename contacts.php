<?
	error_reporting(E_ALL);
	include "class/utils.class.php"; 
	$c = new utils;
	$c->connect(); 
	require_once 'vendor/autoload.php';
	use rapidweb\googlecontacts\factories\ContactFactory;
	$contacts = ContactFactory::getAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Home</title>
  <meta charset="utf-8">
  <meta name="format-detection" content="telephone=no"/>
  <link rel="icon" href="images/favicon.ico" type="image/x-icon">
  <link rel="stylesheet" href="assets/css/bootstrap.css">
  <link rel="stylesheet" href="assets/css/style.css">
  

  <script src="assets/js/jquery.js"></script>
  <script src="js/jquery-migrate-1.2.1.js"></script>

  <!--[if lt IE 9]>
  <html class="lt-ie9">
  <div style=' clear: both; text-align:center; position: relative;'>
    <a href="http://windows.microsoft.com/en-US/internet-explorer/..">
      <img src="images/ie8-panel/warning_bar_0000_us.jpg" border="0" height="42" width="820"
           alt="You are using an outdated browser. For a faster, safer browsing experience, upgrade for free today."/>
    </a>
  </div>
  <script src="js/html5shiv.js"></script>
  <![endif]-->

  <script src='js/device.min.js'></script>
</head>

<body style="background:#1c1c1c">
<div class="page">
  <!--========================================================
                            HEADER
  =========================================================-->
  <header id="header" class="header">
    <div class="header_panel">
      <div class="brand">
        <div class="brand_cnt">
        <b>  <p class="brand_slogan">
        </div>
      </div>
    </div>

  </header>
<?	
	for ($i=0;$i<count($contacts);$i++) {
		$name=$contacts[$i]->name;
		$phone=$c->isValidMobile($contacts[$i]->phoneNumber);
		if (!empty($name)) {
			if ($phone) {
				if (!array_key_exists ($phone,$p)) {
					$n[]=$name;
					$p[]=$c->isValidMobile($phone);
					$c->insert("INSERT INTO `contacts` (`owner_number`, `cat`, `name`, `phone`) VALUES ('" . $_REQUEST['mobile'] . "', '" . substr($name,0,1) . "', '$name', '$phone')");
					$book[]=array("name"=>$name,"phone"=>$c->isValidMobile($phone));
					echo "<div style='border-top:2px solid silver;padding:10px'><div style='width:100%'>". $c->print_blocks($i,'25px') . "</div><div style='width:100%;border-bottom:0px solid #333;color:skyblue'><h2>$name</h2></div><div style='width:100%;color:silver'><h3>".$c->formatMobile($phone, true) . "</h3></div>";
					echo "<div>";
					echo "<span><img src='assets/img/vcam.png' onmouseover='this.src=\"assets/img/vcam_pink.png\"'  onmouseout='this.src=\"assets/img/vcam.png\"' style='width:60px'></span>";
					echo "<span style='margin-left:15px'><img onmouseover='this.src=\"assets/img/zsms_blue.png\"'  onmouseout='this.src=\"assets/img/zsms.png\"'  src='assets/img/zsms.png' style='width:70px'></span>";
					echo "<span style='margin-left:15px'><img onmouseover='this.src=\"assets/img/i3sms_red.png\"'  onmouseout='this.src=\"assets/img/i3sms.png\"'  src='assets/img/i3sms.png' style='width:75px'></span>";
					echo "<span style='margin-left:15px'><img src='assets/img/i32g.png' style='width:80px'></span>";
					echo "</div>";
					echo "</div>";
				}
			}
		}
	}
?>
