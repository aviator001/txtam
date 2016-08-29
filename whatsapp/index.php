<?
error_reporting(E_ALL);
require_once 'src/whatsprot.class.php';
require_once '../class/utils.class.php';
$c=new utils;
$c->connect();
/*
$username = '14807733331';    // Your number with country code, ie: 34123456789
$nickname = '';    // Your nickname, it will appear in push notifications
$debug    = true;  // Shows debug log, this is set to false if not specified
$log      = true;  // Enables log file, this is set to false if not specified
$password = "WIfd1bFF2pBUs7xjCMby9DEgsng="; // The one we got registering the number

// Create a instance of WhastPort.
$w = new WhatsProt($username, $nickname, $debug);

$w->connect(); // Connect to WhatsApp network
$w->loginWithPassword($password); // logging in with the password we got!
$c->show($w);
*/
?>
        <!DOCTYPE html>
        <html lang="en">
			<head>
				<meta charset="utf-8">
				<meta http-equiv="X-UA-Compatible" content="IE=edge">
				<title>Personal Whatsapp Login</title>
				<meta name="viewport" content="width=device-width, initial-scale=1.0">
				<meta name="description" content="">
				<meta name="author" content="">

				<!-- Styles -->
				<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
				<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
				<style type="text/css">
					body {
						padding-top: 40px;
						padding-bottom: 40px;
						background-color: #f5f5f5;
					}
					/*h2 {
						font-size: 24px;
					}
					.form-signin, .danger {
						max-width: 300px;
						padding: 20px 40px;
						margin: 10px auto;
						background-color: #fff;
						border: 1px solid #e5e5e5;
						-webkit-border-radius: 5px;
						-moz-border-radius: 5px;
						border-radius: 5px;
						-webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
						-moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
						box-shadow: 0 1px 2px rgba(0,0,0,.05);
					}
					.form-signin input[type="password"] {
						font-size: 16px;
						height: auto;
						margin-bottom: 15px;
						padding: 7px 9px;
					}*/
					.danger {
						background-color: pink;
						padding: 10px;
					}
				</style>
			<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
			</head>
			<body>
				<div class="container">
					<div class="row">
						<div id="s1" class="col-sm-offset-2 col-md-offset-3 col-lg-offset-3 col-xs-12 col-sm-8 col-md-6">
							<div class="panel panel-default">
								<div class="panel-heading">
									<h2>Whatsapp :: Registration</h2>
								</div>
								<div id="s1" class="panel-body">
									<form class="form-signin" action="/whatsapp/examples/whatsapp.php" method="post" role="form">
										<input type="hidden" name="action" value="login">
										<div class="row">
											<div class="col-xs-offset-1 col-sm-offset-1 col-md-offset-1 col-lg-offset-1 col-xs-10">
												<div class="form-group">
													<input class="form-control input-lg" type="username" name="username" id="username" placeholder="Mobile Number">
												</div>
											</div>
										</div>										
										<div class="row">
											<div class="col-xs-offset-1 col-sm-offset-1 col-md-offset-1 col-lg-offset-1 col-xs-10">
												<div class="form-group">
													<input class="form-control input-lg" type="username" id="web_pswd" placeholder="Create Password">
												</div>
											</div>
										</div>										
										<div class="row">
											<div class="col-xs-offset-1 col-sm-offset-1 col-md-offset-1 col-lg-offset-1 col-xs-10">
												<button class="btn btn-info btn-lg btn-block" type="button" onclick="reg()">Request Code</button>
											</div>
										</div>
									</form>
								</div>
							</div> <!-- /.panel panel-default -->
						</div> <!-- /.col -->
						<div id="s2" style="display:none" class="col-sm-offset-2 col-md-offset-3 col-lg-offset-3 col-xs-12 col-sm-8 col-md-6">
							<div class="panel panel-default">
								<div class="panel-heading">
									<h2>Whatsapp :: Confirm Code</h2>
								</div>
								<div id="" class="panel-body">
									<form class="form-signin" action="/whatsapp/examples/whatsapp.php" method="post" role="form">
										<input type="hidden" name="action" value="login">
										<div class="row">
											<div class="col-xs-offset-1 col-sm-offset-1 col-md-offset-1 col-lg-offset-1 col-xs-10">
												<div class="form-group">
													<input class="form-control input-lg" type="username" name="code" id="code" placeholder="Enter Code Here">
												</div>
											</div>
										</div>										
										<div class="row">
											<div class="col-xs-offset-1 col-sm-offset-1 col-md-offset-1 col-lg-offset-1 col-xs-10">
												<button class="btn btn-info btn-lg btn-block" type="button" onclick="verify_code()">Verify Code</button>
											</div>
										</div>
									</form>
								</div>
							</div> <!-- /.panel panel-default -->
						</div> <!-- /.col -->
						<div id="s3" style="display:none" class="col-sm-offset-2 col-md-offset-3 col-lg-offset-3 col-xs-12 col-sm-8 col-md-6">
							<div class="panel panel-default">
								<div class="panel-heading">
									<h2>Whatsapp :: Registration</h2>
								</div>
								<div id="" class="panel-body">
									<form class="form-signin" action="/whatsapp/examples/whatsapp.php" method="post" role="form">
										<input type="hidden" name="action" value="login">
										<div class="row">
											<div class="col-xs-offset-1 col-sm-offset-1 col-md-offset-1 col-lg-offset-1 col-xs-10">
												<div class="form-group">
													<input class="form-control input-lg" type="username" name="username" value="<?=$_GET['username'];?>" id="username" placeholder="Mobile Number">
												</div>
											</div>
										</div>										
										<div class="row">
											<div class="col-xs-offset-1 col-sm-offset-1 col-md-offset-1 col-lg-offset-1 col-xs-10">
												<div class="form-group">
													<input class="form-control input-lg" type="username" id="pswd" placeholder="WhatsApp Password">
												</div>
											</div>
										</div>										
										<div class="row">
											<div class="col-xs-offset-1 col-sm-offset-1 col-md-offset-1 col-lg-offset-1 col-xs-10">
												<button class="btn btn-info btn-lg btn-block" type="button" onclick="final()">Login to WhatsApp</button>
											</div>
										</div>
									</form>
								</div>
							</div> <!-- /.panel panel-default -->
						</div> <!-- /.col -->

					</div> <!-- /.row -->
				</div> <!-- /.container -->
				
	<script>

	var mob	="<?=$_GET['username'];?>"

	<? if ($_GET['action']=='step2') {?>
		step3()
	<? } ?>
	
	function reg() {
		mob=document.getElementById('username').value
		if (mob=='') {
			alert('enter a valid 10 digit mobile number please')
			return false;
		}
		var url = 'step1.php?username='+document.getElementById('username').value+'&web_pswd='+document.getElementById('web_pswd').value
		console.log(url)
		var request = $.ajax({
			url: url,
			type: "GET",
			dataType: "html",
			cache: false,
			success: function(msg) {
				$('#s1').hide()
				$('#s2').show()
			}
		})		
	}
	var pwd
	function verify_code() {
		var url = 'step2.php?username='+mob+'&code='+document.getElementById('code').value
		console.log(url)
		var request = $.ajax({
			url: url,
			type: "GET",
			dataType: "html",
			cache: false,
			success: function(msg) {
				pwd=msg
				$('#s1').hide()
				$('#s2').hide()
				$('#s3').show()	
				$('#username').val(mob)
				$('#pswd').val(pwd)
				step3()
			}
		})		
		
	}
	
	function step3() {
		var url = 'step.php?username='+mob
		var request = $.ajax({
			url: url,
			type: "GET",
			dataType: "html",
			cache: false,
			success: function(msg) {
				$('#s1').hide()
				$('#s2').hide()
				$('#s3').show()	
				$('#username').val(mob)
				$('#pswd').val(msg)
				document.getElementById('username').value=mob
			}
		})		

	}				
	
	function final() {
		var url = 'step3.php?username='+mob
		console.log(url)
		var request = $.ajax({
			url: url,
			type: "GET",
			dataType: "html",
			cache: false,
			success: function(msg) {
				alert(msg)
			}
		})		
		
	}
	
	</script>


			</body>
        </html>
        