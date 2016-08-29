<?php
set_time_limit(0);

error_reporting(E_ALL);
ini_set('track_errors', 1); 
ini_set('error_reporting', 1);
ini_set('display_errors', 1);
ini_set('error_log','/g_error_log'); 
ini_set('log_errors',TRUE);

require "/home/gaysugar/public_html/inc/utils.class.php";
$c=new utils;
$c->connect();

class socketChat {
	public function __construct () {
		try {
			global $conn;
			$this->conn = new mysqli("199.91.65.85", "gautamadmin", "Shadow2015!", "gaysugardaddyforme");
		} catch (Exception $e) {
			throw new Exception("DB unavailable!");
		}
		$this->port = $c->getPort();
		$this->serverIP = $c->getServerIP();
	}

	// maximum amount of clients that can be connected at one time
	const WS_MAX_CLIENTS = 10000;

	// maximum amount of clients that can be connected at one time on the same IP v4 address
	const WS_MAX_CLIENTS_PER_IP = 10000;

	// amount of seconds a client has to send data to the server, before a ping request is sent to the client,
	// if the client has not completed the opening handshake, the ping request is skipped and the client connection is closed
	const WS_TIMEOUT_RECV = 10;

	// amount of seconds a client has to reply to a ping request, before the client connection is closed
	const WS_TIMEOUT_PONG = 5;

	// the maximum length, in bytes, of a frame's payload data (a message consists of 1 or more frames), this is also internally limited to 2,147,479,538
	const WS_MAX_FRAME_PAYLOAD_RECV = 10000000;

	// the maximum length, in bytes, of a message's payload data, this is also internally limited to 2,147,483,647
	const WS_MAX_MESSAGE_PAYLOAD_RECV = 50000000;

	// Internal Constants
	const WS_FIN 								= 128;
	const WS_MASK 								= 128;
	const WS_OPCODE_CONTINUATION 				= 0;
	const WS_OPCODE_TEXT 						= 1;
	const WS_OPCODE_BINARY 						= 2;
	const WS_OPCODE_CLOSE 						= 8;
	const WS_OPCODE_PING 						= 9;
	const WS_OPCODE_PONG 						= 10;
	const WS_PAYLOAD_LENGTH_16 					= 126;
	const WS_PAYLOAD_LENGTH_63 					= 127;
	const WS_READY_STATE_CONNECTING 			= 0;
	const WS_READY_STATE_OPEN 					= 1;
	const WS_READY_STATE_CLOSING				= 2;
	const WS_READY_STATE_CLOSED					= 3;
	const WS_STATUS_NORMAL_CLOSE 				= 1000;
	const WS_STATUS_GONE_AWAY 					= 1001;
	const WS_STATUS_PROTOCOL_ERROR 				= 1002;
	const WS_STATUS_UNSUPPORTED_MESSAGE_TYPE 	= 1003;
	const WS_STATUS_MESSAGE_TOO_BIG 			= 1004;
	const WS_STATUS_TIMEOUT 					= 10;

	public $clientID;
	public $wsClientCount   					= 0;
	public $wsTotal 							= 0;

	//Public vars for the user object...
	public $logins 								= array();
	public $wsClientIDs     				  	= array();
	public $wsClients     					  	= array();
	public $wsRead         					 	= array();
	public $wsClientIPCount 					= array();
	public $wsOnEvents      					= array();
	public $wsLogins      						= array();

