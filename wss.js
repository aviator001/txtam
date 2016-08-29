var ws_cfg = {
  ssl: true,
  port: 8081,
  ssl_key: 'txt.am.key',
  ssl_cert: 'txt.am.crt'
};

var processRequest = function(req, res) {
    console.log("Request received.")
};

var httpServ = require('https');
var app = null;

app = httpServ.createServer({
  key: fs.readFileSync(ws_cfg.ssl_key),
  cert: fs.readFileSync(ws_cfg.ssl_cert)
}, processRequest).listen(ws_cfg.port);

//var WebSocketServer = require('ws').Server, ws_server = new WebSocketServer(ws_cfg);

var WebSocketServer = require('ws').Server, ws_server = new WebSocketServer( {server: app});
