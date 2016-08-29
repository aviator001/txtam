<?
	$USER_ID = 'guatam@strikeiron.com';
	$PASSWORD = 'Strike1';
	$phoneNumber = $_REQUEST['mobile_number'];
	$WSDL = 'http://ws.strikeiron.com/PhoneandAddressAdvanced?WSDL';
	$client = new SoapClient($WSDL, array('trace' => 1, 'exceptions' => 1));
	$registered_user = array("RegisteredUser" => array("UserID" => $USER_ID,"Password" => $PASSWORD));
	$header = new SoapHeader("http://ws.strikeiron.com", "LicenseInfo", $registered_user);
	$client->__setSoapHeaders($header);
	$params = array("PhoneNumber" => $phoneNumber);
	$result = $client->__soapCall("ReverseLookupByPhoneNumber", array($params), null, null, $output_header);
	echo $name = $result->ReverseLookupByPhoneNumberResult->ServiceResult;