	/*
		$this->wsClients[ integer ClientID ] = array(
			0 => resource  Socket,                            // client socket
			1 => string    MessageBuffer,                     // a blank string when there's no incoming frames
			2 => integer   ReadyState,                        // between 0 and 3
			3 => integer   LastRecvTime,                      // set to time() when the client is added
			4 => int/false PingSentTime,                      // false when the server is not waiting for a pong
			5 => int/false CloseStatus,                       // close status that wsOnClose() will be called with
			6 => integer   IPv4,                              // client's IP stored as a signed long, retrieved from ip2long()
			7 => int/false FramePayloadDataLength,            // length of a frame's payload data, reset to false when all frame data has been read (cannot reset to 0, to allow reading of mask key)
			8 => integer   FrameBytesRead,                    // amount of bytes read for a frame, reset to 0 when all frame data has been read
			9 => string    FrameBuffer,                       // joined onto end as a frame's data comes in, reset to blank string when all frame data has been read
			10 => integer  MessageOpcode,                     // stored by the first frame for fragmented messages, default value is 0
			11 => integer  MessageBufferLength                // the payload data length of MessageBuffer
		)
		$wsRead[ integer ClientID ] = resource Socket         // this one-dimensional array is used for socket_select()
															  // $wsRead[ 0 ] is the socket listening for incoming client connections
		$wsClientCount = integer ClientCount                  // amount of clients currently connected
		$wsClientIPCount[ integer IP ] = integer ClientCount  // amount of clients connected per IP v4 address
	*/

	// server state functions
	protected function insert($sql) {
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

	protected function query($sql) {
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

	protected function close() {
		try {
			$this->conn->close();
			return "Closed";
		} catch (Exception $e) {
			return "Unable to Close";
			exit;
		}
	}

	public function authenticate($companyID, $login, $password, $photo='no_photo') {
		#Step 1. Authenticate User
		#Step 2. Set User Info Object
		$date = new DateTime();
		$unlimited_end = $date->getTimestamp();

		//Read Profile Data from DB
		$fQry=$c->query("select * from members where id='$fromUserID' limit 1");
		$picF=$fQry[0];
		$ipF=$fQry[1];
		$genderF=$fQry[2];
		$locationF=$fQry[3];
		
		$tQry=$c->query("select * from members where id='$toUserID' limit 1");
		$picT=$tQry[0];
		$ipT=$tQry[1];
		$genderT=$tQry[2];
		$locationT=$tQry[3];

		if(!($this->sock = socket_create(AF_INET, SOCK_STREAM, 0))) {
			$this->errCode = socket_last_error();
			$this->errMsg  = socket_strerror($errorcode);
			$this->status="Unable to connect to socket on port $port";
		}
		
		if(!socket_connect($this->sock , $this->serverIP , $this->port))	{
			$this->errCode = socket_last_error();
			$this->errMsg = socket_strerror($errorcode);
			$this->status="Unable to connect to socket on port $port";
		}
		$this->errCode = '';
		$this->errMsg = '';
		$this->status = 'Connection established';
		
		return array($oAuthToken, $msg);
	}

	public function whosOnline($oAuthToken) {
		return array($oAuthToken, $onlineUserList);
	}

	public function sendMsg($oAuthToken, $msgType, $fromUserID, $fromUserLogin, $toUserLogin, $txtMsg) {
		if ($oAuthToken) {
			$message = $msgType + '|' + $fromUserID + '|' + $fromUserLogin + '|' + $toUserLogin + '|' + $txtMsg;
			if( ! socket_send ( $this->sock, $message , strlen($message) , 0)) {
				$this->errCode = socket_last_error();
				$this->errMsg  = socket_strerror($errorcode);
				$this->status="Unable to send Message";
			}
		}
		$this->errCode = null;
		$this->errMsg = null;
		$this->status = 'Message Sent OK.';
	}

	public function getMsg($oAuthToken, $msgType, $fromUserID, $fromUserLogin, $toUserLogin, $txtMsg) {
	//Now receive reply from server
	if(socket_recv ( $sock , $buf , 2045 , MSG_WAITALL ) === FALSE)
	{
		$errorcode = socket_last_error();
		$errormsg = socket_strerror($errorcode);
		 
		die("Could not receive data: [$errorcode] $errormsg \n");
	}
	 
	//print the received message
	echo $buf;
}
}