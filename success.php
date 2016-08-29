<?
session_start();
require_once __DIR__ . '/vendor/autoload.php';
$fb = new Facebook\Facebook([
  'app_id' => '1620406188237943',
  'app_secret' => '52234f9356c42357b903561a4667b33a',
  'default_graph_version' => 'v2.4',
  ]);

$helper = $fb->getRedirectLoginHelper();
try {
	$accessToken = $helper->getAccessToken();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
	echo 'Graph returned an error: ' . $e->getMessage();
exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
	echo 'Facebook SDK returned an error: ' . $e->getMessage();
	exit;
}

if (isset($accessToken)) {
	$_SESSION['facebook_access_token'] = (string) $accessToken;
	$fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
	$response = $fb->get('/me?fields=name,email', $_SESSION['facebook_access_token']);
	$user = $response->getGraphUser();
	$name=$user['name'];
	$email=$user['email'];
	$id=$user['id'];
	$file_src="http://graph.facebook.com/$id/picture?type=large";
	header("Location:auction.php?port=".$_COOKIE['port']);
} else echo "error logging in with fb";