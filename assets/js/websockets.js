var mapSocket = function(url){
	var callbacks = {};
	var ws_url = url;
	var conn;

	this.bind = function(event_name, callback){
		callbacks[event_name] = callbacks[event_name] || [];
		callbacks[event_name].push(callback);
		return this;// chainable
	};
	this.send = function(event_name, event_data){		try {			this.conn.send( event_data );		} catch (err){		//	setTimeout('reset_socket()',100)		}		return this;	};
	try {		this.connect = function() {			if ( typeof(MozWebSocket) == 'function' )				this.conn = new MozWebSocket(url);			else				this.conn = new WebSocket(url);			// dispatch to the right handlers			this.conn.onmessage = function(evt){				dispatch('message', evt.data);			};			this.conn.onclose = function(){dispatch('close',null)}			this.conn.onopen = function(){dispatch('open',null)}		};	} catch (err) {		setTimeout('reset_socket()',100)	}

		this.conn.onclose = function(){dispatch('close',null)}
		this.conn.onopen = function(){dispatch('open',null)}
	};

	this.disconnect = function() {
		this.conn.close();
	};

	var dispatch = function(event_name, message){
		var chain = callbacks[event_name];
		if(typeof chain == 'undefined') return; // no callbacks for this event
		for(var i = 0; i < chain.length; i++){
			chain[i]( message )
		}
	}
function reset_socket() {	var request = $.ajax({		url: 'inc/x_socket_daemon.php?id=1',		type: "GET",		dataType: "html",		cache: false,		success: function(data) {					}	})}