// http://ejohn.org/blog/ecmascript-5-strict-mode-json-and-more/
"use strict";

// Optional. You will see this name in eg. 'ps' or 'top' command
process.title = 'node-chat';
var execSync = require("exec-sync");
// Port where we'll run the websocket server
var webSocketsServerPort = 1338;

// websocket and http servers
var ctr=0;
/**
 * Global variables
 */
// latest 100 messages
var history = [ ];
// list of currently connected clients (users)
var clients = [ ];
var mobile;
var alias;
/**
 * Helper function for escaping input strings
 */
function htmlEntities(str) {
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;')
                      .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

// Array with some colors
var colors = [ 'red', 'green', 'blue', 'magenta', 'purple', 'plum', 'orange' ];
var mobiles = [ ];
// ... in random order
colors.sort(function(a,b) { return Math.random() > 0.5; } );

/**
 * WebSocket server
 */


var webSocketServer = require('websocket').server;
var http = require('http');
var server = http.createServer(function(request, response) {
});
server.listen(webSocketsServerPort, function() {
    console.log((new Date()) + " Server is listening on port " + webSocketsServerPort);
});
var wsServer = new webSocketServer({
    httpServer: server
});

 
var login;
var users = [ ];
var out='';
var to_index
var pm
var to_user
var from_user
var toUser;
var msg;
var toMobile;
var fromMobile;
var pm;
var fromLongCode;

// This callback function is called every time someone
// tries to connect to the WebSocket server
wsServer.on('request', function(request) {
	ctr++
	var userName=false;
    var userColor = false;
	var firstTime=true;
	login=request.resourceURL.query['login'];
	mobile=request.resourceURL.query['mobile'];
	msg=request.resourceURL.query['msg'];
	mobile=isValidMobile(mobile)
	if (mobile) {
		if (mobiles.indexOf(mobile) < 0) {
			var connection = request.accept(null, request.origin); 
			var index = clients.push(connection) - 1;
			
			console.log((new Date()) + ' Connection accepted: ' + mobile + ' is online');
			out=out+JSON.stringify({type: 'users', data: users})+'<br>';

			userName=login;
			users.push(userName);
			mobiles.push(mobile);
			connection.sendUTF('Your Login is ' + mobile);
			connection.sendUTF(JSON.stringify("There are " + (clients.length*1-1) + " other users in chat"));
			connection.sendUTF(JSON.stringify(users));
			for (var i=0; i < clients.length; i++) {
				if (clients[i] != connection) {
					clients[i].sendUTF(userName + ' has just joined the room');
				}
			}
		}
		if (msg) {
			toUser=msg.split('|')[0];
			from_user=login;
			pm=msg.split('|')[1];
			if (users.indexOf(toUser) >= 0) {
				to_index=users.indexOf(toUser);
				to_user=toUser;
				clients[to_index].sendUTF('You have a PM from ' + from_user + ': ' + pm);
			} else {
				if (mobiles.indexOf(toUser) >= 0) {
					to_index=mobiles.indexOf(toUser);
					to_user=clients[mobiles.indexOf(toUser)];
					clients[to_index].sendUTF('You have a PM from ' + from_user + ': ' + pm);
				}
			} 
		}
	}
		/*
		} else {
		var connection = request.accept(null, request.origin); 
		connection.sendUTF('Invalid request - missing mobile and login');
		connection.sendUTF((new Date()) + " Peer "	+ connection.remoteAddress + " disconnected.");
		console.log((new Date()) + " Peer "	+ connection.remoteAddress + " disconnected.");
		connection.close();
		}
		*/
    // user sent some message
    connection.on('message', function(message) {
/*		if (firstTime) {
			firstTime=false;
			userName=message.utf8Data.split('|')[1];
			mobile=message.utf8Data.split('|')[0];
			users.push(userName);
			mobiles.push(mobile);
			connection.sendUTF('Your Login is ' + message.utf8Data.split('|')[1]);
			connection.sendUTF(JSON.stringify("There are " + (clients.length*1-1) + " other users in chat"));
			connection.sendUTF(JSON.stringify(users));
			for (var i=0; i < clients.length; i++) {
				if (clients[i] != connection) {
					clients[i].sendUTF(userName + ' has just joined the room');
				}
			}
		}	else {
*/
		   if (message.type === 'utf8') { // accept only text
			connection.sendUTF(message.utf8Data+ ' :received')
				var obj = {
					time: (new Date()).getTime(),
					text: htmlEntities(message.utf8Data),
					login: userName,
					mobile: mobile
				};
				history.push(obj);
				history = history.slice(-100);
				
				if(message.utf8Data.split('|')[0]=='msg') {
					toUser=message.utf8Data.split('|')[1];
					from_user=message.utf8Data.split('|')[2];
					pm=message.utf8Data.split('|')[3];
					if (users.indexOf(toUser) >= 0) {
						to_index=users.indexOf(toUser);
						to_user=toUser;
						clients[to_index].sendUTF('You have a PM from ' + from_user + ': ' + pm);
					} else {
						if (mobiles.indexOf(toUser) >= 0) {
							to_index=mobiles.indexOf(toUser);
							to_user=clients[mobiles.indexOf(toUser)];
							clients[to_index].sendUTF('You have a PM from ' + from_user + ': ' + pm);
						}
					}
				} else if (message.utf8Data.split('|')[0]=='gateway_reply'){
					console.log(message.utf8Data)
					toUser=message.utf8Data.split('|')[1];
					from_user=message.utf8Data.split('|')[2];
					pm=message.utf8Data.split('|')[3];
					alias=message.utf8Data.split('|')[4];
					
					if (users.indexOf(toUser) >= 0) {
						to_index=users.indexOf(toUser);
						to_user=toUser;
						clients[to_index].sendUTF(pm);
						console.log('Sent msg ' + pm + ' to user ' + to_user);
					} else {
						if (mobiles.indexOf(toUser) >= 0) {
							to_index=mobiles.indexOf(toUser);
							to_user=clients[mobiles.indexOf(toUser)];
							to_user=isValidMobile(to_user)
							clients[to_index].sendUTF('msg|'+from_user+'|'+pm+'|'+alias);
							console.log('Sent msg ' + pm + ' to user ' + alias);
						}
					}
				} else if (message.utf8Data.split('|')[0]=='new_msg'){
					toMobile=message.utf8Data.split('|')[1];
					fromLongCode=message.utf8Data.split('|')[2];
					pm=message.utf8Data.split('|')[3];
					var ep = "wget -O - -q -t 1 'http://txt.am/inc/x_send_api.php?to="+toMobile+"&from="+fromLongCode+"&message="+pm+"' >/dev/null 2>&1"
					var op=execSync(ep);
					console.log(ep);
					console.log(op); 
					
				} else {
					// broadcast message to all connected clients
					var json = JSON.stringify({ type:'message', data: message.utf8Data });
					for (var i=0; i < clients.length; i++) {
						clients[i].sendUTF(json);
					}
				}
			}
//		}
    });

    // user disconnected
	if (!msg) {
    connection.on('close', function(connection) {
 //       if (userName !== false && userColor !== false) {
            console.log((new Date()) + " Peer "
                + connection.remoteAddress + " disconnected.");
            // remove user from the list of connected clients
            clients.splice(index, 1);
            users.splice(index, 1);
            mobiles.splice(index, 1);
            // push back user's color to be reused by another user
            colors.push(userColor);
  //      }
    });
}

});

function userExists(login) {
	for (var i=0; i < users.length; i++) {
		if (users[i]==login) return users[i];
			else return false;
	}	
}

function isValidMobile(mob) {
return mob
}
