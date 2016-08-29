<?
/**
 * Very basic websocket client.
 * Supporting handshake from drafts:
 *	draft-hixie-thewebsocketprotocol-76
 *	draft-ietf-hybi-thewebsocketprotocol-00
 * 
 * @author Simon Samtleben <web@lemmingzshadow.net>
 * @version 2011-09-15
 */
 
class WebsocketClient
{
	private $_Socket = null;
 
	public function __construct($host, $port)
	{
		$this->_connect($host, $port);	
	}
 
	public function __destruct()
	{
		$this->_disconnect();
	}
 
	public function sendData($data)
	{
		// send actual data:
		fwrite($this->_Socket,  $data ) or die('Error:' . $errno . ':' . $errstr); 
		
		$wsData=fread($this->_Socket, 2000);
		echo $retData = trim($wsData,"\x00\xff");     
		stream_set_blocking($retData,false);
		echo stream_get_contents($retData); 
		}
 
	private function _connect($host, $port)
	{
		$key1 = $this->_generateRandomString(32);
		$key2 = $this->_generateRandomString(32);
		$key3 = $this->_generateRandomString(8, false, true);		
 
		$header = "GET /echo HTTP/1.1\r\n";
		$header.= "Upgrade: WebSocket\r\n";
		$header.= "Connection: Upgrade\r\n";
		$header.= "Host: ".$host.":".$port."\r\n";
		$header.= "Origin: http://foobar.com\r\n";
		$header.= "Sec-WebSocket-Key: x3JJHMbDL1EzLkh9GBhXDw==\r\n";
		$header.= "Sec-WebSocket-Protocol: chat, superchat\r\n";
		$header.= "Sec-WebSocket-Version: 13\r\n";
		$header.= "Origin: gautam\r\n\r\n";
 
		$this->_Socket = fsockopen($host, $port, $errno, $errstr, 2); 
		fwrite($this->_Socket, $header) or die('Error: ' . $errno . ':' . $errstr); 
		
		/**
		 * @todo: check response here. Currently not implemented cause "2 key handshake" is already deprecated.
		 * See: http://en.wikipedia.org/wiki/WebSocket#WebSocket_Protocol_Handshake
		 */		
 
		return true;
	}
 
	private function _disconnect()
	{
		fclose($this->_Socket);
	}
 
	private function _generateRandomString($length = 10, $addSpaces = true, $addNumbers = true)
	{  
		$characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!"§$%&/()=[]{}';
		$useChars = array();
		// select some random chars:    
		for($i = 0; $i < $length; $i++)
		{
			$useChars[] = $characters[mt_rand(0, strlen($characters)-1)];
		}
		// add spaces and numbers:
		if($addSpaces === true)
		{
			array_push($useChars, ' ', ' ', ' ', ' ', ' ', ' ');
		}
		if($addNumbers === true)
		{
			array_push($useChars, rand(0,9), rand(0,9), rand(0,9));
		}
		shuffle($useChars);
		$randomString = trim(implode('', $useChars));
		$randomString = substr($randomString, 0, $length);
		return $randomString;
	}
}
 
$WebSocketClient = new WebsocketClient('199.91.65.94', 7777);
$WebSocketClient->sendData("PING|10");
