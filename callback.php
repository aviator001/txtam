<?
session_start();
require_once 'facebook-sdk-v5/autoload.php';
$fb = new Facebook\Facebook([
  'app_id' => '166355080403596',
  'app_secret' => 'b2adc0910bbead52935127839d923f0c',
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

	$member['name']=$user['name'];
	$member['email']=$user['email'];
	$member['id']=$user['id'];
	$member['photo']="http://graph.facebook.com/$id/picture?type=large";
	$_SESSION['user']=$member;
	setCookie("member",json_encode($member));
	include "class/utils.class.php";
	$u = new utils();
	$u->connect();
	if (isset($_COOKIE[mobile])) {
		if ($u->is_mobile()) {
			header("Location:m.members.php");		
		} else {
			header("Location:members.php");
		}
	} else {
		header("Location:mobile.php");
	}
} else echo "error logging in with fb";