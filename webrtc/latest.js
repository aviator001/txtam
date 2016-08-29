! function(e) {
    if ("function" == typeof bootstrap) bootstrap("simplewebrtc", e);
    else if ("object" == typeof exports) module.exports = e();
    else if ("function" == typeof define && define.amd) define(e);
    else if ("undefined" != typeof ses) {
        if (!ses.ok()) return;
        ses.makeSimpleWebRTC = e
    } else "undefined" != typeof window ? window.SimpleWebRTC = e() : global.SimpleWebRTC = e()
}(function() {
    var e;
    return function(e, t, n) {
        function r(n, i) {
            if (!t[n]) {
                if (!e[n]) {
                    var a = "function" == typeof require && require;
                    if (!i && a) return a(n, !0);
                    if (o) return o(n, !0);
                    throw new Error("Cannot find module '" + n + "'")
                }
                var s = t[n] = {
                    exports: {}
                };
                e[n][0].call(s.exports, function(t) {
                    var o = e[n][1][t];
                    return r(o ? o : t)
                }, s, s.exports)
            }
            return t[n].exports
        }
        for (var o = "function" == typeof require && require, i = 0; i < n.length; i++) r(n[i]);
        return r
    }({
        1: [function(e, t) {
            function n(e) {
                var t, n, u = this,
                    p = e || {},
                    d = this.config = {
                        url: "https://sandbox.simplewebrtc.com:443/",
                        socketio: {},
                        connection: null,
                        debug: !1,
                        localVideoEl: "",
                        remoteVideosEl: "",
                        enableDataChannels: !1,
                        autoRequestMedia: !1,
                        autoRemoveVideos: !1,
                        adjustPeerVolume: !1,
                        peerVolumeWhenSpeaking: .25,
                        media: {
                            video: !0,
                            audio: !0
                        },
                        receiveMedia: {
                            offerToReceiveAudio: 1,
                            offerToReceiveVideo: 1
                        },
                        localVideo: {
                            autoplay: !1,
                            mirror: !0,
                            muted: !0
                        }
                    };
                this.logger = function() {
                    return e.debug ? e.logger || console : e.logger || s
                }();
                for (t in p) this.config[t] = p[t];
                this.capabilities = i, o.call(this), n = this.connection = null === this.config.connection ? new c(this.config) : this.config.connection, n.on("connect", function() {
                    u.emit("connectionReady", n.getSessionid()), u.sessionReady = !0, u.testReadiness()
                }), n.on("message", function(e) {
                    var t, n = u.webrtc.getPeers(e.from, e.roomType);
                    "offer" === e.type ? (n.length && n.forEach(function(n) {
                        n.sid == e.sid && (t = n)
                    }), t || (t = u.webrtc.createPeer({
                        id: e.from,
                        sid: e.sid,
                        type: e.roomType,
                        enableDataChannels: u.config.enableDataChannels && "screen" !== e.roomType,
                        sharemyscreen: "screen" === e.roomType && !e.broadcaster,
                        broadcaster: "screen" !== e.roomType || e.broadcaster ? null : u.connection.getSessionid()
                    }), u.emit("createdPeer", t)), t.handleMessage(e)) : n.length && n.forEach(function(t) {
                        e.sid ? t.sid === e.sid && t.handleMessage(e) : t.handleMessage(e)
                    })
                }), n.on("remove", function(e) {
                    e.id !== u.connection.getSessionid() && u.webrtc.removePeers(e.id, e.type)
                }), e.logger = this.logger, e.debug = !1, this.webrtc = new r(e), ["mute", "unmute", "pauseVideo", "resumeVideo", "pause", "resume", "sendToAll", "sendDirectlyToAll", "getPeers"].forEach(function(e) {
                    u[e] = u.webrtc[e].bind(u.webrtc)
                }), this.webrtc.on("*", function() {
                    u.emit.apply(u, arguments)
                }), d.debug && this.on("*", this.logger.log.bind(this.logger, "SimpleWebRTC event:")), this.webrtc.on("localStream", function() {
                    u.testReadiness()
                }), this.webrtc.on("message", function(e) {
                    u.connection.emit("message", e)
                }), this.webrtc.on("peerStreamAdded", this.handlePeerStreamAdded.bind(this)), this.webrtc.on("peerStreamRemoved", this.handlePeerStreamRemoved.bind(this)), this.config.adjustPeerVolume && (this.webrtc.on("speaking", this.setVolumeForAll.bind(this, this.config.peerVolumeWhenSpeaking)), this.webrtc.on("stoppedSpeaking", this.setVolumeForAll.bind(this, 1))), n.on("stunservers", function(e) {
                    u.webrtc.config.peerConnectionConfig.iceServers = e, u.emit("stunservers", e)
                }), n.on("turnservers", function(e) {
                    u.webrtc.config.peerConnectionConfig.iceServers = u.webrtc.config.peerConnectionConfig.iceServers.concat(e), u.emit("turnservers", e)
                }), this.webrtc.on("iceFailed", function() {}), this.webrtc.on("connectivityError", function() {}), this.webrtc.on("audioOn", function() {
                    u.webrtc.sendToAll("unmute", {
                        name: "audio"
                    })
                }), this.webrtc.on("audioOff", function() {
                    u.webrtc.sendToAll("mute", {
                        name: "audio"
                    })
                }), this.webrtc.on("videoOn", function() {
                    u.webrtc.sendToAll("unmute", {
                        name: "video"
                    })
                }), this.webrtc.on("videoOff", function() {
                    u.webrtc.sendToAll("mute", {
                        name: "video"
                    })
                }), this.webrtc.on("localScreen", function(e) {
                    var t = document.createElement("video"),
                        n = u.getRemoteVideoContainer();
                    t.oncontextmenu = function() {
                        return !1
                    }, t.id = "localScreen", a(e, t), n && n.appendChild(t), u.emit("localScreenAdded", t), u.connection.emit("shareScreen"), u.webrtc.peers.forEach(function(e) {
                        var t;
                        "video" === e.type && (t = u.webrtc.createPeer({
                            id: e.id,
                            type: "screen",
                            sharemyscreen: !0,
                            enableDataChannels: !1,
                            receiveMedia: {
                                offerToReceiveAudio: 0,
                                offerToReceiveVideo: 0
                            },
                            broadcaster: u.connection.getSessionid()
                        }), u.emit("createdPeer", t), t.start())
                    })
                }), this.webrtc.on("localScreenStopped", function() {
                    u.stopScreenShare()
                }), this.webrtc.on("channelMessage", function(e, t, n) {
                    "volume" == n.type && u.emit("remoteVolumeChange", e, n.volume)
                }), this.config.autoRequestMedia && this.startLocalVideo()
            }
            var r = e("./webrtc"),
                o = e("wildemitter"),
                i = e("webrtcsupport"),
                a = e("attachmediastream"),
                s = e("mockconsole"),
                c = e("./socketioconnection");
            n.prototype = Object.create(o.prototype, {
                constructor: {
                    value: n
                }
            }), n.prototype.leaveRoom = function() {
                if (this.roomName) {
                    for (this.connection.emit("leave"); this.webrtc.peers.length;) this.webrtc.peers.shift().end();
                    this.getLocalScreen() && this.stopScreenShare(), this.emit("leftRoom", this.roomName), this.roomName = void 0
                }
            }, n.prototype.disconnect = function() {
                this.connection.disconnect(), delete this.connection
            }, n.prototype.handlePeerStreamAdded = function(e) {
                var t = this,
                    n = this.getRemoteVideoContainer(),
                    r = a(e.stream);
                e.videoEl = r, r.id = this.getDomId(e), n && n.appendChild(r), this.emit("videoAdded", r, e), window.setTimeout(function() {
                    t.webrtc.isAudioEnabled() || e.send("mute", {
                        name: "audio"
                    }), t.webrtc.isVideoEnabled() || e.send("mute", {
                        name: "video"
                    })
                }, 250)
            }, n.prototype.handlePeerStreamRemoved = function(e) {
                var t = this.getRemoteVideoContainer(),
                    n = e.videoEl;
                this.config.autoRemoveVideos && t && n && t.removeChild(n), n && this.emit("videoRemoved", n, e)
            }, n.prototype.getDomId = function(e) {
                return [e.id, e.type, e.broadcaster ? "broadcasting" : "incoming"].join("_")
            }, n.prototype.setVolumeForAll = function(e) {
                this.webrtc.peers.forEach(function(t) {
                    t.videoEl && (t.videoEl.volume = e)
                })
            }, n.prototype.joinRoom = function(e, t) {
                var n = this;
                this.roomName = e, this.connection.emit("join", e, function(r, o) {
                    if (console.log("join CB", r, o), r) n.emit("error", r);
                    else {
                        var i, a, s, c;
                        for (i in o.clients) {
                            a = o.clients[i];
                            for (s in a) a[s] && (c = n.webrtc.createPeer({
                                id: i,
                                type: s,
                                enableDataChannels: n.config.enableDataChannels && "screen" !== s,
                                receiveMedia: {
                                    offerToReceiveAudio: "screen" !== s && n.config.receiveMedia.offerToReceiveAudio ? 1 : 0,
                                    offerToReceiveVideo: n.config.receiveMedia.offerToReceiveVideo
                                }
                            }), n.emit("createdPeer", c), c.start())
                        }
                    }
                    t && t(r, o), n.emit("joinedRoom", e)
                })
            }, n.prototype.getEl = function(e) {
                return "string" == typeof e ? document.getElementById(e) : e
            }, n.prototype.startLocalVideo = function() {
                var e = this;
                this.webrtc.startLocalMedia(this.config.media, function(t, n) {
                    t ? e.emit("localMediaError", t) : a(n, e.getLocalVideoContainer(), e.config.localVideo)
                })
            }, n.prototype.stopLocalVideo = function() {
                this.webrtc.stopLocalMedia()
            }, n.prototype.getLocalVideoContainer = function() {
                var e = this.getEl(this.config.localVideoEl);
                if (e && "VIDEO" === e.tagName) return e.oncontextmenu = function() {
                    return !1
                }, e;
                if (e) {
                    var t = document.createElement("video");
                    return t.oncontextmenu = function() {
                        return !1
                    }, e.appendChild(t), t
                }
            }, n.prototype.getRemoteVideoContainer = function() {
                return this.getEl(this.config.remoteVideosEl)
            }, n.prototype.shareScreen = function(e) {
                this.webrtc.startScreenShare(e)
            }, n.prototype.getLocalScreen = function() {
                return this.webrtc.localScreen
            }, n.prototype.stopScreenShare = function() {
                this.connection.emit("unshareScreen");
                var e = document.getElementById("localScreen"),
                    t = this.getRemoteVideoContainer(),
                    n = this.getLocalScreen();
                this.config.autoRemoveVideos && t && e && t.removeChild(e), e && this.emit("videoRemoved", e), n && n.stop(), this.webrtc.peers.forEach(function(e) {
                    e.broadcaster && e.end()
                })
            }, n.prototype.testReadiness = function() {
                var e = this;
                this.webrtc.localStreams.length > 0 && this.sessionReady && e.emit("readyToCall", e.connection.getSessionid())
            }, n.prototype.createRoom = function(e, t) {
                this.roomName = e, 2 === arguments.length ? this.connection.emit("create", e, t) : this.connection.emit("create", e)
            }, n.prototype.sendFile = function() {
                return i.dataChannel ? void 0 : this.emit("error", new Error("DataChannelNotSupported"))
            }, t.exports = n
        }, {
            "./socketioconnection": 2,
            "./webrtc": 3,
            attachmediastream: 5,
            mockconsole: 6,
            webrtcsupport: 7,
            wildemitter: 4
        }],
        4: [function(e, t) {
            function n() {}
            t.exports = n, n.mixin = function(e) {
                var t = e.prototype || e;
                t.isWildEmitter = !0, t.on = function(e) {
                    this.callbacks = this.callbacks || {};
                    var t = 3 === arguments.length,
                        n = t ? arguments[1] : void 0,
                        r = t ? arguments[2] : arguments[1];
                    return r._groupName = n, (this.callbacks[e] = this.callbacks[e] || []).push(r), this
                }, t.once = function(e) {
                    function t() {
                        n.off(e, t), i.apply(this, arguments)
                    }
                    var n = this,
                        r = 3 === arguments.length,
                        o = r ? arguments[1] : void 0,
                        i = r ? arguments[2] : arguments[1];
                    return this.on(e, o, t), this
                }, t.releaseGroup = function(e) {
                    this.callbacks = this.callbacks || {};
                    var t, n, r, o;
                    for (t in this.callbacks)
                        for (o = this.callbacks[t], n = 0, r = o.length; r > n; n++) o[n]._groupName === e && (o.splice(n, 1), n--, r--);
                    return this
                }, t.off = function(e, t) {
                    this.callbacks = this.callbacks || {};
                    var n, r = this.callbacks[e];
                    return r ? 1 === arguments.length ? (delete this.callbacks[e], this) : (n = r.indexOf(t), r.splice(n, 1), 0 === r.length && delete this.callbacks[e], this) : this
                }, t.emit = function(e) {
                    this.callbacks = this.callbacks || {};
                    var t, n, r, o = [].slice.call(arguments, 1),
                        i = this.callbacks[e],
                        a = this.getWildcardCallbacks(e);
                    if (i)
                        for (r = i.slice(), t = 0, n = r.length; n > t && r[t]; ++t) r[t].apply(this, o);
                    if (a)
                        for (n = a.length, r = a.slice(), t = 0, n = r.length; n > t && r[t]; ++t) r[t].apply(this, [e].concat(o));
                    return this
                }, t.getWildcardCallbacks = function(e) {
                    this.callbacks = this.callbacks || {};
                    var t, n, r = [];
                    for (t in this.callbacks) n = t.split("*"), ("*" === t || 2 === n.length && e.slice(0, n[0].length) === n[0]) && (r = r.concat(this.callbacks[t]));
                    return r
                }
            }, n.mixin(n)
        }, {}],
        6: [function(e, t) {
            for (var n = "assert,count,debug,dir,dirxml,error,exception,group,groupCollapsed,groupEnd,info,log,markTimeline,profile,profileEnd,time,timeEnd,trace,warn".split(","), r = n.length, o = function() {}, i = {}; r--;) i[n[r]] = o;
            t.exports = i
        }, {}],
        7: [function(e, t) {
            var n, r;
            window.mozRTCPeerConnection || navigator.mozGetUserMedia ? (n = "moz", r = parseInt(navigator.userAgent.match(/Firefox\/([0-9]+)\./)[1], 10)) : (window.webkitRTCPeerConnection || navigator.webkitGetUserMedia) && (n = "webkit", r = navigator.userAgent.match(/Chrom(e|ium)/) && parseInt(navigator.userAgent.match(/Chrom(e|ium)\/([0-9]+)\./)[2], 10));
            var o = window.RTCPeerConnection || window.mozRTCPeerConnection || window.webkitRTCPeerConnection,
                i = window.mozRTCIceCandidate || window.RTCIceCandidate,
                a = window.mozRTCSessionDescription || window.RTCSessionDescription,
                s = window.webkitMediaStream || window.MediaStream,
                c = "https:" === window.location.protocol && ("webkit" === n && r >= 26 || "moz" === n && r >= 33),
                u = window.AudioContext || window.webkitAudioContext,
                p = document.createElement("video"),
                d = p && p.canPlayType && "probably" === p.canPlayType('video/webm; codecs="vp8", vorbis'),
                f = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.msGetUserMedia || navigator.mozGetUserMedia;
            t.exports = {
                prefix: n,
                browserVersion: r,
                support: !!o && !!f,
                supportRTCPeerConnection: !!o,
                supportVp8: d,
                supportGetUserMedia: !!f,
                supportDataChannel: !!(o && o.prototype && o.prototype.createDataChannel),
                supportWebAudio: !(!u || !u.prototype.createMediaStreamSource),
                supportMediaStream: !(!s || !s.prototype.removeTrack),
                supportScreenSharing: !!c,
                AudioContext: u,
                PeerConnection: o,
                SessionDescription: a,
                IceCandidate: i,
                MediaStream: s,
                getUserMedia: f
            }
        }, {}],
        3: [function(e, t) {
            function n(e) {
                var t = this,
                    n = e || {};
                this.config = {
                    debug: !1,
                    peerConnectionConfig: {
                        iceServers: [{
                            urls: "stun:stun.l.google.com:19302"
                        }]
                    },
                    peerConnectionConstraints: {
                        optional: []
                    },
                    receiveMedia: {
                        offerToReceiveAudio: 1,
                        offerToReceiveVideo: 1
                    },
                    enableDataChannels: !0
                };
                var r;
                this.screenSharingSupport = o.screenSharing, this.logger = function() {
                    return e.debug ? e.logger || console : e.logger || i
                }();
                for (r in n) this.config[r] = n[r];
                o.support || this.logger.error("Your browser doesn't seem to support WebRTC"), this.peers = [], a.call(this, this.config), this.on("speaking", function() {
                    t.hardMuted || t.peers.forEach(function(e) {
                        if (e.enableDataChannels) {
                            var t = e.getDataChannel("hark");
                            if ("open" != t.readyState) return;
                            t.send(JSON.stringify({
                                type: "speaking"
                            }))
                        }
                    })
                }), this.on("stoppedSpeaking", function() {
                    t.hardMuted || t.peers.forEach(function(e) {
                        if (e.enableDataChannels) {
                            var t = e.getDataChannel("hark");
                            if ("open" != t.readyState) return;
                            t.send(JSON.stringify({
                                type: "stoppedSpeaking"
                            }))
                        }
                    })
                }), this.on("volumeChange", function(e) {
                    t.hardMuted || t.peers.forEach(function(t) {
                        if (t.enableDataChannels) {
                            var n = t.getDataChannel("hark");
                            if ("open" != n.readyState) return;
                            n.send(JSON.stringify({
                                type: "volume",
                                volume: e
                            }))
                        }
                    })
                }), this.config.debug && this.on("*", function(e, n, r) {
                    var o;
                    o = t.config.logger === i ? console : t.logger, o.log("event:", e, n, r)
                })
            }
            var r = e("util"),
                o = e("webrtcsupport");
            e("wildemitter");
            var i = e("mockconsole"),
                a = e("localmedia"),
                s = e("./peer");
            r.inherits(n, a), n.prototype.createPeer = function(e) {
                var t;
                return e.parent = this, t = new s(e), this.peers.push(t), t
            }, n.prototype.removePeers = function(e, t) {
                this.getPeers(e, t).forEach(function(e) {
                    e.end()
                })
            }, n.prototype.getPeers = function(e, t) {
                return this.peers.filter(function(n) {
                    return !(e && n.id !== e || t && n.type !== t)
                })
            }, n.prototype.sendToAll = function(e, t) {
                this.peers.forEach(function(n) {
                    n.send(e, t)
                })
            }, n.prototype.sendDirectlyToAll = function(e, t, n) {
                this.peers.forEach(function(r) {
                    r.enableDataChannels && r.sendDirectly(e, t, n)
                })
            }, t.exports = n
        }, {
            "./peer": 9,
            localmedia: 10,
            mockconsole: 6,
            util: 8,
            webrtcsupport: 7,
            wildemitter: 4
        }],
        2: [function(e, t) {
            function n(e) {
                this.connection = r.connect(e.url, e.socketio)
            }
            var r = e("socket.io-client");
            n.prototype.on = function(e, t) {
                this.connection.on(e, t)
            }, n.prototype.emit = function() {
                this.connection.emit.apply(this.connection, arguments)
            }, n.prototype.getSessionid = function() {
                return this.connection.id
            }, n.prototype.disconnect = function() {
                return this.connection.disconnect()
            }, t.exports = n
        }, {
            "socket.io-client": 11
        }],
        8: [function(e, t, n) {
            function r(e) {
                return Array.isArray(e) || "object" == typeof e && "[object Array]" === Object.prototype.toString.call(e)
            }

            function o(e) {
                "object" == typeof e && "[object RegExp]" === Object.prototype.toString.call(e)
            }

            function i(e) {
                return "object" == typeof e && "[object Date]" === Object.prototype.toString.call(e)
            }
            e("events"), n.isArray = r, n.isDate = function(e) {
                return "[object Date]" === Object.prototype.toString.call(e)
            }, n.isRegExp = function(e) {
                return "[object RegExp]" === Object.prototype.toString.call(e)
            }, n.print = function() {}, n.puts = function() {}, n.debug = function() {}, n.inspect = function(e, t, c, u) {
                function p(e, c) {
                    if (e && "function" == typeof e.inspect && e !== n && (!e.constructor || e.constructor.prototype !== e)) return e.inspect(c);
                    switch (typeof e) {
                        case "undefined":
                            return f("undefined", "undefined");
                        case "string":
                            var u = "'" + JSON.stringify(e).replace(/^"|"$/g, "").replace(/'/g, "\\'").replace(/\\"/g, '"') + "'";
                            return f(u, "string");
                        case "number":
                            return f("" + e, "number");
                        case "boolean":
                            return f("" + e, "boolean")
                    }
                    if (null === e) return f("null", "null");
                    var l = a(e),
                        h = t ? s(e) : l;
                    if ("function" == typeof e && 0 === h.length) {
                        if (o(e)) return f("" + e, "regexp");
                        var g = e.name ? ": " + e.name : "";
                        return f("[Function" + g + "]", "special")
                    }
                    if (i(e) && 0 === h.length) return f(e.toUTCString(), "date");
                    var m, v, y;
                    if (r(e) ? (v = "Array", y = ["[", "]"]) : (v = "Object", y = ["{", "}"]), "function" == typeof e) {
                        var b = e.name ? ": " + e.name : "";
                        m = o(e) ? " " + e : " [Function" + b + "]"
                    } else m = "";
                    if (i(e) && (m = " " + e.toUTCString()), 0 === h.length) return y[0] + m + y[1];
                    if (0 > c) return o(e) ? f("" + e, "regexp") : f("[Object]", "special");
                    d.push(e);
                    var w = h.map(function(t) {
                        var n, o;
                        if (e.__lookupGetter__ && (e.__lookupGetter__(t) ? o = e.__lookupSetter__(t) ? f("[Getter/Setter]", "special") : f("[Getter]", "special") : e.__lookupSetter__(t) && (o = f("[Setter]", "special"))), l.indexOf(t) < 0 && (n = "[" + t + "]"), o || (d.indexOf(e[t]) < 0 ? (o = null === c ? p(e[t]) : p(e[t], c - 1), o.indexOf("\n") > -1 && (o = r(e) ? o.split("\n").map(function(e) {
                                return "  " + e
                            }).join("\n").substr(2) : "\n" + o.split("\n").map(function(e) {
                                return "   " + e
                            }).join("\n"))) : o = f("[Circular]", "special")), "undefined" == typeof n) {
                            if ("Array" === v && t.match(/^\d+$/)) return o;
                            n = JSON.stringify("" + t), n.match(/^"([a-zA-Z_][a-zA-Z_0-9]*)"$/) ? (n = n.substr(1, n.length - 2), n = f(n, "name")) : (n = n.replace(/'/g, "\\'").replace(/\\"/g, '"').replace(/(^"|"$)/g, "'"), n = f(n, "string"))
                        }
                        return n + ": " + o
                    });
                    d.pop();
                    var S = 0,
                        k = w.reduce(function(e, t) {
                            return S++, t.indexOf("\n") >= 0 && S++, e + t.length + 1
                        }, 0);
                    return w = k > 50 ? y[0] + ("" === m ? "" : m + "\n ") + " " + w.join(",\n  ") + " " + y[1] : y[0] + m + " " + w.join(", ") + " " + y[1]
                }
                var d = [],
                    f = function(e, t) {
                        var n = {
                                bold: [1, 22],
                                italic: [3, 23],
                                underline: [4, 24],
                                inverse: [7, 27],
                                white: [37, 39],
                                grey: [90, 39],
                                black: [30, 39],
                                blue: [34, 39],
                                cyan: [36, 39],
                                green: [32, 39],
                                magenta: [35, 39],
                                red: [31, 39],
                                yellow: [33, 39]
                            },
                            r = {
                                special: "cyan",
                                number: "blue",
                                "boolean": "yellow",
                                undefined: "grey",
                                "null": "bold",
                                string: "green",
                                date: "magenta",
                                regexp: "red"
                            }[t];
                        return r ? "[" + n[r][0] + "m" + e + "[" + n[r][1] + "m" : e
                    };
                return u || (f = function(e) {
                    return e
                }), p(e, "undefined" == typeof c ? 2 : c)
            }, n.log = function() {}, n.pump = null;
            var a = Object.keys || function(e) {
                    var t = [];
                    for (var n in e) t.push(n);
                    return t
                },
                s = Object.getOwnPropertyNames || function(e) {
                    var t = [];
                    for (var n in e) Object.hasOwnProperty.call(e, n) && t.push(n);
                    return t
                },
                c = Object.create || function(e, t) {
                    var n;
                    if (null === e) n = {
                        __proto__: null
                    };
                    else {
                        if ("object" != typeof e) throw new TypeError("typeof prototype[" + typeof e + "] != 'object'");
                        var r = function() {};
                        r.prototype = e, n = new r, n.__proto__ = e
                    }
                    return "undefined" != typeof t && Object.defineProperties && Object.defineProperties(n, t), n
                };
            n.inherits = function(e, t) {
                e.super_ = t, e.prototype = c(t.prototype, {
                    constructor: {
                        value: e,
                        enumerable: !1,
                        writable: !0,
                        configurable: !0
                    }
                })
            };
            var u = /%[sdj%]/g;
            n.format = function(e) {
                if ("string" != typeof e) {
                    for (var t = [], r = 0; r < arguments.length; r++) t.push(n.inspect(arguments[r]));
                    return t.join(" ")
                }
                for (var r = 1, o = arguments, i = o.length, a = String(e).replace(u, function(e) {
                        if ("%%" === e) return "%";
                        if (r >= i) return e;
                        switch (e) {
                            case "%s":
                                return String(o[r++]);
                            case "%d":
                                return Number(o[r++]);
                            case "%j":
                                return JSON.stringify(o[r++]);
                            default:
                                return e
                        }
                    }), s = o[r]; i > r; s = o[++r]) a += null === s || "object" != typeof s ? " " + s : " " + n.inspect(s);
                return a
            }
        }, {
            events: 12
        }],
        5: [function(e, t) {
            var n = e("webrtc-adapter-test");
            t.exports = function(e, t, r) {
                var o;
                window.URL;
                var i = t,
                    a = {
                        autoplay: !0,
                        mirror: !1,
                        muted: !1,
                        audio: !1,
                        disableContextMenu: !1
                    };
                if (r)
                    for (o in r) a[o] = r[o];
                return i ? "audio" === i.tagName.toLowerCase() && (a.audio = !0) : i = document.createElement(a.audio ? "audio" : "video"), a.disableContextMenu && (i.oncontextmenu = function(e) {
                    e.preventDefault()
                }), a.autoplay && (i.autoplay = "autoplay"), a.muted && (i.muted = !0), !a.audio && a.mirror && ["", "moz", "webkit", "o", "ms"].forEach(function(e) {
                    var t = e ? e + "Transform" : "transform";
                    i.style[t] = "scaleX(-1)"
                }), n.attachMediaStream(i, e), i
            }
        }, {
            "webrtc-adapter-test": 13
        }],
        14: [function(e, t) {
            var n = t.exports = {};
            n.nextTick = function() {
                var e = "undefined" != typeof window && window.setImmediate,
                    t = "undefined" != typeof window && window.postMessage && window.addEventListener;
                if (e) return function(e) {
                    return window.setImmediate(e)
                };
                if (t) {
                    var n = [];
                    return window.addEventListener("message", function(e) {
                            var t = e.source;
                            if ((t === window || null === t) && "process-tick" === e.data && (e.stopPropagation(), n.length > 0)) {
                                var r = n.shift();
                                r()
                            }
                        }, !0),
                        function(e) {
                            n.push(e), window.postMessage("process-tick", "*")
                        }
                }
                return function(e) {
                    setTimeout(e, 0)
                }
            }(), n.title = "browser", n.browser = !0, n.env = {}, n.argv = [], n.binding = function() {
                throw new Error("process.binding is not supported")
            }, n.cwd = function() {
                return "/"
            }, n.chdir = function() {
                throw new Error("process.chdir is not supported")
            }
        }, {}],
        12: [function(e, t, n) {
            function r(e, t) {
                if (e.indexOf) return e.indexOf(t);
                for (var n = 0; n < e.length; n++)
                    if (t === e[n]) return n;
                return -1
            }
            var o = e("__browserify_process");
            o.EventEmitter || (o.EventEmitter = function() {});
            var i = n.EventEmitter = o.EventEmitter,
                a = "function" == typeof Array.isArray ? Array.isArray : function(e) {
                    return "[object Array]" === Object.prototype.toString.call(e)
                },
                s = 10;
            i.prototype.setMaxListeners = function(e) {
                this._events || (this._events = {}), this._events.maxListeners = e
            }, i.prototype.emit = function(e) {
                if ("error" === e && (!this._events || !this._events.error || a(this._events.error) && !this._events.error.length)) throw arguments[1] instanceof Error ? arguments[1] : new Error("Uncaught, unspecified 'error' event.");
                if (!this._events) return !1;
                var t = this._events[e];
                if (!t) return !1;
                if ("function" == typeof t) {
                    switch (arguments.length) {
                        case 1:
                            t.call(this);
                            break;
                        case 2:
                            t.call(this, arguments[1]);
                            break;
                        case 3:
                            t.call(this, arguments[1], arguments[2]);
                            break;
                        default:
                            var n = Array.prototype.slice.call(arguments, 1);
                            t.apply(this, n)
                    }
                    return !0
                }
                if (a(t)) {
                    for (var n = Array.prototype.slice.call(arguments, 1), r = t.slice(), o = 0, i = r.length; i > o; o++) r[o].apply(this, n);
                    return !0
                }
                return !1
            }, i.prototype.addListener = function(e, t) {
                if ("function" != typeof t) throw new Error("addListener only takes instances of Function");
                if (this._events || (this._events = {}), this.emit("newListener", e, t), this._events[e])
                    if (a(this._events[e])) {
                        if (!this._events[e].warned) {
                            var n;
                            n = void 0 !== this._events.maxListeners ? this._events.maxListeners : s, n && n > 0 && this._events[e].length > n && (this._events[e].warned = !0, console.error("(node) warning: possible EventEmitter memory leak detected. %d listeners added. Use emitter.setMaxListeners() to increase limit.", this._events[e].length), console.trace())
                        }
                        this._events[e].push(t)
                    } else this._events[e] = [this._events[e], t];
                else this._events[e] = t;
                return this
            }, i.prototype.on = i.prototype.addListener, i.prototype.once = function(e, t) {
                var n = this;
                return n.on(e, function r() {
                    n.removeListener(e, r), t.apply(this, arguments)
                }), this
            }, i.prototype.removeListener = function(e, t) {
                if ("function" != typeof t) throw new Error("removeListener only takes instances of Function");
                if (!this._events || !this._events[e]) return this;
                var n = this._events[e];
                if (a(n)) {
                    var o = r(n, t);
                    if (0 > o) return this;
                    n.splice(o, 1), 0 == n.length && delete this._events[e]
                } else this._events[e] === t && delete this._events[e];
                return this
            }, i.prototype.removeAllListeners = function(e) {
                return 0 === arguments.length ? (this._events = {}, this) : (e && this._events && this._events[e] && (this._events[e] = null), this)
            }, i.prototype.listeners = function(e) {
                return this._events || (this._events = {}), this._events[e] || (this._events[e] = []), a(this._events[e]) || (this._events[e] = [this._events[e]]), this._events[e]
            }, i.listenerCount = function(e, t) {
                var n;
                return n = e._events && e._events[t] ? "function" == typeof e._events[t] ? 1 : e._events[t].length : 0
            }
        }, {
            __browserify_process: 14
        }],
        13: [function(t, n) {
            "use strict";

            function r(e) {
                return new Promise(function(t, n) {
                    o(e, t, n)
                })
            }
            var o = null,
                i = null,
                a = null,
                s = null,
                c = null,
                u = null,
                p = {
                    log: function() {
                        "undefined" != typeof n || "function" == typeof t && "function" == typeof e || console.log.apply(console, arguments)
                    },
                    extractVersion: function(e, t, n) {
                        var r = e.match(t);
                        return r && r.length >= n && parseInt(r[n])
                    }
                };
            if ("object" == typeof window && (!window.HTMLMediaElement || "srcObject" in window.HTMLMediaElement.prototype || Object.defineProperty(window.HTMLMediaElement.prototype, "srcObject", {
                    get: function() {
                        return "mozSrcObject" in this ? this.mozSrcObject : this._srcObject
                    },
                    set: function(e) {
                        "mozSrcObject" in this ? this.mozSrcObject = e : (this._srcObject = e, this.src = URL.createObjectURL(e))
                    }
                }), o = window.navigator && window.navigator.getUserMedia), i = function(e, t) {
                    e.srcObject = t
                }, a = function(e, t) {
                    e.srcObject = t.srcObject
                }, "undefined" != typeof window && window.navigator)
                if (navigator.mozGetUserMedia && window.mozRTCPeerConnection) {
                    if (p.log("This appears to be Firefox"), s = "firefox", c = p.extractVersion(navigator.userAgent, /Firefox\/([0-9]+)\./, 1), u = 31, window.RTCPeerConnection = function(e, t) {
                            if (38 > c && e && e.iceServers) {
                                for (var n = [], r = 0; r < e.iceServers.length; r++) {
                                    var o = e.iceServers[r];
                                    if (o.hasOwnProperty("urls"))
                                        for (var i = 0; i < o.urls.length; i++) {
                                            var a = {
                                                url: o.urls[i]
                                            };
                                            0 === o.urls[i].indexOf("turn") && (a.username = o.username, a.credential = o.credential), n.push(a)
                                        } else n.push(e.iceServers[r])
                                }
                                e.iceServers = n
                            }
                            return new mozRTCPeerConnection(e, t)
                        }, window.RTCSessionDescription || (window.RTCSessionDescription = mozRTCSessionDescription), window.RTCIceCandidate || (window.RTCIceCandidate = mozRTCIceCandidate), o = function(e, t, n) {
                            var r = function(e) {
                                if ("object" != typeof e || e.require) return e;
                                var t = [];
                                return Object.keys(e).forEach(function(n) {
                                    if ("require" !== n && "advanced" !== n && "mediaSource" !== n) {
                                        var r = e[n] = "object" == typeof e[n] ? e[n] : {
                                            ideal: e[n]
                                        };
                                        if ((void 0 !== r.min || void 0 !== r.max || void 0 !== r.exact) && t.push(n), void 0 !== r.exact && ("number" == typeof r.exact ? r.min = r.max = r.exact : e[n] = r.exact, delete r.exact), void 0 !== r.ideal) {
                                            e.advanced = e.advanced || [];
                                            var o = {};
                                            o[n] = "number" == typeof r.ideal ? {
                                                min: r.ideal,
                                                max: r.ideal
                                            } : r.ideal, e.advanced.push(o), delete r.ideal, Object.keys(r).length || delete e[n]
                                        }
                                    }
                                }), t.length && (e.require = t), e
                            };
                            return 38 > c && (p.log("spec: " + JSON.stringify(e)), e.audio && (e.audio = r(e.audio)), e.video && (e.video = r(e.video)), p.log("ff37: " + JSON.stringify(e))), navigator.mozGetUserMedia(e, t, n)
                        }, navigator.getUserMedia = o, navigator.mediaDevices || (navigator.mediaDevices = {
                            getUserMedia: r,
                            addEventListener: function() {},
                            removeEventListener: function() {}
                        }), navigator.mediaDevices.enumerateDevices = navigator.mediaDevices.enumerateDevices || function() {
                            return new Promise(function(e) {
                                var t = [{
                                    kind: "audioinput",
                                    deviceId: "default",
                                    label: "",
                                    groupId: ""
                                }, {
                                    kind: "videoinput",
                                    deviceId: "default",
                                    label: "",
                                    groupId: ""
                                }];
                                e(t)
                            })
                        }, 41 > c) {
                        var d = navigator.mediaDevices.enumerateDevices.bind(navigator.mediaDevices);
                        navigator.mediaDevices.enumerateDevices = function() {
                            return d().then(void 0, function(e) {
                                if ("NotFoundError" === e.name) return [];
                                throw e
                            })
                        }
                    }
                } else if (navigator.webkitGetUserMedia && window.webkitRTCPeerConnection) {
                p.log("This appears to be Chrome"), s = "chrome", c = p.extractVersion(navigator.userAgent, /Chrom(e|ium)\/([0-9]+)\./, 2), u = 38, window.RTCPeerConnection = function(e, t) {
                    e && e.iceTransportPolicy && (e.iceTransports = e.iceTransportPolicy);
                    var n = new webkitRTCPeerConnection(e, t),
                        r = n.getStats.bind(n);
                    return n.getStats = function(e, t) {
                        var n = this,
                            o = arguments;
                        if (arguments.length > 0 && "function" == typeof e) return r(e, t);
                        var i = function(e) {
                            var t = {},
                                n = e.result();
                            return n.forEach(function(e) {
                                var n = {
                                    id: e.id,
                                    timestamp: e.timestamp,
                                    type: e.type
                                };
                                e.names().forEach(function(t) {
                                    n[t] = e.stat(t)
                                }), t[n.id] = n
                            }), t
                        };
                        if (arguments.length >= 2) {
                            var a = function(e) {
                                o[1](i(e))
                            };
                            return r.apply(this, [a, arguments[0]])
                        }
                        return new Promise(function(t, a) {
                            1 === o.length && null === e ? r.apply(n, [function(e) {
                                t.apply(null, [i(e)])
                            }, a]) : r.apply(n, [t, a])
                        })
                    }, n
                }, ["createOffer", "createAnswer"].forEach(function(e) {
                    var t = webkitRTCPeerConnection.prototype[e];
                    webkitRTCPeerConnection.prototype[e] = function() {
                        var e = this;
                        if (arguments.length < 1 || 1 === arguments.length && "object" == typeof arguments[0]) {
                            var n = 1 === arguments.length ? arguments[0] : void 0;
                            return new Promise(function(r, o) {
                                t.apply(e, [r, o, n])
                            })
                        }
                        return t.apply(this, arguments)
                    }
                }), ["setLocalDescription", "setRemoteDescription", "addIceCandidate"].forEach(function(e) {
                    var t = webkitRTCPeerConnection.prototype[e];
                    webkitRTCPeerConnection.prototype[e] = function() {
                        var e = arguments,
                            n = this;
                        return new Promise(function(r, o) {
                            t.apply(n, [e[0], function() {
                                r(), e.length >= 2 && e[1].apply(null, [])
                            }, function(t) {
                                o(t), e.length >= 3 && e[2].apply(null, [t])
                            }])
                        })
                    }
                });
                var f = function(e) {
                    if ("object" != typeof e || e.mandatory || e.optional) return e;
                    var t = {};
                    return Object.keys(e).forEach(function(n) {
                        if ("require" !== n && "advanced" !== n && "mediaSource" !== n) {
                            var r = "object" == typeof e[n] ? e[n] : {
                                ideal: e[n]
                            };
                            void 0 !== r.exact && "number" == typeof r.exact && (r.min = r.max = r.exact);
                            var o = function(e, t) {
                                return e ? e + t.charAt(0).toUpperCase() + t.slice(1) : "deviceId" === t ? "sourceId" : t
                            };
                            if (void 0 !== r.ideal) {
                                t.optional = t.optional || [];
                                var i = {};
                                "number" == typeof r.ideal ? (i[o("min", n)] = r.ideal, t.optional.push(i), i = {}, i[o("max", n)] = r.ideal, t.optional.push(i)) : (i[o("", n)] = r.ideal, t.optional.push(i))
                            }
                            void 0 !== r.exact && "number" != typeof r.exact ? (t.mandatory = t.mandatory || {}, t.mandatory[o("", n)] = r.exact) : ["min", "max"].forEach(function(e) {
                                void 0 !== r[e] && (t.mandatory = t.mandatory || {}, t.mandatory[o(e, n)] = r[e])
                            })
                        }
                    }), e.advanced && (t.optional = (t.optional || []).concat(e.advanced)), t
                };
                if (o = function(e, t, n) {
                        return e.audio && (e.audio = f(e.audio)), e.video && (e.video = f(e.video)), p.log("chrome: " + JSON.stringify(e)), navigator.webkitGetUserMedia(e, t, n)
                    }, navigator.getUserMedia = o, navigator.mediaDevices || (navigator.mediaDevices = {
                        getUserMedia: r,
                        enumerateDevices: function() {
                            return new Promise(function(e) {
                                var t = {
                                    audio: "audioinput",
                                    video: "videoinput"
                                };
                                return MediaStreamTrack.getSources(function(n) {
                                    e(n.map(function(e) {
                                        return {
                                            label: e.label,
                                            kind: t[e.kind],
                                            deviceId: e.id,
                                            groupId: ""
                                        }
                                    }))
                                })
                            })
                        }
                    }), navigator.mediaDevices.getUserMedia) {
                    var l = navigator.mediaDevices.getUserMedia.bind(navigator.mediaDevices);
                    navigator.mediaDevices.getUserMedia = function(e) {
                        return p.log("spec:   " + JSON.stringify(e)), e.audio = f(e.audio), e.video = f(e.video), p.log("chrome: " + JSON.stringify(e)), l(e)
                    }
                } else navigator.mediaDevices.getUserMedia = function(e) {
                    return r(e)
                };
                "undefined" == typeof navigator.mediaDevices.addEventListener && (navigator.mediaDevices.addEventListener = function() {
                    p.log("Dummy mediaDevices.addEventListener called.")
                }), "undefined" == typeof navigator.mediaDevices.removeEventListener && (navigator.mediaDevices.removeEventListener = function() {
                    p.log("Dummy mediaDevices.removeEventListener called.")
                }), i = function(e, t) {
                    c >= 43 ? e.srcObject = t : "undefined" != typeof e.src ? e.src = URL.createObjectURL(t) : p.log("Error attaching stream to element.")
                }, a = function(e, t) {
                    c >= 43 ? e.srcObject = t.srcObject : e.src = t.src
                }
            } else navigator.mediaDevices && navigator.userAgent.match(/Edge\/(\d+).(\d+)$/) ? (p.log("This appears to be Edge"), s = "edge", c = p.extractVersion(navigator.userAgent, /Edge\/(\d+).(\d+)$/, 2), u = 12) : p.log("Browser does not appear to be WebRTC-capable");
            else p.log("This does not appear to be a browser"), s = "not a browser";
            var h = {};
            try {
                Object.defineProperty(h, "version", {
                    set: function(e) {
                        c = e
                    }
                })
            } catch (g) {}
            if ("undefined" != typeof n) {
                var m;
                "undefined" != typeof window && (m = window.RTCPeerConnection), n.exports = {
                    RTCPeerConnection: m,
                    getUserMedia: o,
                    attachMediaStream: i,
                    reattachMediaStream: a,
                    webrtcDetectedBrowser: s,
                    webrtcDetectedVersion: c,
                    webrtcMinimumVersion: u,
                    webrtcTesting: h,
                    webrtcUtils: p
                }
            } else "function" == typeof t && "function" == typeof e && e([], function() {
                return {
                    RTCPeerConnection: window.RTCPeerConnection,
                    getUserMedia: o,
                    attachMediaStream: i,
                    reattachMediaStream: a,
                    webrtcDetectedBrowser: s,
                    webrtcDetectedVersion: c,
                    webrtcMinimumVersion: u,
                    webrtcTesting: h,
                    webrtcUtils: p
                }
            })
        }, {}],
        9: [function(e, t) {
            function n(e) {
                var t = this;
                a.call(this), this.id = e.id, this.parent = e.parent, this.type = e.type || "video", this.oneway = e.oneway || !1, this.sharemyscreen = e.sharemyscreen || !1, this.browserPrefix = e.prefix, this.stream = e.stream, this.enableDataChannels = void 0 === e.enableDataChannels ? this.parent.config.enableDataChannels : e.enableDataChannels, this.receiveMedia = e.receiveMedia || this.parent.config.receiveMedia, this.channels = {}, this.sid = e.sid || Date.now().toString(), this.pc = new i(this.parent.config.peerConnectionConfig, this.parent.config.peerConnectionConstraints), this.pc.on("ice", this.onIceCandidate.bind(this)), this.pc.on("endOfCandidates", function(e) {
                    t.send("endOfCandidates", e)
                }), this.pc.on("offer", function(e) {
                    t.parent.config.nick && (e.nick = t.parent.config.nick), t.send("offer", e)
                }), this.pc.on("answer", function(e) {
                    t.parent.config.nick && (e.nick = t.parent.config.nick), t.send("answer", e)
                }), this.pc.on("addStream", this.handleRemoteStreamAdded.bind(this)), this.pc.on("addChannel", this.handleDataChannelAdded.bind(this)), this.pc.on("removeStream", this.handleStreamRemoved.bind(this)), this.pc.on("negotiationNeeded", this.emit.bind(this, "negotiationNeeded")), this.pc.on("iceConnectionStateChange", this.emit.bind(this, "iceConnectionStateChange")), this.pc.on("iceConnectionStateChange", function() {
                    switch (t.pc.iceConnectionState) {
                        case "failed":
                            "offer" === t.pc.pc.peerconnection.localDescription.type && (t.parent.emit("iceFailed", t), t.send("connectivityError"))
                    }
                }), this.pc.on("signalingStateChange", this.emit.bind(this, "signalingStateChange")), this.logger = this.parent.logger, "screen" === e.type ? this.parent.localScreen && this.sharemyscreen && (this.logger.log("adding local screen stream to peer connection"), this.pc.addStream(this.parent.localScreen), this.broadcaster = e.broadcaster) : this.parent.localStreams.forEach(function(e) {
                    t.pc.addStream(e)
                }), this.on("channelOpen", function(e) {
                    e.protocol === c && (e.onmessage = function(n) {
                        var r = JSON.parse(n.data),
                            o = new s.Receiver;
                        o.receive(r, e), t.emit("fileTransfer", r, o), o.on("receivedFile", function() {
                            o.channel.close()
                        })
                    })
                }), this.on("*", function() {
                    t.parent.emit.apply(t.parent, arguments)
                })
            }
            var r = e("util"),
                o = e("webrtcsupport"),
                i = e("rtcpeerconnection"),
                a = e("wildemitter"),
                s = e("filetransfer"),
                c = "https://simplewebrtc.com/protocol/filetransfer#inband-v1";
            r.inherits(n, a), n.prototype.handleMessage = function(e) {
                var t = this;
                if (this.logger.log("getting", e.type, e), e.prefix && (this.browserPrefix = e.prefix), "offer" === e.type) this.nick || (this.nick = e.payload.nick), delete e.payload.nick, this.pc.handleOffer(e.payload, function(e) {
                    e || t.pc.answer(function() {})
                });
                else if ("answer" === e.type) this.nick || (this.nick = e.payload.nick), delete e.payload.nick, this.pc.handleAnswer(e.payload);
                else if ("candidate" === e.type) this.pc.processIce(e.payload);
                else if ("connectivityError" === e.type) this.parent.emit("connectivityError", t);
                else if ("mute" === e.type) this.parent.emit("mute", {
                    id: e.from,
                    name: e.payload.name
                });
                else if ("unmute" === e.type) this.parent.emit("unmute", {
                    id: e.from,
                    name: e.payload.name
                });
                else if ("endOfCandidates" === e.type) {
                    var n = this.pc.pc.peerconnection.transceivers || [];
                    n.forEach(function(e) {
                        e.iceTransport && e.iceTransport.addRemoteCandidate({})
                    })
                }
            }, n.prototype.send = function(e, t) {
                var n = {
                    to: this.id,
                    sid: this.sid,
                    broadcaster: this.broadcaster,
                    roomType: this.type,
                    type: e,
                    payload: t,
                    prefix: o.prefix
                };
                this.logger.log("sending", e, n), this.parent.emit("message", n)
            }, n.prototype.sendDirectly = function(e, t, n) {
                var r = {
                    type: t,
                    payload: n
                };
                this.logger.log("sending via datachannel", e, t, r);
                var o = this.getDataChannel(e);
                return "open" != o.readyState ? !1 : (o.send(JSON.stringify(r)), !0)
            }, n.prototype._observeDataChannel = function(e) {
                var t = this;
                e.onclose = this.emit.bind(this, "channelClose", e), e.onerror = this.emit.bind(this, "channelError", e), e.onmessage = function(n) {
                    t.emit("channelMessage", t, e.label, JSON.parse(n.data), e, n)
                }, e.onopen = this.emit.bind(this, "channelOpen", e)
            }, n.prototype.getDataChannel = function(e, t) {
                if (!o.supportDataChannel) return this.emit("error", new Error("createDataChannel not supported"));
                var n = this.channels[e];
                return t || (t = {}), n ? n : (n = this.channels[e] = this.pc.createDataChannel(e, t), this._observeDataChannel(n), n)
            }, n.prototype.onIceCandidate = function(e) {
                if (!this.closed)
                    if (e) {
                        var t = this.parent.config.peerConnectionConfig;
                        "moz" === o.prefix && t && t.iceTransports && e.candidate && e.candidate.candidate && e.candidate.candidate.indexOf(t.iceTransports) < 0 ? this.logger.log("Ignoring ice candidate not matching pcConfig iceTransports type: ", t.iceTransports) : this.send("candidate", e)
                    } else this.logger.log("End of candidates.")
            }, n.prototype.start = function() {
                this.enableDataChannels && this.getDataChannel("simplewebrtc"), this.pc.offer(this.receiveMedia, function() {})
            }, n.prototype.icerestart = function() {
                var e = this.receiveMedia;
                e.mandatory.IceRestart = !0, this.pc.offer(e, function() {})
            }, n.prototype.end = function() {
                this.closed || (this.pc.close(), this.handleStreamRemoved())
            }, n.prototype.handleRemoteStreamAdded = function(e) {
                var t = this;
                this.stream ? this.logger.warn("Already have a remote stream") : (this.stream = e.stream, this.stream.onended = function() {
                    t.end()
                }, this.parent.emit("peerStreamAdded", this))
            }, n.prototype.handleStreamRemoved = function() {
                this.parent.peers.splice(this.parent.peers.indexOf(this), 1), this.closed = !0, this.parent.emit("peerStreamRemoved", this)
            }, n.prototype.handleDataChannelAdded = function(e) {
                this.channels[e.label] = e, this._observeDataChannel(e)
            }, n.prototype.sendFile = function(e) {
                var t = new s.Sender,
                    n = this.getDataChannel("filetransfer" + (new Date).getTime(), {
                        protocol: c
                    });
                return n.onopen = function() {
                    n.send(JSON.stringify({
                        size: e.size,
                        name: e.name
                    })), t.send(e, n)
                }, n.onclose = function() {
                    console.log("sender received transfer"), t.emit("complete")
                }, t
            }, t.exports = n
        }, {
            filetransfer: 15,
            rtcpeerconnection: 16,
            util: 8,
            webrtcsupport: 7,
            wildemitter: 4
        }],
        11: [function(e, t) {
            t.exports = e("./lib/")
        }, {
            "./lib/": 17
        }],
        18: [function(e, t) {
            var n, r;
            window.mozRTCPeerConnection || navigator.mozGetUserMedia ? (n = "moz", r = parseInt(navigator.userAgent.match(/Firefox\/([0-9]+)\./)[1], 10)) : (window.webkitRTCPeerConnection || navigator.webkitGetUserMedia) && (n = "webkit", r = navigator.userAgent.match(/Chrom(e|ium)/) && parseInt(navigator.userAgent.match(/Chrom(e|ium)\/([0-9]+)\./)[2], 10));
            var o = window.mozRTCPeerConnection || window.webkitRTCPeerConnection,
                i = window.mozRTCIceCandidate || window.RTCIceCandidate,
                a = window.mozRTCSessionDescription || window.RTCSessionDescription,
                s = window.webkitMediaStream || window.MediaStream,
                c = "https:" === window.location.protocol && ("webkit" === n && r >= 26 || "moz" === n && r >= 33),
                u = window.AudioContext || window.webkitAudioContext,
                p = document.createElement("video"),
                d = p && p.canPlayType && "probably" === p.canPlayType('video/webm; codecs="vp8", vorbis'),
                f = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.msGetUserMedia || navigator.mozGetUserMedia;
            t.exports = {
                prefix: n,
                browserVersion: r,
                support: !!o && d && !!f,
                supportRTCPeerConnection: !!o,
                supportVp8: d,
                supportGetUserMedia: !!f,
                supportDataChannel: !!(o && o.prototype && o.prototype.createDataChannel),
                supportWebAudio: !(!u || !u.prototype.createMediaStreamSource),
                supportMediaStream: !(!s || !s.prototype.removeTrack),
                supportScreenSharing: !!c,
                dataChannel: !!(o && o.prototype && o.prototype.createDataChannel),
                webAudio: !(!u || !u.prototype.createMediaStreamSource),
                mediaStream: !(!s || !s.prototype.removeTrack),
                screenSharing: !!c,
                AudioContext: u,
                PeerConnection: o,
                SessionDescription: a,
                IceCandidate: i,
                MediaStream: s,
                getUserMedia: f
            }
        }, {}],
        10: [function(e, t) {
            function n(e) {
                c.call(this);
                var t, n = this.config = {
                    autoAdjustMic: !1,
                    detectSpeakingEvents: !1,
                    audioFallback: !1,
                    media: {
                        audio: !0,
                        video: !0
                    },
                    logger: p
                };
                for (t in e) this.config[t] = e[t];
                this.logger = n.logger, this._log = this.logger.log.bind(this.logger, "LocalMedia:"), this._logerror = this.logger.error.bind(this.logger, "LocalMedia:"), this.screenSharingSupport = i.screenSharing, this.localStreams = [], this.localScreens = [], i.support || this._logerror("Your browser does not support local media capture.")
            }
            var r = e("util"),
                o = e("hark"),
                i = e("webrtcsupport"),
                a = e("getusermedia"),
                s = e("getscreenmedia"),
                c = e("wildemitter"),
                u = e("mediastream-gain"),
                p = e("mockconsole");
            r.inherits(n, c), n.prototype.start = function(e, t) {
                var n = this,
                    r = e || this.config.media;
                a(r, function(e, o) {
                    if (e) {
                        if (n.config.audioFallback && "DevicesNotFoundError" === e.name && r.video !== !1) return r.video = !1, n.start(r, t), void 0
                    } else r.audio && n.config.detectSpeakingEvents && n.setupAudioMonitor(o, n.config.harkOptions), n.localStreams.push(o), n.config.autoAdjustMic && (n.gainController = new u(o), n.setMicIfEnabled(.5)), o.onended = function() {}, n.emit("localStream", o);
                    return t ? t(e, o) : void 0
                })
            }, n.prototype.stop = function(e) {
                var t = this;
                if (e) {
                    e.getTracks().forEach(function(e) {
                        e.stop()
                    });
                    var n = t.localStreams.indexOf(e);
                    n > -1 ? (t.emit("localStreamStopped", e), t.localStreams = t.localStreams.splice(n, 1)) : (n = t.localScreens.indexOf(e), n > -1 && (t.emit("localScreenStopped", e), t.localScreens = t.localScreens.splice(n, 1)))
                } else this.stopStreams(), this.stopScreenShare()
            }, n.prototype.stopStreams = function() {
                var e = this;
                this.audioMonitor && (this.audioMonitor.stop(), delete this.audioMonitor), this.localStreams.forEach(function(t) {
                    t.getTracks().forEach(function(e) {
                        e.stop()
                    }), e.emit("localStreamStopped", t)
                }), this.localStreams = []
            }, n.prototype.startScreenShare = function(e) {
                var t = this;
                s(function(n, r) {
                    return n || (t.localScreens.push(r), r.onended = function() {
                        var e = t.localScreens.indexOf(r);
                        e > -1 && t.localScreens.splice(e, 1), t.emit("localScreenStopped", r)
                    }, t.emit("localScreen", r)), e ? e(n, r) : void 0
                })
            }, n.prototype.stopScreenShare = function(e) {
                var t = this;
                e ? (e.getTracks().forEach(function(e) {
                    e.stop()
                }), this.emit("localScreenStopped", e)) : (this.localScreens.forEach(function(e) {
                    e.getTracks().forEach(function(e) {
                        e.stop()
                    }), t.emit("localScreenStopped", e)
                }), this.localScreens = [])
            }, n.prototype.mute = function() {
                this._audioEnabled(!1), this.hardMuted = !0, this.emit("audioOff")
            }, n.prototype.unmute = function() {
                this._audioEnabled(!0), this.hardMuted = !1, this.emit("audioOn")
            }, n.prototype.setupAudioMonitor = function(e, t) {
                this._log("Setup audio");
                var n, r = this.audioMonitor = o(e, t),
                    i = this;
                r.on("speaking", function() {
                    i.emit("speaking"), i.hardMuted || i.setMicIfEnabled(1)
                }), r.on("stopped_speaking", function() {
                    n && clearTimeout(n), n = setTimeout(function() {
                        i.emit("stoppedSpeaking"), i.hardMuted || i.setMicIfEnabled(.5)
                    }, 1e3)
                }), r.on("volume_change", function(e, t) {
                    i.emit("volumeChange", e, t)
                })
            }, n.prototype.setMicIfEnabled = function(e) {
                this.config.autoAdjustMic && this.gainController.setGain(e)
            }, n.prototype.pauseVideo = function() {
                this._videoEnabled(!1), this.emit("videoOff")
            }, n.prototype.resumeVideo = function() {
                this._videoEnabled(!0), this.emit("videoOn")
            }, n.prototype.pause = function() {
                this.mute(), this.pauseVideo()
            }, n.prototype.resume = function() {
                this.unmute(), this.resumeVideo()
            }, n.prototype._audioEnabled = function(e) {
                this.setMicIfEnabled(e ? 1 : 0), this.localStreams.forEach(function(t) {
                    t.getAudioTracks().forEach(function(t) {
                        t.enabled = !!e
                    })
                })
            }, n.prototype._videoEnabled = function(e) {
                this.localStreams.forEach(function(t) {
                    t.getVideoTracks().forEach(function(t) {
                        t.enabled = !!e
                    })
                })
            }, n.prototype.isAudioEnabled = function() {
                var e = !0;
                return this.localStreams.forEach(function(t) {
                    t.getAudioTracks().forEach(function(t) {
                        e = e && t.enabled
                    })
                }), e
            }, n.prototype.isVideoEnabled = function() {
                var e = !0;
                return this.localStreams.forEach(function(t) {
                    t.getVideoTracks().forEach(function(t) {
                        e = e && t.enabled
                    })
                }), e
            }, n.prototype.startLocalMedia = n.prototype.start, n.prototype.stopLocalMedia = n.prototype.stop, Object.defineProperty(n.prototype, "localStream", {
                get: function() {
                    return this.localStreams.length > 0 ? this.localStreams[0] : null
                }
            }), Object.defineProperty(n.prototype, "localScreen", {
                get: function() {
                    return this.localScreens.length > 0 ? this.localScreens[0] : null
                }
            }), t.exports = n
        }, {
            getscreenmedia: 21,
            getusermedia: 20,
            hark: 19,
            "mediastream-gain": 22,
            mockconsole: 6,
            util: 8,
            webrtcsupport: 18,
            wildemitter: 4
        }],
        23: [function(t, n) {
            "use strict";

            function r(e) {
                return new Promise(function(t, n) {
                    o(e, t, n)
                })
            }
            var o = null,
                i = null,
                a = null,
                s = null,
                c = null,
                u = null,
                p = {
                    log: function() {
                        "undefined" != typeof n || "function" == typeof t && "function" == typeof e || console.log.apply(console, arguments)
                    },
                    extractVersion: function(e, t, n) {
                        var r = e.match(t);
                        return r && r.length >= n && parseInt(r[n])
                    }
                };
            if ("object" == typeof window && (!window.HTMLMediaElement || "srcObject" in window.HTMLMediaElement.prototype || Object.defineProperty(window.HTMLMediaElement.prototype, "srcObject", {
                    get: function() {
                        return "mozSrcObject" in this ? this.mozSrcObject : this._srcObject
                    },
                    set: function(e) {
                        "mozSrcObject" in this ? this.mozSrcObject = e : (this._srcObject = e, this.src = URL.createObjectURL(e))
                    }
                }), o = window.navigator && window.navigator.getUserMedia), i = function(e, t) {
                    e.srcObject = t
                }, a = function(e, t) {
                    e.srcObject = t.srcObject
                }, "undefined" != typeof window && window.navigator)
                if (navigator.mozGetUserMedia && window.mozRTCPeerConnection) {
                    if (p.log("This appears to be Firefox"), s = "firefox", c = p.extractVersion(navigator.userAgent, /Firefox\/([0-9]+)\./, 1), u = 31, window.RTCPeerConnection = function(e, t) {
                            if (38 > c && e && e.iceServers) {
                                for (var n = [], r = 0; r < e.iceServers.length; r++) {
                                    var o = e.iceServers[r];
                                    if (o.hasOwnProperty("urls"))
                                        for (var i = 0; i < o.urls.length; i++) {
                                            var a = {
                                                url: o.urls[i]
                                            };
                                            0 === o.urls[i].indexOf("turn") && (a.username = o.username, a.credential = o.credential), n.push(a)
                                        } else n.push(e.iceServers[r])
                                }
                                e.iceServers = n
                            }
                            return new mozRTCPeerConnection(e, t)
                        }, window.RTCSessionDescription || (window.RTCSessionDescription = mozRTCSessionDescription), window.RTCIceCandidate || (window.RTCIceCandidate = mozRTCIceCandidate), o = function(e, t, n) {
                            var r = function(e) {
                                if ("object" != typeof e || e.require) return e;
                                var t = [];
                                return Object.keys(e).forEach(function(n) {
                                    if ("require" !== n && "advanced" !== n && "mediaSource" !== n) {
                                        var r = e[n] = "object" == typeof e[n] ? e[n] : {
                                            ideal: e[n]
                                        };
                                        if ((void 0 !== r.min || void 0 !== r.max || void 0 !== r.exact) && t.push(n), void 0 !== r.exact && ("number" == typeof r.exact ? r.min = r.max = r.exact : e[n] = r.exact, delete r.exact), void 0 !== r.ideal) {
                                            e.advanced = e.advanced || [];
                                            var o = {};
                                            o[n] = "number" == typeof r.ideal ? {
                                                min: r.ideal,
                                                max: r.ideal
                                            } : r.ideal, e.advanced.push(o), delete r.ideal, Object.keys(r).length || delete e[n]
                                        }
                                    }
                                }), t.length && (e.require = t), e
                            };
                            return 38 > c && (p.log("spec: " + JSON.stringify(e)), e.audio && (e.audio = r(e.audio)), e.video && (e.video = r(e.video)), p.log("ff37: " + JSON.stringify(e))), navigator.mozGetUserMedia(e, t, n)
                        }, navigator.getUserMedia = o, navigator.mediaDevices || (navigator.mediaDevices = {
                            getUserMedia: r,
                            addEventListener: function() {},
                            removeEventListener: function() {}
                        }), navigator.mediaDevices.enumerateDevices = navigator.mediaDevices.enumerateDevices || function() {
                            return new Promise(function(e) {
                                var t = [{
                                    kind: "audioinput",
                                    deviceId: "default",
                                    label: "",
                                    groupId: ""
                                }, {
                                    kind: "videoinput",
                                    deviceId: "default",
                                    label: "",
                                    groupId: ""
                                }];
                                e(t)
                            })
                        }, 41 > c) {
                        var d = navigator.mediaDevices.enumerateDevices.bind(navigator.mediaDevices);
                        navigator.mediaDevices.enumerateDevices = function() {
                            return d().then(void 0, function(e) {
                                if ("NotFoundError" === e.name) return [];
                                throw e
                            })
                        }
                    }
                } else if (navigator.webkitGetUserMedia && window.webkitRTCPeerConnection) {
                p.log("This appears to be Chrome"), s = "chrome", c = p.extractVersion(navigator.userAgent, /Chrom(e|ium)\/([0-9]+)\./, 2), u = 38, window.RTCPeerConnection = function(e, t) {
                    e && e.iceTransportPolicy && (e.iceTransports = e.iceTransportPolicy);
                    var n = new webkitRTCPeerConnection(e, t),
                        r = n.getStats.bind(n);
                    return n.getStats = function(e, t) {
                        var n = this,
                            o = arguments;
                        if (arguments.length > 0 && "function" == typeof e) return r(e, t);
                        var i = function(e) {
                            var t = {},
                                n = e.result();
                            return n.forEach(function(e) {
                                var n = {
                                    id: e.id,
                                    timestamp: e.timestamp,
                                    type: e.type
                                };
                                e.names().forEach(function(t) {
                                    n[t] = e.stat(t)
                                }), t[n.id] = n
                            }), t
                        };
                        if (arguments.length >= 2) {
                            var a = function(e) {
                                o[1](i(e))
                            };
                            return r.apply(this, [a, arguments[0]])
                        }
                        return new Promise(function(t, a) {
                            1 === o.length && null === e ? r.apply(n, [function(e) {
                                t.apply(null, [i(e)])
                            }, a]) : r.apply(n, [t, a])
                        })
                    }, n
                }, ["createOffer", "createAnswer"].forEach(function(e) {
                    var t = webkitRTCPeerConnection.prototype[e];
                    webkitRTCPeerConnection.prototype[e] = function() {
                        var e = this;
                        if (arguments.length < 1 || 1 === arguments.length && "object" == typeof arguments[0]) {
                            var n = 1 === arguments.length ? arguments[0] : void 0;
                            return new Promise(function(r, o) {
                                t.apply(e, [r, o, n])
                            })
                        }
                        return t.apply(this, arguments)
                    }
                }), ["setLocalDescription", "setRemoteDescription", "addIceCandidate"].forEach(function(e) {
                    var t = webkitRTCPeerConnection.prototype[e];
                    webkitRTCPeerConnection.prototype[e] = function() {
                        var e = arguments,
                            n = this;
                        return new Promise(function(r, o) {
                            t.apply(n, [e[0], function() {
                                r(), e.length >= 2 && e[1].apply(null, [])
                            }, function(t) {
                                o(t), e.length >= 3 && e[2].apply(null, [t])
                            }])
                        })
                    }
                });
                var f = function(e) {
                    if ("object" != typeof e || e.mandatory || e.optional) return e;
                    var t = {};
                    return Object.keys(e).forEach(function(n) {
                        if ("require" !== n && "advanced" !== n && "mediaSource" !== n) {
                            var r = "object" == typeof e[n] ? e[n] : {
                                ideal: e[n]
                            };
                            void 0 !== r.exact && "number" == typeof r.exact && (r.min = r.max = r.exact);
                            var o = function(e, t) {
                                return e ? e + t.charAt(0).toUpperCase() + t.slice(1) : "deviceId" === t ? "sourceId" : t
                            };
                            if (void 0 !== r.ideal) {
                                t.optional = t.optional || [];
                                var i = {};
                                "number" == typeof r.ideal ? (i[o("min", n)] = r.ideal, t.optional.push(i), i = {}, i[o("max", n)] = r.ideal, t.optional.push(i)) : (i[o("", n)] = r.ideal, t.optional.push(i))
                            }
                            void 0 !== r.exact && "number" != typeof r.exact ? (t.mandatory = t.mandatory || {}, t.mandatory[o("", n)] = r.exact) : ["min", "max"].forEach(function(e) {
                                void 0 !== r[e] && (t.mandatory = t.mandatory || {}, t.mandatory[o(e, n)] = r[e])
                            })
                        }
                    }), e.advanced && (t.optional = (t.optional || []).concat(e.advanced)), t
                };
                if (o = function(e, t, n) {
                        return e.audio && (e.audio = f(e.audio)), e.video && (e.video = f(e.video)), p.log("chrome: " + JSON.stringify(e)), navigator.webkitGetUserMedia(e, t, n)
                    }, navigator.getUserMedia = o, navigator.mediaDevices || (navigator.mediaDevices = {
                        getUserMedia: r,
                        enumerateDevices: function() {
                            return new Promise(function(e) {
                                var t = {
                                    audio: "audioinput",
                                    video: "videoinput"
                                };
                                return MediaStreamTrack.getSources(function(n) {
                                    e(n.map(function(e) {
                                        return {
                                            label: e.label,
                                            kind: t[e.kind],
                                            deviceId: e.id,
                                            groupId: ""
                                        }
                                    }))
                                })
                            })
                        }
                    }), navigator.mediaDevices.getUserMedia) {
                    var l = navigator.mediaDevices.getUserMedia.bind(navigator.mediaDevices);
                    navigator.mediaDevices.getUserMedia = function(e) {
                        return p.log("spec:   " + JSON.stringify(e)), e.audio = f(e.audio), e.video = f(e.video), p.log("chrome: " + JSON.stringify(e)), l(e)
                    }
                } else navigator.mediaDevices.getUserMedia = function(e) {
                    return r(e)
                };
                "undefined" == typeof navigator.mediaDevices.addEventListener && (navigator.mediaDevices.addEventListener = function() {
                    p.log("Dummy mediaDevices.addEventListener called.")
                }), "undefined" == typeof navigator.mediaDevices.removeEventListener && (navigator.mediaDevices.removeEventListener = function() {
                    p.log("Dummy mediaDevices.removeEventListener called.")
                }), i = function(e, t) {
                    c >= 43 ? e.srcObject = t : "undefined" != typeof e.src ? e.src = URL.createObjectURL(t) : p.log("Error attaching stream to element.")
                }, a = function(e, t) {
                    c >= 43 ? e.srcObject = t.srcObject : e.src = t.src
                }
            } else navigator.mediaDevices && navigator.userAgent.match(/Edge\/(\d+).(\d+)$/) ? (p.log("This appears to be Edge"), s = "edge", c = p.extractVersion(navigator.userAgent, /Edge\/(\d+).(\d+)$/, 2), u = 12) : p.log("Browser does not appear to be WebRTC-capable");
            else p.log("This does not appear to be a browser"), s = "not a browser";
            var h = {};
            try {
                Object.defineProperty(h, "version", {
                    set: function(e) {
                        c = e
                    }
                })
            } catch (g) {}
            if ("undefined" != typeof n) {
                var m;
                "undefined" != typeof window && (m = window.RTCPeerConnection), n.exports = {
                    RTCPeerConnection: m,
                    getUserMedia: o,
                    attachMediaStream: i,
                    reattachMediaStream: a,
                    webrtcDetectedBrowser: s,
                    webrtcDetectedVersion: c,
                    webrtcMinimumVersion: u,
                    webrtcTesting: h,
                    webrtcUtils: p
                }
            } else "function" == typeof t && "function" == typeof e && e([], function() {
                return {
                    RTCPeerConnection: window.RTCPeerConnection,
                    getUserMedia: o,
                    attachMediaStream: i,
                    reattachMediaStream: a,
                    webrtcDetectedBrowser: s,
                    webrtcDetectedVersion: c,
                    webrtcMinimumVersion: u,
                    webrtcTesting: h,
                    webrtcUtils: p
                }
            })
        }, {}],
        15: [function(e, t) {
            function n(e) {
                o.call(this);
                var t = e || {};
                this.config = {
                    chunksize: 16384,
                    pacing: 0
                };
                var n;
                for (n in t) this.config[n] = t[n];
                this.file = null, this.channel = null
            }

            function r() {
                o.call(this), this.receiveBuffer = [], this.received = 0, this.metadata = {}, this.channel = null
            }
            var o = e("wildemitter"),
                i = e("util");
            i.inherits(n, o), n.prototype.send = function(e, t) {
                var n = this;
                this.file = e, this.channel = t;
                var r = function(t) {
                    var o = new window.FileReader;
                    o.onload = function() {
                        return function(o) {
                            n.channel.send(o.target.result), n.emit("progress", t, e.size, o.target.result), e.size > t + o.target.result.byteLength ? window.setTimeout(r, n.config.pacing, t + n.config.chunksize) : (n.emit("progress", e.size, e.size, null), n.emit("sentFile"))
                        }
                    }(e);
                    var i = e.slice(t, t + n.config.chunksize);
                    o.readAsArrayBuffer(i)
                };
                window.setTimeout(r, 0, 0)
            }, i.inherits(r, o), r.prototype.receive = function(e, t) {
                var n = this;
                e && (this.metadata = e), this.channel = t, t.binaryType = "arraybuffer", this.channel.onmessage = function(e) {
                    var t = e.data.byteLength;
                    n.received += t, n.receiveBuffer.push(e.data), n.emit("progress", n.received, n.metadata.size, e.data), n.received === n.metadata.size ? (n.emit("receivedFile", new window.Blob(n.receiveBuffer), n.metadata), n.receiveBuffer = []) : n.received > n.metadata.size && (console.error("received more than expected, discarding..."), n.receiveBuffer = [])
                }
            }, t.exports = {}, t.exports.support = "undefined" != typeof window && window && window.File && window.FileReader && window.Blob, t.exports.Sender = n, t.exports.Receiver = r
        }, {
            util: 8,
            wildemitter: 4
        }],
        16: [function(e, t) {
            function n(e, t) {
                var n, r = this;
                s.call(this), e = e || {}, e.iceServers = e.iceServers || [], this.enableChromeNativeSimulcast = !1, t && t.optional && "chrome" === u.webrtcDetectedBrowser && null === navigator.appVersion.match(/Chromium\//) && t.optional.forEach(function(e) {
                    e.enableChromeNativeSimulcast && (r.enableChromeNativeSimulcast = !0)
                }), this.enableMultiStreamHacks = !1, t && t.optional && "chrome" === u.webrtcDetectedBrowser && t.optional.forEach(function(e) {
                    e.enableMultiStreamHacks && (r.enableMultiStreamHacks = !0)
                }), this.restrictBandwidth = 0, t && t.optional && t.optional.forEach(function(e) {
                    e.andyetRestrictBandwidth && (r.restrictBandwidth = e.andyetRestrictBandwidth)
                }), this.batchIceCandidates = 0, t && t.optional && t.optional.forEach(function(e) {
                    e.andyetBatchIce && (r.batchIceCandidates = e.andyetBatchIce)
                }), this.batchedIceCandidates = [], t && t.optional && "chrome" === u.webrtcDetectedBrowser && t.optional.forEach(function(e) {
                    e.andyetFasterICE && (r.eliminateDuplicateCandidates = e.andyetFasterICE)
                }), t && t.optional && t.optional.forEach(function(e) {
                    e.andyetDontSignalCandidates && (r.dontSignalCandidates = e.andyetDontSignalCandidates)
                }), this.assumeSetLocalSuccess = !1, t && t.optional && t.optional.forEach(function(e) {
                    e.andyetAssumeSetLocalSuccess && (r.assumeSetLocalSuccess = e.andyetAssumeSetLocalSuccess)
                }), "firefox" === u.webrtcDetectedBrowser && t && t.optional && (this.wtFirefox = 0, t.optional.forEach(function(e) {
                    e.andyetFirefoxMakesMeSad && (r.wtFirefox = e.andyetFirefoxMakesMeSad, r.wtFirefox > 0 && (r.firefoxcandidatebuffer = []))
                })), this.pc = new c(e, t), this.getLocalStreams = this.pc.getLocalStreams.bind(this.pc), this.getRemoteStreams = this.pc.getRemoteStreams.bind(this.pc), this.addStream = this.pc.addStream.bind(this.pc), this.removeStream = this.pc.removeStream.bind(this.pc), this.pc.on("*", function() {
                    r.emit.apply(r, arguments)
                }), this.pc.onremovestream = this.emit.bind(this, "removeStream"), this.pc.onaddstream = this.emit.bind(this, "addStream"), this.pc.onnegotiationneeded = this.emit.bind(this, "negotiationNeeded"), this.pc.oniceconnectionstatechange = this.emit.bind(this, "iceConnectionStateChange"), this.pc.onsignalingstatechange = this.emit.bind(this, "signalingStateChange"), this.pc.onicecandidate = this._onIce.bind(this), this.pc.ondatachannel = this._onDataChannel.bind(this), this.localDescription = {
                    contents: []
                }, this.remoteDescription = {
                    contents: []
                }, this.config = {
                    debug: !1,
                    ice: {},
                    sid: "",
                    isInitiator: !0,
                    sdpSessionID: Date.now(),
                    useJingle: !1
                };
                for (n in e) this.config[n] = e[n];
                this.config.debug && this.on("*", function() {
                    var t = e.logger || console;
                    t.log("PeerConnection event:", arguments)
                }), this.hadLocalStunCandidate = !1, this.hadRemoteStunCandidate = !1, this.hadLocalRelayCandidate = !1, this.hadRemoteRelayCandidate = !1, this.hadLocalIPv6Candidate = !1, this.hadRemoteIPv6Candidate = !1, this._remoteDataChannels = [], this._localDataChannels = [], this._candidateBuffer = []
            }
            var r = e("util"),
                o = e("lodash.foreach"),
                i = e("lodash.pluck"),
                a = e("sdp-jingle-json"),
                s = e("wildemitter"),
                c = e("traceablepeerconnection"),
                u = e("webrtc-adapter-test");
            r.inherits(n, s), Object.defineProperty(n.prototype, "signalingState", {
                get: function() {
                    return this.pc.signalingState
                }
            }), Object.defineProperty(n.prototype, "iceConnectionState", {
                get: function() {
                    return this.pc.iceConnectionState
                }
            }), n.prototype._role = function() {
                return this.isInitiator ? "initiator" : "responder"
            }, n.prototype.addStream = function(e) {
                this.localStream = e, this.pc.addStream(e)
            }, n.prototype._checkLocalCandidate = function(e) {
                var t = a.toCandidateJSON(e);
                "srflx" == t.type ? this.hadLocalStunCandidate = !0 : "relay" == t.type && (this.hadLocalRelayCandidate = !0), -1 != t.ip.indexOf(":") && (this.hadLocalIPv6Candidate = !0)
            }, n.prototype._checkRemoteCandidate = function(e) {
                var t = a.toCandidateJSON(e);
                "srflx" == t.type ? this.hadRemoteStunCandidate = !0 : "relay" == t.type && (this.hadRemoteRelayCandidate = !0), -1 != t.ip.indexOf(":") && (this.hadRemoteIPv6Candidate = !0)
            }, n.prototype.processIce = function(e, t) {
                t = t || function() {};
                var n = this;
                if ("closed" === this.pc.signalingState) return t();
                if (e.contents || e.jingle && e.jingle.contents) {
                    var r = i(this.remoteDescription.contents, "name"),
                        o = e.contents || e.jingle.contents;
                    o.forEach(function(e) {
                        var t = e.transport || {},
                            o = t.candidates || [],
                            i = r.indexOf(e.name),
                            s = e.name;
                        o.forEach(function(e) {
                            var t = a.toCandidateSDP(e) + "\r\n";
                            n.pc.addIceCandidate(new RTCIceCandidate({
                                candidate: t,
                                sdpMLineIndex: i,
                                sdpMid: s
                            }), function() {}, function(e) {
                                n.emit("error", e)
                            }), n._checkRemoteCandidate(t)
                        })
                    })
                } else {
                    if (e.candidate && 0 !== e.candidate.candidate.indexOf("a=") && (e.candidate.candidate = "a=" + e.candidate.candidate), this.wtFirefox && null !== this.firefoxcandidatebuffer && this.pc.localDescription && "offer" === this.pc.localDescription.type) return this.firefoxcandidatebuffer.push(e.candidate), t();
                    n.pc.addIceCandidate(new RTCIceCandidate(e.candidate), function() {}, function(e) {
                        n.emit("error", e)
                    }), n._checkRemoteCandidate(e.candidate.candidate)
                }
                t()
            }, n.prototype.offer = function(e, t) {
                var n = this,
                    r = 2 === arguments.length,
                    i = r && e ? e : {
                        mandatory: {
                            OfferToReceiveAudio: !0,
                            OfferToReceiveVideo: !0
                        }
                    };
                return t = r ? t : e, t = t || function() {}, "closed" === this.pc.signalingState ? t("Already closed") : (this.pc.createOffer(function(e) {
                    var r = {
                        type: "offer",
                        sdp: e.sdp
                    };
                    n.assumeSetLocalSuccess && (n.emit("offer", r), t(null, r)), n._candidateBuffer = [], n.pc.setLocalDescription(e, function() {
                        var i;
                        n.config.useJingle && (i = a.toSessionJSON(e.sdp, {
                            role: n._role(),
                            direction: "outgoing"
                        }), i.sid = n.config.sid, n.localDescription = i, o(i.contents, function(e) {
                            var t = e.transport || {};
                            t.ufrag && (n.config.ice[e.name] = {
                                ufrag: t.ufrag,
                                pwd: t.pwd
                            })
                        }), r.jingle = i), r.sdp.split("\r\n").forEach(function(e) {
                            0 === e.indexOf("a=candidate:") && n._checkLocalCandidate(e)
                        }), n.assumeSetLocalSuccess || (n.emit("offer", r), t(null, r))
                    }, function(e) {
                        n.emit("error", e), t(e)
                    })
                }, function(e) {
                    n.emit("error", e), t(e)
                }, i), void 0)
            }, n.prototype.handleOffer = function(e, t) {
                t = t || function() {};
                var n = this;
                if (e.type = "offer", e.jingle) {
                    if (this.enableChromeNativeSimulcast && e.jingle.contents.forEach(function(e) {
                            "video" === e.name && (e.description.googConferenceFlag = !0)
                        }), this.enableMultiStreamHacks && e.jingle.contents.forEach(function(e) {
                            if ("video" === e.name) {
                                var t = e.description.sources || [];
                                (0 === t.length || "3735928559" !== t[0].ssrc) && (t.unshift({
                                    ssrc: "3735928559",
                                    parameters: [{
                                        key: "cname",
                                        value: "deadbeef"
                                    }, {
                                        key: "msid",
                                        value: "mixyourfecintothis please"
                                    }]
                                }), e.description.sources = t)
                            }
                        }), n.restrictBandwidth > 0 && e.jingle.contents.length >= 2 && "video" === e.jingle.contents[1].name) {
                        var r = e.jingle.contents[1],
                            o = r.description && r.description.bandwidth;
                        o || (e.jingle.contents[1].description.bandwidth = {
                            type: "AS",
                            bandwidth: n.restrictBandwidth.toString()
                        }, e.sdp = a.toSessionSDP(e.jingle, {
                            sid: n.config.sdpSessionID,
                            role: n._role(),
                            direction: "outgoing"
                        }))
                    }
                    e.sdp = a.toSessionSDP(e.jingle, {
                        sid: n.config.sdpSessionID,
                        role: n._role(),
                        direction: "incoming"
                    }), n.remoteDescription = e.jingle
                }
                e.sdp.split("\r\n").forEach(function(e) {
                    0 === e.indexOf("a=candidate:") && n._checkRemoteCandidate(e)
                }), n.pc.setRemoteDescription(new RTCSessionDescription(e), function() {
                    t()
                }, t)
            }, n.prototype.answerAudioOnly = function(e) {
                var t = {
                    mandatory: {
                        OfferToReceiveAudio: !0,
                        OfferToReceiveVideo: !1
                    }
                };
                this._answer(t, e)
            }, n.prototype.answerBroadcastOnly = function(e) {
                var t = {
                    mandatory: {
                        OfferToReceiveAudio: !1,
                        OfferToReceiveVideo: !1
                    }
                };
                this._answer(t, e)
            }, n.prototype.answer = function(e, t) {
                var n = 2 === arguments.length,
                    r = n ? t : e,
                    o = n && e ? e : {
                        mandatory: {
                            OfferToReceiveAudio: !0,
                            OfferToReceiveVideo: !0
                        }
                    };
                this._answer(o, r)
            }, n.prototype.handleAnswer = function(e, t) {
                t = t || function() {};
                var n = this;
                e.jingle && (e.sdp = a.toSessionSDP(e.jingle, {
                    sid: n.config.sdpSessionID,
                    role: n._role(),
                    direction: "incoming"
                }), n.remoteDescription = e.jingle), e.sdp.split("\r\n").forEach(function(e) {
                    0 === e.indexOf("a=candidate:") && n._checkRemoteCandidate(e)
                }), n.pc.setRemoteDescription(new RTCSessionDescription(e), function() {
                    n.wtFirefox && window.setTimeout(function() {
                        n.firefoxcandidatebuffer.forEach(function(e) {
                            n.pc.addIceCandidate(new RTCIceCandidate(e), function() {}, function(e) {
                                n.emit("error", e)
                            }), n._checkRemoteCandidate(e.candidate)
                        }), n.firefoxcandidatebuffer = null
                    }, n.wtFirefox), t(null)
                }, t)
            }, n.prototype.close = function() {
                this.pc.close(), this._localDataChannels = [], this._remoteDataChannels = [], this.emit("close")
            }, n.prototype._answer = function(e, t) {
                t = t || function() {};
                var n = this;
                if (!this.pc.remoteDescription) throw new Error("remoteDescription not set");
                return "closed" === this.pc.signalingState ? t("Already closed") : (n.pc.createAnswer(function(e) {
                    var r = [];
                    if (n.enableChromeNativeSimulcast && (e.jingle = a.toSessionJSON(e.sdp, {
                            role: n._role(),
                            direction: "outgoing"
                        }), e.jingle.contents.length >= 2 && "video" === e.jingle.contents[1].name)) {
                        var o = e.jingle.contents[1].description.sourceGroups || [],
                            i = !1;
                        if (o.forEach(function(e) {
                                "SIM" == e.semantics && (i = !0)
                            }), !i && e.jingle.contents[1].description.sources.length) {
                            var s = JSON.parse(JSON.stringify(e.jingle.contents[1].description.sources[0]));
                            s.ssrc = "" + Math.floor(4294967295 * Math.random()), e.jingle.contents[1].description.sources.push(s), r.push(e.jingle.contents[1].description.sources[0].ssrc), r.push(s.ssrc), o.push({
                                semantics: "SIM",
                                sources: r
                            });
                            var c = JSON.parse(JSON.stringify(s));
                            c.ssrc = "" + Math.floor(4294967295 * Math.random()), e.jingle.contents[1].description.sources.push(c), o.push({
                                semantics: "FID",
                                sources: [s.ssrc, c.ssrc]
                            }), e.jingle.contents[1].description.sourceGroups = o, e.sdp = a.toSessionSDP(e.jingle, {
                                sid: n.config.sdpSessionID,
                                role: n._role(),
                                direction: "outgoing"
                            })
                        }
                    }
                    var u = {
                        type: "answer",
                        sdp: e.sdp
                    };
                    n.assumeSetLocalSuccess && (n.emit("answer", u), t(null, u)), n._candidateBuffer = [], n.pc.setLocalDescription(e, function() {
                        if (n.config.useJingle) {
                            var r = a.toSessionJSON(e.sdp, {
                                role: n._role(),
                                direction: "outgoing"
                            });
                            r.sid = n.config.sid, n.localDescription = r, u.jingle = r
                        }
                        n.enableChromeNativeSimulcast && (u.jingle || (u.jingle = a.toSessionJSON(e.sdp, {
                            role: n._role(),
                            direction: "outgoing"
                        })), u.jingle.contents[1].description.sources.forEach(function(e, t) {
                            e.parameters = e.parameters.map(function(e) {
                                return "msid" === e.key && (e.value += "-" + Math.floor(t / 2)), e
                            })
                        }), u.sdp = a.toSessionSDP(u.jingle, {
                            sid: n.sdpSessionID,
                            role: n._role(),
                            direction: "outgoing"
                        })), u.sdp.split("\r\n").forEach(function(e) {
                            0 === e.indexOf("a=candidate:") && n._checkLocalCandidate(e)
                        }), n.assumeSetLocalSuccess || (n.emit("answer", u), t(null, u))
                    }, function(e) {
                        n.emit("error", e), t(e)
                    })
                }, function(e) {
                    n.emit("error", e), t(e)
                }, e), void 0)
            }, n.prototype._onIce = function(e) {
                var t = this;
                if (e.candidate) {
                    if (this.dontSignalCandidates) return;
                    var n = e.candidate,
                        r = {
                            candidate: {
                                candidate: n.candidate,
                                sdpMid: n.sdpMid,
                                sdpMLineIndex: n.sdpMLineIndex
                            }
                        };
                    this._checkLocalCandidate(n.candidate);
                    var i, s, c = a.toCandidateJSON(n.candidate);
                    if (this.eliminateDuplicateCandidates && "relay" === c.type && (i = this._candidateBuffer.filter(function(e) {
                            return "relay" === e.type
                        }).map(function(e) {
                            return e.foundation + ":" + e.component
                        }), s = i.indexOf(c.foundation + ":" + c.component), s > -1 && c.priority >> 24 >= i[s].priority >> 24)) return;
                    if ("max-bundle" === this.config.bundlePolicy && (i = this._candidateBuffer.filter(function(e) {
                            return c.type === e.type
                        }).map(function(e) {
                            return e.address + ":" + e.port
                        }), s = i.indexOf(c.address + ":" + c.port), s > -1)) return;
                    if ("require" === this.config.rtcpMuxPolicy && "2" === c.component) return;
                    if (this._candidateBuffer.push(c), t.config.useJingle) {
                        if (n.sdpMid || (n.sdpMid = t.pc.remoteDescription && "offer" === t.pc.remoteDescription.type ? t.remoteDescription.contents[n.sdpMLineIndex].name : t.localDescription.contents[n.sdpMLineIndex].name), !t.config.ice[n.sdpMid]) {
                            var u = a.toSessionJSON(t.pc.localDescription.sdp, {
                                role: t._role(),
                                direction: "outgoing"
                            });
                            o(u.contents, function(e) {
                                var n = e.transport || {};
                                n.ufrag && (t.config.ice[e.name] = {
                                    ufrag: n.ufrag,
                                    pwd: n.pwd
                                })
                            })
                        }
                        if (r.jingle = {
                                contents: [{
                                    name: n.sdpMid,
                                    creator: t._role(),
                                    transport: {
                                        transType: "iceUdp",
                                        ufrag: t.config.ice[n.sdpMid].ufrag,
                                        pwd: t.config.ice[n.sdpMid].pwd,
                                        candidates: [c]
                                    }
                                }]
                            }, t.batchIceCandidates > 0) return 0 === t.batchedIceCandidates.length && window.setTimeout(function() {
                            var e = {};
                            t.batchedIceCandidates.forEach(function(t) {
                                t = t.contents[0], e[t.name] || (e[t.name] = t), e[t.name].transport.candidates.push(t.transport.candidates[0])
                            });
                            var n = {
                                jingle: {
                                    contents: []
                                }
                            };
                            Object.keys(e).forEach(function(t) {
                                n.jingle.contents.push(e[t])
                            }), t.batchedIceCandidates = [], t.emit("ice", n)
                        }, t.batchIceCandidates), t.batchedIceCandidates.push(r.jingle), void 0
                    }
                    this.emit("ice", r)
                } else this.emit("endOfCandidates")
            }, n.prototype._onDataChannel = function(e) {
                var t = e.channel;
                this._remoteDataChannels.push(t), this.emit("addChannel", t)
            }, n.prototype.createDataChannel = function(e, t) {
                var n = this.pc.createDataChannel(e, t);
                return this._localDataChannels.push(n), n
            }, n.prototype.getStats = function(e) {
                this.pc.getStats(null, function(t) {
                    e(null, t)
                }, function(t) {
                    e(t)
                })
            }, t.exports = n
        }, {
            "lodash.foreach": 26,
            "lodash.pluck": 27,
            "sdp-jingle-json": 24,
            traceablepeerconnection: 25,
            util: 8,
            "webrtc-adapter-test": 23,
            wildemitter: 4
        }],
        19: [function(e, t) {
            function n(e, t) {
                var n = -1 / 0;
                e.getFloatFrequencyData(t);
                for (var r = 4, o = t.length; o > r; r++) t[r] > n && t[r] < 0 && (n = t[r]);
                return n
            }
            var r = e("wildemitter"),
                o = window.AudioContext || window.webkitAudioContext,
                i = null;
            t.exports = function(e, t) {
                var a = new r;
                if (!o) return a;
                var t = t || {},
                    s = t.smoothing || .1,
                    c = t.interval || 50,
                    u = t.threshold,
                    p = t.play,
                    d = t.history || 10,
                    f = !0;
                i || (i = new o);
                var l, h, g;
                g = i.createAnalyser(), g.fftSize = 512, g.smoothingTimeConstant = s, h = new Float32Array(g.fftSize), e.jquery && (e = e[0]), e instanceof HTMLAudioElement || e instanceof HTMLVideoElement ? (l = i.createMediaElementSource(e), "undefined" == typeof p && (p = !0), u = u || -50) : (l = i.createMediaStreamSource(e), u = u || -50), l.connect(g), p && g.connect(i.destination), a.speaking = !1, a.setThreshold = function(e) {
                    u = e
                }, a.setInterval = function(e) {
                    c = e
                }, a.stop = function() {
                    f = !1, a.emit("volume_change", -100, u), a.speaking && (a.speaking = !1, a.emit("stopped_speaking"))
                }, a.speakingHistory = [];
                for (var m = 0; d > m; m++) a.speakingHistory.push(0);
                var v = function() {
                    setTimeout(function() {
                        if (f) {
                            var e = n(g, h);
                            a.emit("volume_change", e, u);
                            var t = 0;
                            if (e > u && !a.speaking) {
                                for (var r = a.speakingHistory.length - 3; r < a.speakingHistory.length; r++) t += a.speakingHistory[r];
                                t >= 2 && (a.speaking = !0, a.emit("speaking"))
                            } else if (u > e && a.speaking) {
                                for (var r = 0; r < a.speakingHistory.length; r++) t += a.speakingHistory[r];
                                0 == t && (a.speaking = !1, a.emit("stopped_speaking"))
                            }
                            a.speakingHistory.shift(), a.speakingHistory.push(0 + (e > u)), v()
                        }
                    }, c)
                };
                return v(), a
            }
        }, {
            wildemitter: 28
        }],
        20: [function(e, t) {
            e("webrtc-adapter-test"), t.exports = function(e, t) {
                var n, r = 2 === arguments.length,
                    o = {
                        video: !0,
                        audio: !0
                    },
                    i = "PermissionDeniedError",
                    a = "PERMISSION_DENIED",
                    s = "ConstraintNotSatisfiedError";
                return r || (t = e, e = o), navigator.getUserMedia ? e.audio || e.video ? (localStorage && "true" === localStorage.useFirefoxFakeDevice && (e.fake = !0), navigator.getUserMedia(e, function(e) {
                    t(null, e)
                }, function(e) {
                    var n;
                    "string" == typeof e ? (n = new Error("MediaStreamError"), n.name = e === i || e === a ? i : s) : (n = e, n.name || (e.name = n[i] ? i : s)), t(n)
                }), void 0) : (n = new Error("MediaStreamError"), n.name = "NoMediaRequestedError", window.setTimeout(function() {
                    t(n)
                }, 0)) : (n = new Error("MediaStreamError"), n.name = "NotSupportedError", window.setTimeout(function() {
                    t(n)
                }, 0))
            }
        }, {
            "webrtc-adapter-test": 29
        }],
        28: [function(e, t) {
            function n() {
                this.callbacks = {}
            }
            t.exports = n, n.prototype.on = function(e) {
                var t = 3 === arguments.length,
                    n = t ? arguments[1] : void 0,
                    r = t ? arguments[2] : arguments[1];
                return r._groupName = n, (this.callbacks[e] = this.callbacks[e] || []).push(r), this
            }, n.prototype.once = function(e) {
                function t() {
                    n.off(e, t), i.apply(this, arguments)
                }
                var n = this,
                    r = 3 === arguments.length,
                    o = r ? arguments[1] : void 0,
                    i = r ? arguments[2] : arguments[1];
                return this.on(e, o, t), this
            }, n.prototype.releaseGroup = function(e) {
                var t, n, r, o;
                for (t in this.callbacks)
                    for (o = this.callbacks[t], n = 0, r = o.length; r > n; n++) o[n]._groupName === e && (o.splice(n, 1), n--, r--);
                return this
            }, n.prototype.off = function(e, t) {
                var n, r = this.callbacks[e];
                return r ? 1 === arguments.length ? (delete this.callbacks[e], this) : (n = r.indexOf(t), r.splice(n, 1), 0 === r.length && delete this.callbacks[e], this) : this
            }, n.prototype.emit = function(e) {
                var t, n, r, o = [].slice.call(arguments, 1),
                    i = this.callbacks[e],
                    a = this.getWildcardCallbacks(e);
                if (i)
                    for (r = i.slice(), t = 0, n = r.length; n > t && r[t]; ++t) r[t].apply(this, o);
                if (a)
                    for (n = a.length, r = a.slice(), t = 0, n = r.length; n > t && r[t]; ++t) r[t].apply(this, [e].concat(o));
                return this
            }, n.prototype.getWildcardCallbacks = function(e) {
                var t, n, r = [];
                for (t in this.callbacks) n = t.split("*"), ("*" === t || 2 === n.length && e.slice(0, n[0].length) === n[0]) && (r = r.concat(this.callbacks[t]));
                return r
            }
        }, {}],
        29: [function(t, n) {
            "use strict";

            function r(e) {
                return new Promise(function(t, n) {
                    o(e, t, n)
                })
            }
            var o = null,
                i = null,
                a = null,
                s = null,
                c = null,
                u = null,
                p = {
                    log: function() {
                        "undefined" != typeof n || "function" == typeof t && "function" == typeof e || console.log.apply(console, arguments)
                    },
                    extractVersion: function(e, t, n) {
                        var r = e.match(t);
                        return r && r.length >= n && parseInt(r[n])
                    }
                };
            if ("object" == typeof window && (!window.HTMLMediaElement || "srcObject" in window.HTMLMediaElement.prototype || Object.defineProperty(window.HTMLMediaElement.prototype, "srcObject", {
                    get: function() {
                        return "mozSrcObject" in this ? this.mozSrcObject : this._srcObject
                    },
                    set: function(e) {
                        "mozSrcObject" in this ? this.mozSrcObject = e : (this._srcObject = e, this.src = URL.createObjectURL(e))
                    }
                }), o = window.navigator && window.navigator.getUserMedia), i = function(e, t) {
                    e.srcObject = t
                }, a = function(e, t) {
                    e.srcObject = t.srcObject
                }, "undefined" != typeof window && window.navigator)
                if (navigator.mozGetUserMedia && window.mozRTCPeerConnection) {
                    if (p.log("This appears to be Firefox"), s = "firefox", c = p.extractVersion(navigator.userAgent, /Firefox\/([0-9]+)\./, 1), u = 31, window.RTCPeerConnection = function(e, t) {
                            if (38 > c && e && e.iceServers) {
                                for (var n = [], r = 0; r < e.iceServers.length; r++) {
                                    var o = e.iceServers[r];
                                    if (o.hasOwnProperty("urls"))
                                        for (var i = 0; i < o.urls.length; i++) {
                                            var a = {
                                                url: o.urls[i]
                                            };
                                            0 === o.urls[i].indexOf("turn") && (a.username = o.username, a.credential = o.credential), n.push(a)
                                        } else n.push(e.iceServers[r])
                                }
                                e.iceServers = n
                            }
                            return new mozRTCPeerConnection(e, t)
                        }, window.RTCSessionDescription || (window.RTCSessionDescription = mozRTCSessionDescription), window.RTCIceCandidate || (window.RTCIceCandidate = mozRTCIceCandidate), o = function(e, t, n) {
                            var r = function(e) {
                                if ("object" != typeof e || e.require) return e;
                                var t = [];
                                return Object.keys(e).forEach(function(n) {
                                    if ("require" !== n && "advanced" !== n && "mediaSource" !== n) {
                                        var r = e[n] = "object" == typeof e[n] ? e[n] : {
                                            ideal: e[n]
                                        };
                                        if ((void 0 !== r.min || void 0 !== r.max || void 0 !== r.exact) && t.push(n), void 0 !== r.exact && ("number" == typeof r.exact ? r.min = r.max = r.exact : e[n] = r.exact, delete r.exact), void 0 !== r.ideal) {
                                            e.advanced = e.advanced || [];
                                            var o = {};
                                            o[n] = "number" == typeof r.ideal ? {
                                                min: r.ideal,
                                                max: r.ideal
                                            } : r.ideal, e.advanced.push(o), delete r.ideal, Object.keys(r).length || delete e[n]
                                        }
                                    }
                                }), t.length && (e.require = t), e
                            };
                            return 38 > c && (p.log("spec: " + JSON.stringify(e)), e.audio && (e.audio = r(e.audio)), e.video && (e.video = r(e.video)), p.log("ff37: " + JSON.stringify(e))), navigator.mozGetUserMedia(e, t, n)
                        }, navigator.getUserMedia = o, navigator.mediaDevices || (navigator.mediaDevices = {
                            getUserMedia: r,
                            addEventListener: function() {},
                            removeEventListener: function() {}
                        }), navigator.mediaDevices.enumerateDevices = navigator.mediaDevices.enumerateDevices || function() {
                            return new Promise(function(e) {
                                var t = [{
                                    kind: "audioinput",
                                    deviceId: "default",
                                    label: "",
                                    groupId: ""
                                }, {
                                    kind: "videoinput",
                                    deviceId: "default",
                                    label: "",
                                    groupId: ""
                                }];
                                e(t)
                            })
                        }, 41 > c) {
                        var d = navigator.mediaDevices.enumerateDevices.bind(navigator.mediaDevices);
                        navigator.mediaDevices.enumerateDevices = function() {
                            return d().then(void 0, function(e) {
                                if ("NotFoundError" === e.name) return [];
                                throw e
                            })
                        }
                    }
                } else if (navigator.webkitGetUserMedia && window.webkitRTCPeerConnection) {
                p.log("This appears to be Chrome"), s = "chrome", c = p.extractVersion(navigator.userAgent, /Chrom(e|ium)\/([0-9]+)\./, 2), u = 38, window.RTCPeerConnection = function(e, t) {
                    e && e.iceTransportPolicy && (e.iceTransports = e.iceTransportPolicy);
                    var n = new webkitRTCPeerConnection(e, t),
                        r = n.getStats.bind(n);
                    return n.getStats = function(e, t) {
                        var n = this,
                            o = arguments;
                        if (arguments.length > 0 && "function" == typeof e) return r(e, t);
                        var i = function(e) {
                            var t = {},
                                n = e.result();
                            return n.forEach(function(e) {
                                var n = {
                                    id: e.id,
                                    timestamp: e.timestamp,
                                    type: e.type
                                };
                                e.names().forEach(function(t) {
                                    n[t] = e.stat(t)
                                }), t[n.id] = n
                            }), t
                        };
                        if (arguments.length >= 2) {
                            var a = function(e) {
                                o[1](i(e))
                            };
                            return r.apply(this, [a, arguments[0]])
                        }
                        return new Promise(function(t, a) {
                            1 === o.length && null === e ? r.apply(n, [function(e) {
                                t.apply(null, [i(e)])
                            }, a]) : r.apply(n, [t, a])
                        })
                    }, n
                }, ["createOffer", "createAnswer"].forEach(function(e) {
                    var t = webkitRTCPeerConnection.prototype[e];
                    webkitRTCPeerConnection.prototype[e] = function() {
                        var e = this;
                        if (arguments.length < 1 || 1 === arguments.length && "object" == typeof arguments[0]) {
                            var n = 1 === arguments.length ? arguments[0] : void 0;
                            return new Promise(function(r, o) {
                                t.apply(e, [r, o, n])
                            })
                        }
                        return t.apply(this, arguments)
                    }
                }), ["setLocalDescription", "setRemoteDescription", "addIceCandidate"].forEach(function(e) {
                    var t = webkitRTCPeerConnection.prototype[e];
                    webkitRTCPeerConnection.prototype[e] = function() {
                        var e = arguments,
                            n = this;
                        return new Promise(function(r, o) {
                            t.apply(n, [e[0], function() {
                                r(), e.length >= 2 && e[1].apply(null, [])
                            }, function(t) {
                                o(t), e.length >= 3 && e[2].apply(null, [t])
                            }])
                        })
                    }
                });
                var f = function(e) {
                    if ("object" != typeof e || e.mandatory || e.optional) return e;
                    var t = {};
                    return Object.keys(e).forEach(function(n) {
                        if ("require" !== n && "advanced" !== n && "mediaSource" !== n) {
                            var r = "object" == typeof e[n] ? e[n] : {
                                ideal: e[n]
                            };
                            void 0 !== r.exact && "number" == typeof r.exact && (r.min = r.max = r.exact);
                            var o = function(e, t) {
                                return e ? e + t.charAt(0).toUpperCase() + t.slice(1) : "deviceId" === t ? "sourceId" : t
                            };
                            if (void 0 !== r.ideal) {
                                t.optional = t.optional || [];
                                var i = {};
                                "number" == typeof r.ideal ? (i[o("min", n)] = r.ideal, t.optional.push(i), i = {}, i[o("max", n)] = r.ideal, t.optional.push(i)) : (i[o("", n)] = r.ideal, t.optional.push(i))
                            }
                            void 0 !== r.exact && "number" != typeof r.exact ? (t.mandatory = t.mandatory || {}, t.mandatory[o("", n)] = r.exact) : ["min", "max"].forEach(function(e) {
                                void 0 !== r[e] && (t.mandatory = t.mandatory || {}, t.mandatory[o(e, n)] = r[e])
                            })
                        }
                    }), e.advanced && (t.optional = (t.optional || []).concat(e.advanced)), t
                };
                if (o = function(e, t, n) {
                        return e.audio && (e.audio = f(e.audio)), e.video && (e.video = f(e.video)), p.log("chrome: " + JSON.stringify(e)), navigator.webkitGetUserMedia(e, t, n)
                    }, navigator.getUserMedia = o, navigator.mediaDevices || (navigator.mediaDevices = {
                        getUserMedia: r,
                        enumerateDevices: function() {
                            return new Promise(function(e) {
                                var t = {
                                    audio: "audioinput",
                                    video: "videoinput"
                                };
                                return MediaStreamTrack.getSources(function(n) {
                                    e(n.map(function(e) {
                                        return {
                                            label: e.label,
                                            kind: t[e.kind],
                                            deviceId: e.id,
                                            groupId: ""
                                        }
                                    }))
                                })
                            })
                        }
                    }), navigator.mediaDevices.getUserMedia) {
                    var l = navigator.mediaDevices.getUserMedia.bind(navigator.mediaDevices);
                    navigator.mediaDevices.getUserMedia = function(e) {
                        return p.log("spec:   " + JSON.stringify(e)), e.audio = f(e.audio), e.video = f(e.video), p.log("chrome: " + JSON.stringify(e)), l(e)
                    }
                } else navigator.mediaDevices.getUserMedia = function(e) {
                    return r(e)
                };
                "undefined" == typeof navigator.mediaDevices.addEventListener && (navigator.mediaDevices.addEventListener = function() {
                    p.log("Dummy mediaDevices.addEventListener called.")
                }), "undefined" == typeof navigator.mediaDevices.removeEventListener && (navigator.mediaDevices.removeEventListener = function() {
                    p.log("Dummy mediaDevices.removeEventListener called.")
                }), i = function(e, t) {
                    c >= 43 ? e.srcObject = t : "undefined" != typeof e.src ? e.src = URL.createObjectURL(t) : p.log("Error attaching stream to element.")
                }, a = function(e, t) {
                    c >= 43 ? e.srcObject = t.srcObject : e.src = t.src
                }
            } else navigator.mediaDevices && navigator.userAgent.match(/Edge\/(\d+).(\d+)$/) ? (p.log("This appears to be Edge"), s = "edge", c = p.extractVersion(navigator.userAgent, /Edge\/(\d+).(\d+)$/, 2), u = 12) : p.log("Browser does not appear to be WebRTC-capable");
            else p.log("This does not appear to be a browser"), s = "not a browser";
            var h = {};
            try {
                Object.defineProperty(h, "version", {
                    set: function(e) {
                        c = e
                    }
                })
            } catch (g) {}
            if ("undefined" != typeof n) {
                var m;
                "undefined" != typeof window && (m = window.RTCPeerConnection), n.exports = {
                    RTCPeerConnection: m,
                    getUserMedia: o,
                    attachMediaStream: i,
                    reattachMediaStream: a,
                    webrtcDetectedBrowser: s,
                    webrtcDetectedVersion: c,
                    webrtcMinimumVersion: u,
                    webrtcTesting: h,
                    webrtcUtils: p
                }
            } else "function" == typeof t && "function" == typeof e && e([], function() {
                return {
                    RTCPeerConnection: window.RTCPeerConnection,
                    getUserMedia: o,
                    attachMediaStream: i,
                    reattachMediaStream: a,
                    webrtcDetectedBrowser: s,
                    webrtcDetectedVersion: c,
                    webrtcMinimumVersion: u,
                    webrtcTesting: h,
                    webrtcUtils: p
                }
            })
        }, {}],
        24: [function(e, t, n) {
            var r = e("./lib/tosdp"),
                o = e("./lib/tojson");
            n.toIncomingSDPOffer = function(e) {
                return r.toSessionSDP(e, {
                    role: "responder",
                    direction: "incoming"
                })
            }, n.toOutgoingSDPOffer = function(e) {
                return r.toSessionSDP(e, {
                    role: "initiator",
                    direction: "outgoing"
                })
            }, n.toIncomingSDPAnswer = function(e) {
                return r.toSessionSDP(e, {
                    role: "initiator",
                    direction: "incoming"
                })
            }, n.toOutgoingSDPAnswer = function(e) {
                return r.toSessionSDP(e, {
                    role: "responder",
                    direction: "outgoing"
                })
            }, n.toIncomingMediaSDPOffer = function(e) {
                return r.toMediaSDP(e, {
                    role: "responder",
                    direction: "incoming"
                })
            }, n.toOutgoingMediaSDPOffer = function(e) {
                return r.toMediaSDP(e, {
                    role: "initiator",
                    direction: "outgoing"
                })
            }, n.toIncomingMediaSDPAnswer = function(e) {
                return r.toMediaSDP(e, {
                    role: "initiator",
                    direction: "incoming"
                })
            }, n.toOutgoingMediaSDPAnswer = function(e) {
                return r.toMediaSDP(e, {
                    role: "responder",
                    direction: "outgoing"
                })
            }, n.toCandidateSDP = r.toCandidateSDP, n.toMediaSDP = r.toMediaSDP, n.toSessionSDP = r.toSessionSDP, n.toIncomingJSONOffer = function(e, t) {
                return o.toSessionJSON(e, {
                    role: "responder",
                    direction: "incoming",
                    creators: t
                })
            }, n.toOutgoingJSONOffer = function(e, t) {
                return o.toSessionJSON(e, {
                    role: "initiator",
                    direction: "outgoing",
                    creators: t
                })
            }, n.toIncomingJSONAnswer = function(e, t) {
                return o.toSessionJSON(e, {
                    role: "initiator",
                    direction: "incoming",
                    creators: t
                })
            }, n.toOutgoingJSONAnswer = function(e, t) {
                return o.toSessionJSON(e, {
                    role: "responder",
                    direction: "outgoing",
                    creators: t
                })
            }, n.toIncomingMediaJSONOffer = function(e, t) {
                return o.toMediaJSON(e, {
                    role: "responder",
                    direction: "incoming",
                    creator: t
                })
            }, n.toOutgoingMediaJSONOffer = function(e, t) {
                return o.toMediaJSON(e, {
                    role: "initiator",
                    direction: "outgoing",
                    creator: t
                })
            }, n.toIncomingMediaJSONAnswer = function(e, t) {
                return o.toMediaJSON(e, {
                    role: "initiator",
                    direction: "incoming",
                    creator: t
                })
            }, n.toOutgoingMediaJSONAnswer = function(e, t) {
                return o.toMediaJSON(e, {
                    role: "responder",
                    direction: "outgoing",
                    creator: t
                })
            }, n.toCandidateJSON = o.toCandidateJSON, n.toMediaJSON = o.toMediaJSON, n.toSessionJSON = o.toSessionJSON
        }, {
            "./lib/tojson": 31,
            "./lib/tosdp": 30
        }],
        21: [function(e, t) {
            var n = e("getusermedia"),
                r = {};
            t.exports = function(e, t) {
                var o, i = 2 === arguments.length,
                    a = i ? t : e;
                if ("undefined" == typeof window || "http:" === window.location.protocol) return o = new Error("NavigatorUserMediaError"), o.name = "HTTPS_REQUIRED", a(o);
                if (window.navigator.userAgent.match("Chrome")) {
                    var s = parseInt(window.navigator.userAgent.match(/Chrome\/(.*) /)[1], 10),
                        c = 33,
                        u = !window.chrome.webstore;
                    if (window.navigator.userAgent.match("Linux") && (c = 35), sessionStorage.getScreenMediaJSExtensionId) chrome.runtime.sendMessage(sessionStorage.getScreenMediaJSExtensionId, {
                        type: "getScreen",
                        id: 1
                    }, null, function(t) {
                        if (t && "" !== t.sourceId) e = i && e || {
                            audio: !1,
                            video: {
                                mandatory: {
                                    chromeMediaSource: "desktop",
                                    maxWidth: window.screen.width,
                                    maxHeight: window.screen.height,
                                    maxFrameRate: 3
                                },
                                optional: [{
                                    googLeakyBucket: !0
                                }, {
                                    googTemporalLayeredScreencast: !0
                                }]
                            }
                        }, e.video.mandatory.chromeMediaSourceId = t.sourceId, n(e, a);
                        else {
                            var r = new Error("NavigatorUserMediaError");
                            r.name = "PERMISSION_DENIED", a(r)
                        }
                    });
                    else if (window.cefGetScreenMedia) window.cefGetScreenMedia(function(t) {
                        if (t) e = i && e || {
                            audio: !1,
                            video: {
                                mandatory: {
                                    chromeMediaSource: "desktop",
                                    maxWidth: window.screen.width,
                                    maxHeight: window.screen.height,
                                    maxFrameRate: 3
                                },
                                optional: [{
                                    googLeakyBucket: !0
                                }, {
                                    googTemporalLayeredScreencast: !0
                                }]
                            }
                        }, e.video.mandatory.chromeMediaSourceId = t, n(e, a);
                        else {
                            var r = new Error("cefGetScreenMediaError");
                            r.name = "CEF_GETSCREENMEDIA_CANCELED", a(r)
                        }
                    });
                    else if (u || s >= 26 && c >= s) e = i && e || {
                        video: {
                            mandatory: {
                                googLeakyBucket: !0,
                                maxWidth: window.screen.width,
                                maxHeight: window.screen.height,
                                maxFrameRate: 3,
                                chromeMediaSource: "screen"
                            }
                        }
                    }, n(e, a);
                    else {
                        var p = window.setTimeout(function() {
                            return o = new Error("NavigatorUserMediaError"), o.name = "EXTENSION_UNAVAILABLE", a(o)
                        }, 1e3);
                        r[p] = [a, i ? e : null], window.postMessage({
                            type: "getScreen",
                            id: p
                        }, "*")
                    }
                } else if (window.navigator.userAgent.match("Firefox")) {
                    var d = parseInt(window.navigator.userAgent.match(/Firefox\/(.*)/)[1], 10);
                    d >= 33 ? (e = i && e || {
                        video: {
                            mozMediaSource: "window",
                            mediaSource: "window"
                        }
                    }, n(e, function(e, t) {
                        if (a(e, t), !e) var n = t.currentTime,
                            r = window.setInterval(function() {
                                t || window.clearInterval(r), t.currentTime == n && (window.clearInterval(r), t.onended && t.onended()), n = t.currentTime
                            }, 500)
                    })) : (o = new Error("NavigatorUserMediaError"), o.name = "EXTENSION_UNAVAILABLE")
                }
            }, window.addEventListener("message", function(e) {
                if (e.origin == window.location.origin)
                    if ("gotScreen" == e.data.type && r[e.data.id]) {
                        var t = r[e.data.id],
                            o = t[1],
                            i = t[0];
                        if (delete r[e.data.id], "" === e.data.sourceId) {
                            var a = new Error("NavigatorUserMediaError");
                            a.name = "PERMISSION_DENIED", i(a)
                        } else o = o || {
                            audio: !1,
                            video: {
                                mandatory: {
                                    chromeMediaSource: "desktop",
                                    maxWidth: window.screen.width,
                                    maxHeight: window.screen.height,
                                    maxFrameRate: 3
                                },
                                optional: [{
                                    googLeakyBucket: !0
                                }, {
                                    googTemporalLayeredScreencast: !0
                                }]
                            }
                        }, o.video.mandatory.chromeMediaSourceId = e.data.sourceId, n(o, i)
                    } else "getScreenPending" == e.data.type && window.clearTimeout(e.data.id)
            })
        }, {
            getusermedia: 20
        }],
        22: [function(e, t) {
            function n(e) {
                if (this.support = r.webAudio && r.mediaStream, this.gain = 1, this.support) {
                    var t = this.context = new r.AudioContext;
                    this.microphone = t.createMediaStreamSource(e), this.gainFilter = t.createGain(), this.destination = t.createMediaStreamDestination(), this.outputStream = this.destination.stream, this.microphone.connect(this.gainFilter), this.gainFilter.connect(this.destination), e.addTrack(this.outputStream.getAudioTracks()[0]), e.removeTrack(e.getAudioTracks()[0])
                }
                this.stream = e
            }
            var r = e("webrtcsupport");
            n.prototype.setGain = function(e) {
                this.support && (this.gainFilter.gain.value = e, this.gain = e)
            }, n.prototype.getGain = function() {
                return this.gain
            }, n.prototype.off = function() {
                return this.setGain(0)
            }, n.prototype.on = function() {
                this.setGain(1)
            }, t.exports = n
        }, {
            webrtcsupport: 18
        }],
        17: [function(e, t, n) {
            function r(e, t) {
                "object" == typeof e && (t = e, e = void 0), t = t || {};
                var n, r = o(e),
                    i = r.source,
                    u = r.id;
                return t.forceNew || t["force new connection"] || !1 === t.multiplex ? (s("ignoring socket cache for %s", i), n = a(i, t)) : (c[u] || (s("new io instance for %s", i), c[u] = a(i, t)), n = c[u]), n.socket(r.path)
            }
            var o = e("./url"),
                i = e("socket.io-parser"),
                a = e("./manager"),
                s = e("debug")("socket.io-client");
            t.exports = n = r;
            var c = n.managers = {};
            n.protocol = i.protocol, n.connect = r, n.Manager = e("./manager"), n.Socket = e("./socket")
        }, {
            "./manager": 33,
            "./socket": 34,
            "./url": 32,
            debug: 35,
            "socket.io-parser": 36
        }],
        37: [function(e, t) {
            function n(e, t, n) {
                return e.on(t, n), {
                    destroy: function() {
                        e.removeListener(t, n)
                    }
                }
            }
            t.exports = n
        }, {}],
        35: [function(e, t) {
            function n(e) {
                return n.enabled(e) ? function(t) {
                    t = r(t);
                    var o = new Date,
                        i = o - (n[e] || o);
                    n[e] = o, t = e + " " + t + " +" + n.humanize(i), window.console && console.log && Function.prototype.apply.call(console.log, console, arguments)
                } : function() {}
            }

            function r(e) {
                return e instanceof Error ? e.stack || e.message : e
            }
            t.exports = n, n.names = [], n.skips = [], n.enable = function(e) {
                try {
                    localStorage.debug = e
                } catch (t) {}
                for (var r = (e || "").split(/[\s,]+/), o = r.length, i = 0; o > i; i++) e = r[i].replace("*", ".*?"), "-" === e[0] ? n.skips.push(new RegExp("^" + e.substr(1) + "$")) : n.names.push(new RegExp("^" + e + "$"))
            }, n.disable = function() {
                n.enable("")
            }, n.humanize = function(e) {
                var t = 1e3,
                    n = 6e4,
                    r = 60 * n;
                return e >= r ? (e / r).toFixed(1) + "h" : e >= n ? (e / n).toFixed(1) + "m" : e >= t ? (0 | e / t) + "s" : e + "ms"
            }, n.enabled = function(e) {
                for (var t = 0, r = n.skips.length; r > t; t++)
                    if (n.skips[t].test(e)) return !1;
                for (var t = 0, r = n.names.length; r > t; t++)
                    if (n.names[t].test(e)) return !0;
                return !1
            };
            try {
                window.localStorage && n.enable(localStorage.debug)
            } catch (o) {}
        }, {}],
        26: [function(e, t) {
            function n(e, t) {
                return function(n, r, o) {
                    return "function" == typeof r && void 0 === o && a(n) ? e(n, r) : t(n, i(r, o, 3))
                }
            }
            var r = e("lodash._arrayeach"),
                o = e("lodash._baseeach"),
                i = e("lodash._bindcallback"),
                a = e("lodash.isarray"),
                s = n(r, o);
            t.exports = s
        }, {
            "lodash._arrayeach": 38,
            "lodash._baseeach": 39,
            "lodash._bindcallback": 40,
            "lodash.isarray": 41
        }],
        27: [function(e, t) {
            function n(e) {
                return function(t) {
                    return null == t ? void 0 : t[e]
                }
            }

            function r(e) {
                var t = e + "";
                return e = p(e),
                    function(n) {
                        return u(n, e, t)
                    }
            }

            function o(e, t) {
                var n = typeof e;
                if ("string" == n && h.test(e) || "number" == n) return !0;
                if (d(e)) return !1;
                var r = !l.test(e);
                return r || null != t && e in i(t)
            }

            function i(e) {
                return s(e) ? e : Object(e)
            }

            function a(e, t) {
                return f(e, c(t))
            }

            function s(e) {
                var t = typeof e;
                return !!e && ("object" == t || "function" == t)
            }

            function c(e) {
                return o(e) ? n(e) : r(e)
            }
            var u = e("lodash._baseget"),
                p = e("lodash._topath"),
                d = e("lodash.isarray"),
                f = e("lodash.map"),
                l = /\.|\[(?:[^[\]]*|(["'])(?:(?!\1)[^\n\\]|\\.)*?\1)\]/,
                h = /^\w*$/;
            t.exports = a
        }, {
            "lodash._baseget": 42,
            "lodash._topath": 43,
            "lodash.isarray": 44,
            "lodash.map": 45
        }],
        30: [function(e, t, n) {
            var r = e("./senders");
            n.toSessionSDP = function(e, t) {
                t.role || "initiator", t.direction || "outgoing";
                var r = t.sid || e.sid || Date.now(),
                    o = t.time || Date.now(),
                    i = ["v=0", "o=- " + r + " " + o + " IN IP4 0.0.0.0", "s=-", "t=0 0", "a=msid-semantic: WMS *"],
                    a = e.groups || [];
                a.forEach(function(e) {
                    i.push("a=group:" + e.semantics + " " + e.contents.join(" "))
                });
                var s = e.contents || [];
                return s.forEach(function(e) {
                    i.push(n.toMediaSDP(e, t))
                }), i.join("\r\n") + "\r\n"
            }, n.toMediaSDP = function(e, t) {
                var o = [],
                    i = t.role || "initiator",
                    a = t.direction || "outgoing",
                    s = e.description,
                    c = e.transport,
                    u = s.payloads || [],
                    p = c && c.fingerprints || [],
                    d = [];
                if ("datachannel" == s.descType ? (d.push("application"), d.push("1"), d.push("DTLS/SCTP"), c.sctp && c.sctp.forEach(function(e) {
                        d.push(e.number)
                    })) : (d.push(s.media), d.push("1"), s.encryption && s.encryption.length > 0 || p.length > 0 ? d.push("RTP/SAVPF") : d.push("RTP/AVPF"), u.forEach(function(e) {
                        d.push(e.id)
                    })), o.push("m=" + d.join(" ")), o.push("c=IN IP4 0.0.0.0"), s.bandwidth && s.bandwidth.type && s.bandwidth.bandwidth && o.push("b=" + s.bandwidth.type + ":" + s.bandwidth.bandwidth), "rtp" == s.descType && o.push("a=rtcp:1 IN IP4 0.0.0.0"), c) {
                    c.ufrag && o.push("a=ice-ufrag:" + c.ufrag), c.pwd && o.push("a=ice-pwd:" + c.pwd);
                    var f = !1;
                    p.forEach(function(e) {
                        o.push("a=fingerprint:" + e.hash + " " + e.value), e.setup && !f && o.push("a=setup:" + e.setup)
                    }), c.sctp && c.sctp.forEach(function(e) {
                        o.push("a=sctpmap:" + e.number + " " + e.protocol + " " + e.streams)
                    })
                }
                "rtp" == s.descType && o.push("a=" + (r[i][a][e.senders] || "sendrecv")), o.push("a=mid:" + e.name), s.sources && s.sources.length && (s.sources[0].parameters || []).forEach(function(e) {
                    "msid" === e.key && o.push("a=msid:" + e.value)
                }), s.mux && o.push("a=rtcp-mux");
                var l = s.encryption || [];
                l.forEach(function(e) {
                    o.push("a=crypto:" + e.tag + " " + e.cipherSuite + " " + e.keyParams + (e.sessionParams ? " " + e.sessionParams : ""))
                }), s.googConferenceFlag && o.push("a=x-google-flag:conference"), u.forEach(function(e) {
                    var t = "a=rtpmap:" + e.id + " " + e.name + "/" + e.clockrate;
                    if (e.channels && "1" != e.channels && (t += "/" + e.channels), o.push(t), e.parameters && e.parameters.length) {
                        var n = ["a=fmtp:" + e.id],
                            r = [];
                        e.parameters.forEach(function(e) {
                            r.push((e.key ? e.key + "=" : "") + e.value)
                        }), n.push(r.join(";")), o.push(n.join(" "))
                    }
                    e.feedback && e.feedback.forEach(function(t) {
                        "trr-int" === t.type ? o.push("a=rtcp-fb:" + e.id + " trr-int " + (t.value ? t.value : "0")) : o.push("a=rtcp-fb:" + e.id + " " + t.type + (t.subtype ? " " + t.subtype : ""))
                    })
                }), s.feedback && s.feedback.forEach(function(e) {
                    "trr-int" === e.type ? o.push("a=rtcp-fb:* trr-int " + (e.value ? e.value : "0")) : o.push("a=rtcp-fb:* " + e.type + (e.subtype ? " " + e.subtype : ""))
                });
                var h = s.headerExtensions || [];
                h.forEach(function(e) {
                    o.push("a=extmap:" + e.id + (e.senders ? "/" + r[i][a][e.senders] : "") + " " + e.uri)
                });
                var g = s.sourceGroups || [];
                g.forEach(function(e) {
                    o.push("a=ssrc-group:" + e.semantics + " " + e.sources.join(" "))
                });
                var m = s.sources || [];
                m.forEach(function(e) {
                    for (var t = 0; t < e.parameters.length; t++) {
                        var n = e.parameters[t];
                        o.push("a=ssrc:" + (e.ssrc || s.ssrc) + " " + n.key + (n.value ? ":" + n.value : ""))
                    }
                });
                var v = c.candidates || [];
                return v.forEach(function(e) {
                    o.push(n.toCandidateSDP(e))
                }), o.join("\r\n")
            }, n.toCandidateSDP = function(e) {
                var t = [];
                t.push(e.foundation), t.push(e.component), t.push(e.protocol.toUpperCase()), t.push(e.priority), t.push(e.ip), t.push(e.port);
                var n = e.type;
                return t.push("typ"), t.push(n), ("srflx" === n || "prflx" === n || "relay" === n) && e.relAddr && e.relPort && (t.push("raddr"), t.push(e.relAddr), t.push("rport"), t.push(e.relPort)), e.tcpType && "TCP" == e.protocol.toUpperCase() && (t.push("tcptype"), t.push(e.tcpType)), t.push("generation"), t.push(e.generation || "0"), "a=candidate:" + t.join(" ")
            }
        }, {
            "./senders": 46
        }],
        31: [function(e, t, n) {
            var r = e("./senders"),
                o = e("./parsers"),
                i = Math.random();
            n._setIdCounter = function(e) {
                i = e
            }, n.toSessionJSON = function(e, t) {
                var r, i = t.creators || [],
                    a = t.role || "initiator",
                    s = t.direction || "outgoing",
                    c = e.split("\r\nm=");
                for (r = 1; r < c.length; r++) c[r] = "m=" + c[r], r !== c.length - 1 && (c[r] += "\r\n");
                var u = c.shift() + "\r\n",
                    p = o.lines(u),
                    d = {},
                    f = [];
                for (r = 0; r < c.length; r++) f.push(n.toMediaJSON(c[r], u, {
                    role: a,
                    direction: s,
                    creator: i[r] || "initiator"
                }));
                d.contents = f;
                var l = o.findLines("a=group:", p);
                return l.length && (d.groups = o.groups(l)), d
            }, n.toMediaJSON = function(e, t, i) {
                var a = i.creator || "initiator",
                    s = i.role || "initiator",
                    c = i.direction || "outgoing",
                    u = o.lines(e),
                    p = o.lines(t),
                    d = o.mline(u[0]),
                    f = {
                        creator: a,
                        name: d.media,
                        description: {
                            descType: "rtp",
                            media: d.media,
                            payloads: [],
                            encryption: [],
                            feedback: [],
                            headerExtensions: []
                        },
                        transport: {
                            transType: "iceUdp",
                            candidates: [],
                            fingerprints: []
                        }
                    };
                "application" == d.media && (f.description = {
                    descType: "datachannel"
                }, f.transport.sctp = []);
                var l = f.description,
                    h = f.transport,
                    g = o.findLine("a=mid:", u);
                if (g && (f.name = g.substr(6)), o.findLine("a=sendrecv", u, p) ? f.senders = "both" : o.findLine("a=sendonly", u, p) ? f.senders = r[s][c].sendonly : o.findLine("a=recvonly", u, p) ? f.senders = r[s][c].recvonly : o.findLine("a=inactive", u, p) && (f.senders = "none"), "rtp" == l.descType) {
                    var m = o.findLine("b=", u);
                    m && (l.bandwidth = o.bandwidth(m));
                    var v = o.findLine("a=ssrc:", u);
                    v && (l.ssrc = v.substr(7).split(" ")[0]);
                    var y = o.findLines("a=rtpmap:", u);
                    y.forEach(function(e) {
                        var t = o.rtpmap(e);
                        t.parameters = [], t.feedback = [];
                        var n = o.findLines("a=fmtp:" + t.id, u);
                        n.forEach(function(e) {
                            t.parameters = o.fmtp(e)
                        });
                        var r = o.findLines("a=rtcp-fb:" + t.id, u);
                        r.forEach(function(e) {
                            t.feedback.push(o.rtcpfb(e))
                        }), l.payloads.push(t)
                    });
                    var b = o.findLines("a=crypto:", u, p);
                    b.forEach(function(e) {
                        l.encryption.push(o.crypto(e))
                    }), o.findLine("a=rtcp-mux", u) && (l.mux = !0);
                    var w = o.findLines("a=rtcp-fb:*", u);
                    w.forEach(function(e) {
                        l.feedback.push(o.rtcpfb(e))
                    });
                    var S = o.findLines("a=extmap:", u);
                    S.forEach(function(e) {
                        var t = o.extmap(e);
                        t.senders = r[s][c][t.senders], l.headerExtensions.push(t)
                    });
                    var k = o.findLines("a=ssrc-group:", u);
                    l.sourceGroups = o.sourceGroups(k || []);
                    var C = o.findLines("a=ssrc:", u),
                        x = l.sources = o.sources(C || []),
                        E = o.findLine("a=msid:", u);
                    if (E) {
                        var j = o.msid(E);
                        ["msid", "mslabel", "label"].forEach(function(e) {
                            for (var t = 0; t < x.length; t++) {
                                for (var n = !1, r = 0; r < x[t].parameters.length; r++) x[t].parameters[r].key === e && (n = !0);
                                n || x[t].parameters.push({
                                    key: e,
                                    value: j[e]
                                })
                            }
                        })
                    }
                    o.findLine("a=x-google-flag:conference", u, p) && (l.googConferenceFlag = !0)
                }
                var O = o.findLines("a=fingerprint:", u, p),
                    T = o.findLine("a=setup:", u, p);
                O.forEach(function(e) {
                    var t = o.fingerprint(e);
                    T && (t.setup = T.substr(8)), h.fingerprints.push(t)
                });
                var D = o.findLine("a=ice-ufrag:", u, p),
                    A = o.findLine("a=ice-pwd:", u, p);
                if (D && A) {
                    h.ufrag = D.substr(12), h.pwd = A.substr(10), h.candidates = [];
                    var R = o.findLines("a=candidate:", u, p);
                    R.forEach(function(e) {
                        h.candidates.push(n.toCandidateJSON(e))
                    })
                }
                if ("datachannel" == l.descType) {
                    var M = o.findLines("a=sctpmap:", u);
                    M.forEach(function(e) {
                        var t = o.sctpmap(e);
                        h.sctp.push(t)
                    })
                }
                return f
            }, n.toCandidateJSON = function(e) {
                var t = o.candidate(e.split("\r\n")[0]);
                return t.id = (i++).toString(36).substr(0, 12), t
            }
        }, {
            "./parsers": 47,
            "./senders": 46
        }],
        38: [function(e, t) {
            function n(e, t) {
                for (var n = -1, r = e.length; ++n < r && t(e[n], n, e) !== !1;);
                return e
            }
            t.exports = n
        }, {}],
        40: [function(e, t) {
            function n(e, t, n) {
                if ("function" != typeof e) return r;
                if (void 0 === t) return e;
                switch (n) {
                    case 1:
                        return function(n) {
                            return e.call(t, n)
                        };
                    case 3:
                        return function(n, r, o) {
                            return e.call(t, n, r, o)
                        };
                    case 4:
                        return function(n, r, o, i) {
                            return e.call(t, n, r, o, i)
                        };
                    case 5:
                        return function(n, r, o, i, a) {
                            return e.call(t, n, r, o, i, a)
                        }
                }
                return function() {
                    return e.apply(t, arguments)
                }
            }

            function r(e) {
                return e
            }
            t.exports = n
        }, {}],
        41: [function(e, t) {
            function n(e) {
                return !!e && "object" == typeof e
            }

            function r(e, t) {
                var n = null == e ? void 0 : e[t];
                return s(n) ? n : void 0
            }

            function o(e) {
                return "number" == typeof e && e > -1 && 0 == e % 1 && v >= e
            }

            function i(e) {
                return a(e) && h.call(e) == u
            }

            function a(e) {
                var t = typeof e;
                return !!e && ("object" == t || "function" == t)
            }

            function s(e) {
                return null == e ? !1 : i(e) ? g.test(f.call(e)) : n(e) && p.test(e)
            }
            var c = "[object Array]",
                u = "[object Function]",
                p = /^\[object .+?Constructor\]$/,
                d = Object.prototype,
                f = Function.prototype.toString,
                l = d.hasOwnProperty,
                h = d.toString,
                g = RegExp("^" + f.call(l).replace(/[\\^$.*+?()[\]{}|]/g, "\\$&").replace(/hasOwnProperty|(function).*?(?=\\\()| for .+?(?=\\\])/g, "$1.*?") + "$"),
                m = r(Array, "isArray"),
                v = 9007199254740991,
                y = m || function(e) {
                    return n(e) && o(e.length) && h.call(e) == c
                };
            t.exports = y
        }, {}],
        42: [function(e, t) {
            function n(e, t, n) {
                if (null != e) {
                    void 0 !== n && n in r(e) && (t = [n]);
                    for (var o = 0, i = t.length; null != e && i > o;) e = e[t[o++]];
                    return o && o == i ? e : void 0
                }
            }

            function r(e) {
                return o(e) ? e : Object(e)
            }

            function o(e) {
                var t = typeof e;
                return !!e && ("object" == t || "function" == t)
            }
            t.exports = n
        }, {}],
        44: [function(e, t) {
            function n(e) {
                return !!e && "object" == typeof e
            }

            function r(e, t) {
                var n = null == e ? void 0 : e[t];
                return s(n) ? n : void 0
            }

            function o(e) {
                return "number" == typeof e && e > -1 && 0 == e % 1 && v >= e
            }

            function i(e) {
                return a(e) && h.call(e) == u
            }

            function a(e) {
                var t = typeof e;
                return !!e && ("object" == t || "function" == t)
            }

            function s(e) {
                return null == e ? !1 : i(e) ? g.test(f.call(e)) : n(e) && p.test(e)
            }
            var c = "[object Array]",
                u = "[object Function]",
                p = /^\[object .+?Constructor\]$/,
                d = Object.prototype,
                f = Function.prototype.toString,
                l = d.hasOwnProperty,
                h = d.toString,
                g = RegExp("^" + f.call(l).replace(/[\\^$.*+?()[\]{}|]/g, "\\$&").replace(/hasOwnProperty|(function).*?(?=\\\()| for .+?(?=\\\])/g, "$1.*?") + "$"),
                m = r(Array, "isArray"),
                v = 9007199254740991,
                y = m || function(e) {
                    return n(e) && o(e.length) && h.call(e) == c
                };
            t.exports = y
        }, {}],
        32: [function(e, t) {
            function n(e, t) {
                var n = e,
                    t = t || r.location;
                return null == e && (e = t.protocol + "//" + t.host), "string" == typeof e && ("/" == e.charAt(0) && (e = "/" == e.charAt(1) ? t.protocol + e : t.hostname + e), /^(https?|wss?):\/\//.test(e) || (i("protocol-less url %s", e), e = "undefined" != typeof t ? t.protocol + "//" + e : "https://" + e), i("parse %s", e), n = o(e)), n.port || (/^(http|ws)$/.test(n.protocol) ? n.port = "80" : /^(http|ws)s$/.test(n.protocol) && (n.port = "443")), n.path = n.path || "/", n.id = n.protocol + "://" + n.host + ":" + n.port, n.href = n.protocol + "://" + n.host + (t && t.port == n.port ? "" : ":" + n.port), n
            }
            var r = self,
                o = e("parseuri"),
                i = e("debug")("socket.io-client:url");
            t.exports = n
        }, {
            debug: 35,
            parseuri: 48
        }],
        33: [function(e, t) {
            function n(e, t) {
                return this instanceof n ? (e && "object" == typeof e && (t = e, e = void 0), t = t || {}, t.path = t.path || "/socket.io", this.nsps = {}, this.subs = [], this.opts = t, this.reconnection(t.reconnection !== !1), this.reconnectionAttempts(t.reconnectionAttempts || 1 / 0), this.reconnectionDelay(t.reconnectionDelay || 1e3), this.reconnectionDelayMax(t.reconnectionDelayMax || 5e3), this.randomizationFactor(t.randomizationFactor || .5), this.backoff = new d({
                    min: this.reconnectionDelay(),
                    max: this.reconnectionDelayMax(),
                    jitter: this.randomizationFactor()
                }), this.timeout(null == t.timeout ? 2e4 : t.timeout), this.readyState = "closed", this.uri = e, this.connected = [], this.encoding = !1, this.packetBuffer = [], this.encoder = new a.Encoder, this.decoder = new a.Decoder, this.autoConnect = t.autoConnect !== !1, this.autoConnect && this.open(), void 0) : new n(e, t)
            }
            e("./url");
            var r = e("engine.io-client"),
                o = e("./socket"),
                i = e("component-emitter"),
                a = e("socket.io-parser"),
                s = e("./on"),
                c = e("component-bind");
            e("object-component");
            var u = e("debug")("socket.io-client:manager"),
                p = e("indexof"),
                d = e("backo2");
            t.exports = n, n.prototype.emitAll = function() {
                this.emit.apply(this, arguments);
                for (var e in this.nsps) this.nsps[e].emit.apply(this.nsps[e], arguments)
            }, n.prototype.updateSocketIds = function() {
                for (var e in this.nsps) this.nsps[e].id = this.engine.id
            }, i(n.prototype), n.prototype.reconnection = function(e) {
                return arguments.length ? (this._reconnection = !!e, this) : this._reconnection
            }, n.prototype.reconnectionAttempts = function(e) {
                return arguments.length ? (this._reconnectionAttempts = e, this) : this._reconnectionAttempts
            }, n.prototype.reconnectionDelay = function(e) {
                return arguments.length ? (this._reconnectionDelay = e, this.backoff && this.backoff.setMin(e), this) : this._reconnectionDelay
            }, n.prototype.randomizationFactor = function(e) {
                return arguments.length ? (this._randomizationFactor = e, this.backoff && this.backoff.setJitter(e), this) : this._randomizationFactor
            }, n.prototype.reconnectionDelayMax = function(e) {
                return arguments.length ? (this._reconnectionDelayMax = e, this.backoff && this.backoff.setMax(e), this) : this._reconnectionDelayMax
            }, n.prototype.timeout = function(e) {
                return arguments.length ? (this._timeout = e, this) : this._timeout
            }, n.prototype.maybeReconnectOnOpen = function() {
                !this.reconnecting && this._reconnection && 0 === this.backoff.attempts && this.reconnect()
            }, n.prototype.open = n.prototype.connect = function(e) {
                if (u("readyState %s", this.readyState), ~this.readyState.indexOf("open")) return this;
                u("opening %s", this.uri), this.engine = r(this.uri, this.opts);
                var t = this.engine,
                    n = this;
                this.readyState = "opening", this.skipReconnect = !1;
                var o = s(t, "open", function() {
                        n.onopen(), e && e()
                    }),
                    i = s(t, "error", function(t) {
                        if (u("connect_error"), n.cleanup(), n.readyState = "closed", n.emitAll("connect_error", t), e) {
                            var r = new Error("Connection error");
                            r.data = t, e(r)
                        } else n.maybeReconnectOnOpen()
                    });
                if (!1 !== this._timeout) {
                    var a = this._timeout;
                    u("connect attempt will timeout after %d", a);
                    var c = setTimeout(function() {
                        u("connect attempt timed out after %d", a), o.destroy(), t.close(), t.emit("error", "timeout"), n.emitAll("connect_timeout", a)
                    }, a);
                    this.subs.push({
                        destroy: function() {
                            clearTimeout(c)
                        }
                    })
                }
                return this.subs.push(o), this.subs.push(i), this
            }, n.prototype.onopen = function() {
                u("open"), this.cleanup(), this.readyState = "open", this.emit("open");
                var e = this.engine;
                this.subs.push(s(e, "data", c(this, "ondata"))), this.subs.push(s(this.decoder, "decoded", c(this, "ondecoded"))), this.subs.push(s(e, "error", c(this, "onerror"))), this.subs.push(s(e, "close", c(this, "onclose")))
            }, n.prototype.ondata = function(e) {
                this.decoder.add(e)
            }, n.prototype.ondecoded = function(e) {
                this.emit("packet", e)
            }, n.prototype.onerror = function(e) {
                u("error", e), this.emitAll("error", e)
            }, n.prototype.socket = function(e) {
                var t = this.nsps[e];
                if (!t) {
                    t = new o(this, e), this.nsps[e] = t;
                    var n = this;
                    t.on("connect", function() {
                        t.id = n.engine.id, ~p(n.connected, t) || n.connected.push(t)
                    })
                }
                return t
            }, n.prototype.destroy = function(e) {
                var t = p(this.connected, e);
                ~t && this.connected.splice(t, 1), this.connected.length || this.close()
            }, n.prototype.packet = function(e) {
                u("writing packet %j", e);
                var t = this;
                t.encoding ? t.packetBuffer.push(e) : (t.encoding = !0, this.encoder.encode(e, function(e) {
                    for (var n = 0; n < e.length; n++) t.engine.write(e[n]);
                    t.encoding = !1, t.processPacketQueue()
                }))
            }, n.prototype.processPacketQueue = function() {
                if (this.packetBuffer.length > 0 && !this.encoding) {
                    var e = this.packetBuffer.shift();
                    this.packet(e)
                }
            }, n.prototype.cleanup = function() {
                for (var e; e = this.subs.shift();) e.destroy();
                this.packetBuffer = [], this.encoding = !1, this.decoder.destroy()
            }, n.prototype.close = n.prototype.disconnect = function() {
                this.skipReconnect = !0, this.backoff.reset(), this.readyState = "closed", this.engine && this.engine.close()
            }, n.prototype.onclose = function(e) {
                u("close"), this.cleanup(), this.backoff.reset(), this.readyState = "closed", this.emit("close", e), this._reconnection && !this.skipReconnect && this.reconnect()
            }, n.prototype.reconnect = function() {
                if (this.reconnecting || this.skipReconnect) return this;
                var e = this;
                if (this.backoff.attempts >= this._reconnectionAttempts) u("reconnect failed"), this.backoff.reset(), this.emitAll("reconnect_failed"), this.reconnecting = !1;
                else {
                    var t = this.backoff.duration();
                    u("will wait %dms before reconnect attempt", t), this.reconnecting = !0;
                    var n = setTimeout(function() {
                        e.skipReconnect || (u("attempting reconnect"), e.emitAll("reconnect_attempt", e.backoff.attempts), e.emitAll("reconnecting", e.backoff.attempts), e.skipReconnect || e.open(function(t) {
                            t ? (u("reconnect attempt error"), e.reconnecting = !1, e.reconnect(), e.emitAll("reconnect_error", t.data)) : (u("reconnect success"), e.onreconnect())
                        }))
                    }, t);
                    this.subs.push({
                        destroy: function() {
                            clearTimeout(n)
                        }
                    })
                }
            }, n.prototype.onreconnect = function() {
                var e = this.backoff.attempts;
                this.reconnecting = !1, this.backoff.reset(), this.updateSocketIds(), this.emitAll("reconnect", e)
            }
        }, {
            "./on": 37,
            "./socket": 34,
            "./url": 32,
            backo2: 54,
            "component-bind": 51,
            "component-emitter": 49,
            debug: 35,
            "engine.io-client": 50,
            indexof: 53,
            "object-component": 52,
            "socket.io-parser": 36
        }],
        34: [function(e, t, n) {
            function r(e, t) {
                this.io = e, this.nsp = t, this.json = this, this.ids = 0, this.acks = {}, this.io.autoConnect && this.open(), this.receiveBuffer = [], this.sendBuffer = [], this.connected = !1, this.disconnected = !0
            }
            var o = e("socket.io-parser"),
                i = e("component-emitter"),
                a = e("to-array"),
                s = e("./on"),
                c = e("component-bind"),
                u = e("debug")("socket.io-client:socket"),
                p = e("has-binary");
            t.exports = n = r;
            var d = {
                    connect: 1,
                    connect_error: 1,
                    connect_timeout: 1,
                    disconnect: 1,
                    error: 1,
                    reconnect: 1,
                    reconnect_attempt: 1,
                    reconnect_failed: 1,
                    reconnect_error: 1,
                    reconnecting: 1
                },
                f = i.prototype.emit;
            i(r.prototype), r.prototype.subEvents = function() {
                if (!this.subs) {
                    var e = this.io;
                    this.subs = [s(e, "open", c(this, "onopen")), s(e, "packet", c(this, "onpacket")), s(e, "close", c(this, "onclose"))]
                }
            }, r.prototype.open = r.prototype.connect = function() {
                return this.connected ? this : (this.subEvents(), this.io.open(), "open" == this.io.readyState && this.onopen(), this)
            }, r.prototype.send = function() {
                var e = a(arguments);
                return e.unshift("message"), this.emit.apply(this, e), this
            }, r.prototype.emit = function(e) {
                if (d.hasOwnProperty(e)) return f.apply(this, arguments), this;
                var t = a(arguments),
                    n = o.EVENT;
                p(t) && (n = o.BINARY_EVENT);
                var r = {
                    type: n,
                    data: t
                };
                return "function" == typeof t[t.length - 1] && (u("emitting packet with ack id %d", this.ids), this.acks[this.ids] = t.pop(), r.id = this.ids++), this.connected ? this.packet(r) : this.sendBuffer.push(r), this
            }, r.prototype.packet = function(e) {
                e.nsp = this.nsp, this.io.packet(e)
            }, r.prototype.onopen = function() {
                u("transport is open - connecting"), "/" != this.nsp && this.packet({
                    type: o.CONNECT
                })
            }, r.prototype.onclose = function(e) {
                u("close (%s)", e), this.connected = !1, this.disconnected = !0, delete this.id, this.emit("disconnect", e)
            }, r.prototype.onpacket = function(e) {
                if (e.nsp == this.nsp) switch (e.type) {
                    case o.CONNECT:
                        this.onconnect();
                        break;
                    case o.EVENT:
                        this.onevent(e);
                        break;
                    case o.BINARY_EVENT:
                        this.onevent(e);
                        break;
                    case o.ACK:
                        this.onack(e);
                        break;
                    case o.BINARY_ACK:
                        this.onack(e);
                        break;
                    case o.DISCONNECT:
                        this.ondisconnect();
                        break;
                    case o.ERROR:
                        this.emit("error", e.data)
                }
            }, r.prototype.onevent = function(e) {
                var t = e.data || [];
                u("emitting event %j", t), null != e.id && (u("attaching ack callback to event"), t.push(this.ack(e.id))), this.connected ? f.apply(this, t) : this.receiveBuffer.push(t)
            }, r.prototype.ack = function(e) {
                var t = this,
                    n = !1;
                return function() {
                    if (!n) {
                        n = !0;
                        var r = a(arguments);
                        u("sending ack %j", r);
                        var i = p(r) ? o.BINARY_ACK : o.ACK;
                        t.packet({
                            type: i,
                            id: e,
                            data: r
                        })
                    }
                }
            }, r.prototype.onack = function(e) {
                u("calling ack %s with %j", e.id, e.data);
                var t = this.acks[e.id];
                t.apply(this, e.data), delete this.acks[e.id]
            }, r.prototype.onconnect = function() {
                this.connected = !0, this.disconnected = !1, this.emit("connect"), this.emitBuffered()
            }, r.prototype.emitBuffered = function() {
                var e;
                for (e = 0; e < this.receiveBuffer.length; e++) f.apply(this, this.receiveBuffer[e]);
                for (this.receiveBuffer = [], e = 0; e < this.sendBuffer.length; e++) this.packet(this.sendBuffer[e]);
                this.sendBuffer = []
            }, r.prototype.ondisconnect = function() {
                u("server disconnect (%s)", this.nsp), this.destroy(), this.onclose("io server disconnect")
            }, r.prototype.destroy = function() {
                if (this.subs) {
                    for (var e = 0; e < this.subs.length; e++) this.subs[e].destroy();
                    this.subs = null
                }
                this.io.destroy(this)
            }, r.prototype.close = r.prototype.disconnect = function() {
                return this.connected && (u("performing disconnect (%s)", this.nsp), this.packet({
                    type: o.DISCONNECT
                })), this.destroy(), this.connected && this.onclose("io client disconnect"), this
            }
        }, {
            "./on": 37,
            "component-bind": 51,
            "component-emitter": 49,
            debug: 35,
            "has-binary": 56,
            "socket.io-parser": 36,
            "to-array": 55
        }],
        46: [function(e, t) {
            t.exports = {
                initiator: {
                    incoming: {
                        initiator: "recvonly",
                        responder: "sendonly",
                        both: "sendrecv",
                        none: "inactive",
                        recvonly: "initiator",
                        sendonly: "responder",
                        sendrecv: "both",
                        inactive: "none"
                    },
                    outgoing: {
                        initiator: "sendonly",
                        responder: "recvonly",
                        both: "sendrecv",
                        none: "inactive",
                        recvonly: "responder",
                        sendonly: "initiator",
                        sendrecv: "both",
                        inactive: "none"
                    }
                },
                responder: {
                    incoming: {
                        initiator: "sendonly",
                        responder: "recvonly",
                        both: "sendrecv",
                        none: "inactive",
                        recvonly: "responder",
                        sendonly: "initiator",
                        sendrecv: "both",
                        inactive: "none"
                    },
                    outgoing: {
                        initiator: "recvonly",
                        responder: "sendonly",
                        both: "sendrecv",
                        none: "inactive",
                        recvonly: "initiator",
                        sendonly: "responder",
                        sendrecv: "both",
                        inactive: "none"
                    }
                }
            }
        }, {}],
        47: [function(e, t, n) {
            n.lines = function(e) {
                return e.split("\r\n").filter(function(e) {
                    return e.length > 0
                })
            }, n.findLine = function(e, t, n) {
                for (var r = e.length, o = 0; o < t.length; o++)
                    if (t[o].substr(0, r) === e) return t[o];
                if (!n) return !1;
                for (var i = 0; i < n.length; i++)
                    if (n[i].substr(0, r) === e) return n[i];
                return !1
            }, n.findLines = function(e, t, n) {
                for (var r = [], o = e.length, i = 0; i < t.length; i++) t[i].substr(0, o) === e && r.push(t[i]);
                if (r.length || !n) return r;
                for (var a = 0; a < n.length; a++) n[a].substr(0, o) === e && r.push(n[a]);
                return r
            }, n.mline = function(e) {
                for (var t = e.substr(2).split(" "), n = {
                        media: t[0],
                        port: t[1],
                        proto: t[2],
                        formats: []
                    }, r = 3; r < t.length; r++) t[r] && n.formats.push(t[r]);
                return n
            }, n.rtpmap = function(e) {
                var t = e.substr(9).split(" "),
                    n = {
                        id: t.shift()
                    };
                return t = t[0].split("/"), n.name = t[0], n.clockrate = t[1], n.channels = 3 == t.length ? t[2] : "1", n
            }, n.sctpmap = function(e) {
                var t = e.substr(10).split(" "),
                    n = {
                        number: t.shift(),
                        protocol: t.shift(),
                        streams: t.shift()
                    };
                return n
            }, n.fmtp = function(e) {
                for (var t, n, r, o = e.substr(e.indexOf(" ") + 1).split(";"), i = [], a = 0; a < o.length; a++) t = o[a].split("="), n = t[0].trim(), r = t[1], n && r ? i.push({
                    key: n,
                    value: r
                }) : n && i.push({
                    key: "",
                    value: n
                });
                return i
            }, n.crypto = function(e) {
                var t = e.substr(9).split(" "),
                    n = {
                        tag: t[0],
                        cipherSuite: t[1],
                        keyParams: t[2],
                        sessionParams: t.slice(3).join(" ")
                    };
                return n
            }, n.fingerprint = function(e) {
                var t = e.substr(14).split(" ");
                return {
                    hash: t[0],
                    value: t[1]
                }
            }, n.extmap = function(e) {
                var t = e.substr(9).split(" "),
                    n = {},
                    r = t.shift(),
                    o = r.indexOf("/");
                return o >= 0 ? (n.id = r.substr(0, o), n.senders = r.substr(o + 1)) : (n.id = r, n.senders = "sendrecv"), n.uri = t.shift() || "", n
            }, n.rtcpfb = function(e) {
                var t = e.substr(10).split(" "),
                    n = {};
                return n.id = t.shift(), n.type = t.shift(), "trr-int" === n.type ? n.value = t.shift() : n.subtype = t.shift() || "", n.parameters = t, n
            }, n.candidate = function(e) {
                var t;
                t = 0 === e.indexOf("a=candidate:") ? e.substring(12).split(" ") : e.substring(10).split(" ");
                for (var n = {
                        foundation: t[0],
                        component: t[1],
                        protocol: t[2].toLowerCase(),
                        priority: t[3],
                        ip: t[4],
                        port: t[5],
                        type: t[7],
                        generation: "0"
                    }, r = 8; r < t.length; r += 2) "raddr" === t[r] ? n.relAddr = t[r + 1] : "rport" === t[r] ? n.relPort = t[r + 1] : "generation" === t[r] ? n.generation = t[r + 1] : "tcptype" === t[r] && (n.tcpType = t[r + 1]);
                return n.network = "1", n
            }, n.sourceGroups = function(e) {
                for (var t = [], n = 0; n < e.length; n++) {
                    var r = e[n].substr(13).split(" ");
                    t.push({
                        semantics: r.shift(),
                        sources: r
                    })
                }
                return t
            }, n.sources = function(e) {
                for (var t = [], n = {}, r = 0; r < e.length; r++) {
                    var o = e[r].substr(7).split(" "),
                        i = o.shift();
                    if (!n[i]) {
                        var a = {
                            ssrc: i,
                            parameters: []
                        };
                        t.push(a), n[i] = a
                    }
                    o = o.join(" ").split(":");
                    var s = o.shift(),
                        c = o.join(":") || null;
                    n[i].parameters.push({
                        key: s,
                        value: c
                    })
                }
                return t
            }, n.groups = function(e) {
                for (var t, n = [], r = 0; r < e.length; r++) t = e[r].substr(8).split(" "), n.push({
                    semantics: t.shift(),
                    contents: t
                });
                return n
            }, n.bandwidth = function(e) {
                var t = e.substr(2).split(":"),
                    n = {};
                return n.type = t.shift(), n.bandwidth = t.shift(), n
            }, n.msid = function(e) {
                var t = e.substr(7),
                    n = t.split(" ");
                return {
                    msid: t,
                    mslabel: n[0],
                    label: n[1]
                }
            }
        }, {}],
        57: [function(e, t) {
            function n(e) {
                return r.Buffer && r.Buffer.isBuffer(e) || r.ArrayBuffer && e instanceof ArrayBuffer
            }
            var r = self;
            t.exports = n
        }, {}],
        49: [function(e, t) {
            function n(e) {
                return e ? r(e) : void 0
            }

            function r(e) {
                for (var t in n.prototype) e[t] = n.prototype[t];
                return e
            }
            t.exports = n, n.prototype.on = n.prototype.addEventListener = function(e, t) {
                return this._callbacks = this._callbacks || {}, (this._callbacks[e] = this._callbacks[e] || []).push(t), this
            }, n.prototype.once = function(e, t) {
                function n() {
                    r.off(e, n), t.apply(this, arguments)
                }
                var r = this;
                return this._callbacks = this._callbacks || {}, n.fn = t, this.on(e, n), this
            }, n.prototype.off = n.prototype.removeListener = n.prototype.removeAllListeners = n.prototype.removeEventListener = function(e, t) {
                if (this._callbacks = this._callbacks || {}, 0 == arguments.length) return this._callbacks = {}, this;
                var n = this._callbacks[e];
                if (!n) return this;
                if (1 == arguments.length) return delete this._callbacks[e], this;
                for (var r, o = 0; o < n.length; o++)
                    if (r = n[o], r === t || r.fn === t) {
                        n.splice(o, 1);
                        break
                    }
                return this
            }, n.prototype.emit = function(e) {
                this._callbacks = this._callbacks || {};
                var t = [].slice.call(arguments, 1),
                    n = this._callbacks[e];
                if (n) {
                    n = n.slice(0);
                    for (var r = 0, o = n.length; o > r; ++r) n[r].apply(this, t)
                }
                return this
            }, n.prototype.listeners = function(e) {
                return this._callbacks = this._callbacks || {}, this._callbacks[e] || []
            }, n.prototype.hasListeners = function(e) {
                return !!this.listeners(e).length
            }
        }, {}],
        48: [function(e, t) {
            var n = /^(?:(?![^:@]+:[^:@\/]*@)(http|https|ws|wss):\/\/)?((?:(([^:@]*)(?::([^:@]*))?)?@)?((?:[a-f0-9]{0,4}:){2,7}[a-f0-9]{0,4}|[^:\/?#]*)(?::(\d*))?)(((\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[?#]|$)))*\/?)?([^?#\/]*))(?:\?([^#]*))?(?:#(.*))?)/,
                r = ["source", "protocol", "authority", "userInfo", "user", "password", "host", "port", "relative", "path", "directory", "file", "query", "anchor"];
            t.exports = function(e) {
                for (var t = n.exec(e || ""), o = {}, i = 14; i--;) o[r[i]] = t[i] || "";
                return o
            }
        }, {}],
        25: [function(e, t) {
            function n(e) {
                return {
                    type: e.type,
                    sdp: e.sdp
                }
            }

            function r(e) {
                var t = {
                    label: e.id
                };
                return e.getAudioTracks().length && (t.audio = e.getAudioTracks().map(function(e) {
                    return e.id
                })), e.getVideoTracks().length && (t.video = e.getVideoTracks().map(function(e) {
                    return e.id
                })), t
            }

            function o(e, t) {
                var n = this;
                a.call(this), this.peerconnection = new window.RTCPeerConnection(e, t), this.trace = function(e, t) {
                    n.emit("PeerConnectionTrace", {
                        time: new Date,
                        type: e,
                        value: t || ""
                    })
                }, this.onicecandidate = null, this.peerconnection.onicecandidate = function(e) {
                    n.trace("onicecandidate", e.candidate), null !== n.onicecandidate && n.onicecandidate(e)
                }, this.onaddstream = null, this.peerconnection.onaddstream = function(e) {
                    n.trace("onaddstream", r(e.stream)), null !== n.onaddstream && n.onaddstream(e)
                }, this.onremovestream = null, this.peerconnection.onremovestream = function(e) {
                    n.trace("onremovestream", r(e.stream)), null !== n.onremovestream && n.onremovestream(e)
                }, this.onsignalingstatechange = null, this.peerconnection.onsignalingstatechange = function(e) {
                    n.trace("onsignalingstatechange", n.signalingState), null !== n.onsignalingstatechange && n.onsignalingstatechange(e)
                }, this.oniceconnectionstatechange = null, this.peerconnection.oniceconnectionstatechange = function(e) {
                    n.trace("oniceconnectionstatechange", n.iceConnectionState), null !== n.oniceconnectionstatechange && n.oniceconnectionstatechange(e)
                }, this.onnegotiationneeded = null, this.peerconnection.onnegotiationneeded = function(e) {
                    n.trace("onnegotiationneeded"), null !== n.onnegotiationneeded && n.onnegotiationneeded(e)
                }, n.ondatachannel = null, this.peerconnection.ondatachannel = function(e) {
                    n.trace("ondatachannel", e), null !== n.ondatachannel && n.ondatachannel(e)
                }, this.getLocalStreams = this.peerconnection.getLocalStreams.bind(this.peerconnection), this.getRemoteStreams = this.peerconnection.getRemoteStreams.bind(this.peerconnection)
            }
            var i = e("util");
            e("webrtc-adapter-test");
            var a = e("wildemitter");
            i.inherits(o, a), ["signalingState", "iceConnectionState", "localDescription", "remoteDescription"].forEach(function(e) {
                Object.defineProperty(o.prototype, e, {
                    get: function() {
                        return this.peerconnection[e]
                    }
                })
            }), o.prototype.addStream = function(e) {
                this.trace("addStream", r(e)), this.peerconnection.addStream(e)
            }, o.prototype.removeStream = function(e) {
                this.trace("removeStream", r(e)), this.peerconnection.removeStream(e)
            }, o.prototype.createDataChannel = function(e, t) {
                return this.trace("createDataChannel", e, t), this.peerconnection.createDataChannel(e, t)
            }, o.prototype.setLocalDescription = function(e, t, r) {
                var o = this;
                this.trace("setLocalDescription", n(e)), this.peerconnection.setLocalDescription(e, function() {
                    o.trace("setLocalDescriptionOnSuccess"), t && t()
                }, function(e) {
                    o.trace("setLocalDescriptionOnFailure", e), r && r(e)
                })
            }, o.prototype.setRemoteDescription = function(e, t, r) {
                var o = this;
                this.trace("setRemoteDescription", n(e)), this.peerconnection.setRemoteDescription(e, function() {
                    o.trace("setRemoteDescriptionOnSuccess"), t && t()
                }, function(e) {
                    o.trace("setRemoteDescriptionOnFailure", e), r && r(e)
                })
            }, o.prototype.close = function() {
                this.trace("stop"), "closed" != this.peerconnection.signalingState && this.peerconnection.close()
            }, o.prototype.createOffer = function(e, t, r) {
                var o = this;
                this.trace("createOffer", r), this.peerconnection.createOffer(function(t) {
                    o.trace("createOfferOnSuccess", n(t)), e && e(t)
                }, function(e) {
                    o.trace("createOfferOnFailure", e), t && t(e)
                }, r)
            }, o.prototype.createAnswer = function(e, t, r) {
                var o = this;
                this.trace("createAnswer", r), this.peerconnection.createAnswer(function(t) {
                    o.trace("createAnswerOnSuccess", n(t)), e && e(t)
                }, function(e) {
                    o.trace("createAnswerOnFailure", e), t && t(e)
                }, r)
            }, o.prototype.addIceCandidate = function(e, t, n) {
                var r = this;
                this.trace("addIceCandidate", e), this.peerconnection.addIceCandidate(e, function() {
                    t && t()
                }, function(e) {
                    r.trace("addIceCandidateOnFailure", e), n && n(e)
                })
            }, o.prototype.getStats = function() {
                this.peerconnection.getStats.apply(this.peerconnection, arguments)
            }, t.exports = o
        }, {
            util: 8,
            "webrtc-adapter-test": 23,
            wildemitter: 4
        }],
        52: [function(e, t, n) {
            var r = Object.prototype.hasOwnProperty;
            n.keys = Object.keys || function(e) {
                var t = [];
                for (var n in e) r.call(e, n) && t.push(n);
                return t
            }, n.values = function(e) {
                var t = [];
                for (var n in e) r.call(e, n) && t.push(e[n]);
                return t
            }, n.merge = function(e, t) {
                for (var n in t) r.call(t, n) && (e[n] = t[n]);
                return e
            }, n.length = function(e) {
                return n.keys(e).length
            }, n.isEmpty = function(e) {
                return 0 == n.length(e)
            }
        }, {}],
        51: [function(e, t) {
            var n = [].slice;
            t.exports = function(e, t) {
                if ("string" == typeof t && (t = e[t]), "function" != typeof t) throw new Error("bind() requires a function");
                var r = n.call(arguments, 2);
                return function() {
                    return t.apply(e, r.concat(n.call(arguments)))
                }
            }
        }, {}],
        54: [function(e, t) {
            function n(e) {
                e = e || {}, this.ms = e.min || 100, this.max = e.max || 1e4, this.factor = e.factor || 2, this.jitter = e.jitter > 0 && e.jitter <= 1 ? e.jitter : 0, this.attempts = 0
            }
            t.exports = n, n.prototype.duration = function() {
                var e = this.ms * Math.pow(this.factor, this.attempts++);
                if (this.jitter) {
                    var t = Math.random(),
                        n = Math.floor(t * this.jitter * e);
                    e = 0 == (1 & Math.floor(10 * t)) ? e - n : e + n
                }
                return 0 | Math.min(e, this.max)
            }, n.prototype.reset = function() {
                this.attempts = 0
            }, n.prototype.setMin = function(e) {
                this.ms = e
            }, n.prototype.setMax = function(e) {
                this.max = e
            }, n.prototype.setJitter = function(e) {
                this.jitter = e
            }
        }, {}],
        53: [function(e, t) {
            var n = [].indexOf;
            t.exports = function(e, t) {
                if (n) return e.indexOf(t);
                for (var r = 0; r < e.length; ++r)
                    if (e[r] === t) return r;
                return -1
            }
        }, {}],
        55: [function(e, t) {
            function n(e, t) {
                var n = [];
                t = t || 0;
                for (var r = t || 0; r < e.length; r++) n[r - t] = e[r];
                return n
            }
            t.exports = n
        }, {}],
        58: [function(e, t) {
            t.exports = Array.isArray || function(e) {
                return "[object Array]" == Object.prototype.toString.call(e)
            }
        }, {}],
        36: [function(e, t, n) {
            function r() {}

            function o(e) {
                var t = "",
                    r = !1;
                return t += e.type, (n.BINARY_EVENT == e.type || n.BINARY_ACK == e.type) && (t += e.attachments, t += "-"), e.nsp && "/" != e.nsp && (r = !0, t += e.nsp), null != e.id && (r && (t += ",", r = !1), t += e.id), null != e.data && (r && (t += ","), t += d.stringify(e.data)), p("encoded %j as %s", e, t), t
            }

            function i(e, t) {
                function n(e) {
                    var n = l.deconstructPacket(e),
                        r = o(n.packet),
                        i = n.buffers;
                    i.unshift(r), t(i)
                }
                l.removeBlobs(e, n)
            }

            function a() {
                this.reconstructor = null
            }

            function s(e) {
                var t = {},
                    r = 0;
                if (t.type = Number(e.charAt(0)), null == n.types[t.type]) return u();
                if (n.BINARY_EVENT == t.type || n.BINARY_ACK == t.type) {
                    for (var o = "";
                        "-" != e.charAt(++r) && (o += e.charAt(r), r != e.length););
                    if (o != Number(o) || "-" != e.charAt(r)) throw new Error("Illegal attachments");
                    t.attachments = Number(o)
                }
                if ("/" == e.charAt(r + 1))
                    for (t.nsp = ""; ++r;) {
                        var i = e.charAt(r);
                        if ("," == i) break;
                        if (t.nsp += i, r == e.length) break
                    } else t.nsp = "/";
                var a = e.charAt(r + 1);
                if ("" !== a && Number(a) == a) {
                    for (t.id = ""; ++r;) {
                        var i = e.charAt(r);
                        if (null == i || Number(i) != i) {
                            --r;
                            break
                        }
                        if (t.id += e.charAt(r), r == e.length) break
                    }
                    t.id = Number(t.id)
                }
                if (e.charAt(++r)) try {
                    t.data = d.parse(e.substr(r))
                } catch (s) {
                    return u()
                }
                return p("decoded %s as %j", e, t), t
            }

            function c(e) {
                this.reconPack = e, this.buffers = []
            }

            function u() {
                return {
                    type: n.ERROR,
                    data: "parser error"
                }
            }
            var p = e("debug")("socket.io-parser"),
                d = e("json3");
            e("isarray");
            var f = e("component-emitter"),
                l = e("./binary"),
                h = e("./is-buffer");
            n.protocol = 4, n.types = ["CONNECT", "DISCONNECT", "EVENT", "BINARY_EVENT", "ACK", "BINARY_ACK", "ERROR"], n.CONNECT = 0, n.DISCONNECT = 1, n.EVENT = 2, n.ACK = 3, n.ERROR = 4, n.BINARY_EVENT = 5, n.BINARY_ACK = 6, n.Encoder = r, n.Decoder = a, r.prototype.encode = function(e, t) {
                if (p("encoding packet %j", e), n.BINARY_EVENT == e.type || n.BINARY_ACK == e.type) i(e, t);
                else {
                    var r = o(e);
                    t([r])
                }
            }, f(a.prototype), a.prototype.add = function(e) {
                var t;
                if ("string" == typeof e) t = s(e), n.BINARY_EVENT == t.type || n.BINARY_ACK == t.type ? (this.reconstructor = new c(t), 0 === this.reconstructor.reconPack.attachments && this.emit("decoded", t)) : this.emit("decoded", t);
                else {
                    if (!h(e) && !e.base64) throw new Error("Unknown type: " + e);
                    if (!this.reconstructor) throw new Error("got binary data when not reconstructing a packet");
                    t = this.reconstructor.takeBinaryData(e), t && (this.reconstructor = null, this.emit("decoded", t))
                }
            }, a.prototype.destroy = function() {
                this.reconstructor && this.reconstructor.finishedReconstruction()
            }, c.prototype.takeBinaryData = function(e) {
                if (this.buffers.push(e), this.buffers.length == this.reconPack.attachments) {
                    var t = l.reconstructPacket(this.reconPack, this.buffers);
                    return this.finishedReconstruction(), t
                }
                return null
            }, c.prototype.finishedReconstruction = function() {
                this.reconPack = null, this.buffers = []
            }
        }, {
            "./binary": 59,
            "./is-buffer": 57,
            "component-emitter": 49,
            debug: 35,
            isarray: 58,
            json3: 60
        }],
        60: [function(t, n, r) {
            ! function(t) {
                function n(e) {
                    if (n[e] !== a) return n[e];
                    var t;
                    if ("bug-string-char-index" == e) t = "a" != "a" [0];
                    else if ("json" == e) t = n("json-stringify") && n("json-parse");
                    else {
                        var r, o = '{"a":[1,true,false,null,"\\u0000\\b\\n\\f\\r\\t"]}';
                        if ("json-stringify" == e) {
                            var i = p.stringify,
                                c = "function" == typeof i && d;
                            if (c) {
                                (r = function() {
                                    return 1
                                }).toJSON = r;
                                try {
                                    c = "0" === i(0) && "0" === i(new Number) && '""' == i(new String) && i(s) === a && i(a) === a && i() === a && "1" === i(r) && "[1]" == i([r]) && "[null]" == i([a]) && "null" == i(null) && "[null,null,null]" == i([a, s, null]) && i({
                                        a: [r, !0, !1, null, "\0\b\n\f\r	"]
                                    }) == o && "1" === i(null, r) && "[\n 1,\n 2\n]" == i([1, 2], null, 1) && '"-271821-04-20T00:00:00.000Z"' == i(new Date(-864e13)) && '"+275760-09-13T00:00:00.000Z"' == i(new Date(864e13)) && '"-000001-01-01T00:00:00.000Z"' == i(new Date(-621987552e5)) && '"1969-12-31T23:59:59.999Z"' == i(new Date(-1))
                                } catch (u) {
                                    c = !1
                                }
                            }
                            t = c
                        }
                        if ("json-parse" == e) {
                            var f = p.parse;
                            if ("function" == typeof f) try {
                                if (0 === f("0") && !f(!1)) {
                                    r = f(o);
                                    var l = 5 == r.a.length && 1 === r.a[0];
                                    if (l) {
                                        try {
                                            l = !f('"	"')
                                        } catch (u) {}
                                        if (l) try {
                                            l = 1 !== f("01")
                                        } catch (u) {}
                                        if (l) try {
                                            l = 1 !== f("1.")
                                        } catch (u) {}
                                    }
                                }
                            } catch (u) {
                                l = !1
                            }
                            t = l
                        }
                    }
                    return n[e] = !!t
                }
                var o, i, a, s = {}.toString,
                    c = "function" == typeof e && e.amd,
                    u = "object" == typeof JSON && JSON,
                    p = "object" == typeof r && r && !r.nodeType && r;
                p && u ? (p.stringify = u.stringify, p.parse = u.parse) : p = t.JSON = u || {};
                var d = new Date(-0xc782b5b800cec);
                try {
                    d = -109252 == d.getUTCFullYear() && 0 === d.getUTCMonth() && 1 === d.getUTCDate() && 10 == d.getUTCHours() && 37 == d.getUTCMinutes() && 6 == d.getUTCSeconds() && 708 == d.getUTCMilliseconds()
                } catch (f) {}
                if (!n("json")) {
                    var l = "[object Function]",
                        h = "[object Date]",
                        g = "[object Number]",
                        m = "[object String]",
                        v = "[object Array]",
                        y = "[object Boolean]",
                        b = n("bug-string-char-index");
                    if (!d) var w = Math.floor,
                        S = [0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334],
                        k = function(e, t) {
                            return S[t] + 365 * (e - 1970) + w((e - 1969 + (t = +(t > 1))) / 4) - w((e - 1901 + t) / 100) + w((e - 1601 + t) / 400)
                        };
                    (o = {}.hasOwnProperty) || (o = function(e) {
                        var t, n = {};
                        return (n.__proto__ = null, n.__proto__ = {
                            toString: 1
                        }, n).toString != s ? o = function(e) {
                            var t = this.__proto__,
                                n = (this.__proto__ = null, e in this);
                            return this.__proto__ = t, n
                        } : (t = n.constructor, o = function(e) {
                            var n = (this.constructor || t).prototype;
                            return e in this && !(e in n && this[e] === n[e])
                        }), n = null, o.call(this, e)
                    });
                    var C = {
                            "boolean": 1,
                            number: 1,
                            string: 1,
                            undefined: 1
                        },
                        x = function(e, t) {
                            var n = typeof e[t];
                            return "object" == n ? !!e[t] : !C[n]
                        };
                    if (i = function(e, t) {
                            var n, r, a, c = 0;
                            (n = function() {
                                this.valueOf = 0
                            }).prototype.valueOf = 0, r = new n;
                            for (a in r) o.call(r, a) && c++;
                            return n = r = null, c ? i = 2 == c ? function(e, t) {
                                var n, r = {},
                                    i = s.call(e) == l;
                                for (n in e) i && "prototype" == n || o.call(r, n) || !(r[n] = 1) || !o.call(e, n) || t(n)
                            } : function(e, t) {
                                var n, r, i = s.call(e) == l;
                                for (n in e) i && "prototype" == n || !o.call(e, n) || (r = "constructor" === n) || t(n);
                                (r || o.call(e, n = "constructor")) && t(n)
                            } : (r = ["valueOf", "toString", "toLocaleString", "propertyIsEnumerable", "isPrototypeOf", "hasOwnProperty", "constructor"], i = function(e, t) {
                                var n, i, a = s.call(e) == l,
                                    c = !a && "function" != typeof e.constructor && x(e, "hasOwnProperty") ? e.hasOwnProperty : o;
                                for (n in e) a && "prototype" == n || !c.call(e, n) || t(n);
                                for (i = r.length; n = r[--i]; c.call(e, n) && t(n));
                            }), i(e, t)
                        }, !n("json-stringify")) {
                        var E = {
                                92: "\\\\",
                                34: '\\"',
                                8: "\\b",
                                12: "\\f",
                                10: "\\n",
                                13: "\\r",
                                9: "\\t"
                            },
                            j = "000000",
                            O = function(e, t) {
                                return (j + (t || 0)).slice(-e)
                            },
                            T = "\\u00",
                            D = function(e) {
                                var t, n = '"',
                                    r = 0,
                                    o = e.length,
                                    i = o > 10 && b;
                                for (i && (t = e.split("")); o > r; r++) {
                                    var a = e.charCodeAt(r);
                                    switch (a) {
                                        case 8:
                                        case 9:
                                        case 10:
                                        case 12:
                                        case 13:
                                        case 34:
                                        case 92:
                                            n += E[a];
                                            break;
                                        default:
                                            if (32 > a) {
                                                n += T + O(2, a.toString(16));
                                                break
                                            }
                                            n += i ? t[r] : b ? e.charAt(r) : e[r]
                                    }
                                }
                                return n + '"'
                            },
                            A = function(e, t, n, r, c, u, p) {
                                var d, f, l, b, S, C, x, E, j, T, R, M, P, _, I, L;
                                try {
                                    d = t[e]
                                } catch (N) {}
                                if ("object" == typeof d && d)
                                    if (f = s.call(d), f != h || o.call(d, "toJSON")) "function" == typeof d.toJSON && (f != g && f != m && f != v || o.call(d, "toJSON")) && (d = d.toJSON(e));
                                    else if (d > -1 / 0 && 1 / 0 > d) {
                                    if (k) {
                                        for (S = w(d / 864e5), l = w(S / 365.2425) + 1970 - 1; k(l + 1, 0) <= S; l++);
                                        for (b = w((S - k(l, 0)) / 30.42); k(l, b + 1) <= S; b++);
                                        S = 1 + S - k(l, b), C = (d % 864e5 + 864e5) % 864e5, x = w(C / 36e5) % 24, E = w(C / 6e4) % 60, j = w(C / 1e3) % 60, T = C % 1e3
                                    } else l = d.getUTCFullYear(), b = d.getUTCMonth(), S = d.getUTCDate(), x = d.getUTCHours(), E = d.getUTCMinutes(), j = d.getUTCSeconds(), T = d.getUTCMilliseconds();
                                    d = (0 >= l || l >= 1e4 ? (0 > l ? "-" : "+") + O(6, 0 > l ? -l : l) : O(4, l)) + "-" + O(2, b + 1) + "-" + O(2, S) + "T" + O(2, x) + ":" + O(2, E) + ":" + O(2, j) + "." + O(3, T) + "Z"
                                } else d = null;
                                if (n && (d = n.call(t, e, d)), null === d) return "null";
                                if (f = s.call(d), f == y) return "" + d;
                                if (f == g) return d > -1 / 0 && 1 / 0 > d ? "" + d : "null";
                                if (f == m) return D("" + d);
                                if ("object" == typeof d) {
                                    for (_ = p.length; _--;)
                                        if (p[_] === d) throw TypeError();
                                    if (p.push(d), R = [], I = u, u += c, f == v) {
                                        for (P = 0, _ = d.length; _ > P; P++) M = A(P, d, n, r, c, u, p), R.push(M === a ? "null" : M);
                                        L = R.length ? c ? "[\n" + u + R.join(",\n" + u) + "\n" + I + "]" : "[" + R.join(",") + "]" : "[]"
                                    } else i(r || d, function(e) {
                                        var t = A(e, d, n, r, c, u, p);
                                        t !== a && R.push(D(e) + ":" + (c ? " " : "") + t)
                                    }), L = R.length ? c ? "{\n" + u + R.join(",\n" + u) + "\n" + I + "}" : "{" + R.join(",") + "}" : "{}";
                                    return p.pop(), L
                                }
                            };
                        p.stringify = function(e, t, n) {
                            var r, o, i, a;
                            if ("function" == typeof t || "object" == typeof t && t)
                                if ((a = s.call(t)) == l) o = t;
                                else if (a == v) {
                                i = {};
                                for (var c, u = 0, p = t.length; p > u; c = t[u++], a = s.call(c), (a == m || a == g) && (i[c] = 1));
                            }
                            if (n)
                                if ((a = s.call(n)) == g) {
                                    if ((n -= n % 1) > 0)
                                        for (r = "", n > 10 && (n = 10); r.length < n; r += " ");
                                } else a == m && (r = n.length <= 10 ? n : n.slice(0, 10));
                            return A("", (c = {}, c[""] = e, c), o, i, r, "", [])
                        }
                    }
                    if (!n("json-parse")) {
                        var R, M, P = String.fromCharCode,
                            _ = {
                                92: "\\",
                                34: '"',
                                47: "/",
                                98: "\b",
                                116: "	",
                                110: "\n",
                                102: "\f",
                                114: "\r"
                            },
                            I = function() {
                                throw R = M = null, SyntaxError()
                            },
                            L = function() {
                                for (var e, t, n, r, o, i = M, a = i.length; a > R;) switch (o = i.charCodeAt(R)) {
                                    case 9:
                                    case 10:
                                    case 13:
                                    case 32:
                                        R++;
                                        break;
                                    case 123:
                                    case 125:
                                    case 91:
                                    case 93:
                                    case 58:
                                    case 44:
                                        return e = b ? i.charAt(R) : i[R], R++, e;
                                    case 34:
                                        for (e = "@", R++; a > R;)
                                            if (o = i.charCodeAt(R), 32 > o) I();
                                            else if (92 == o) switch (o = i.charCodeAt(++R)) {
                                            case 92:
                                            case 34:
                                            case 47:
                                            case 98:
                                            case 116:
                                            case 110:
                                            case 102:
                                            case 114:
                                                e += _[o], R++;
                                                break;
                                            case 117:
                                                for (t = ++R, n = R + 4; n > R; R++) o = i.charCodeAt(R), o >= 48 && 57 >= o || o >= 97 && 102 >= o || o >= 65 && 70 >= o || I();
                                                e += P("0x" + i.slice(t, R));
                                                break;
                                            default:
                                                I()
                                        } else {
                                            if (34 == o) break;
                                            for (o = i.charCodeAt(R), t = R; o >= 32 && 92 != o && 34 != o;) o = i.charCodeAt(++R);
                                            e += i.slice(t, R)
                                        }
                                        if (34 == i.charCodeAt(R)) return R++, e;
                                        I();
                                    default:
                                        if (t = R, 45 == o && (r = !0, o = i.charCodeAt(++R)), o >= 48 && 57 >= o) {
                                            for (48 == o && (o = i.charCodeAt(R + 1), o >= 48 && 57 >= o) && I(), r = !1; a > R && (o = i.charCodeAt(R), o >= 48 && 57 >= o); R++);
                                            if (46 == i.charCodeAt(R)) {
                                                for (n = ++R; a > n && (o = i.charCodeAt(n), o >= 48 && 57 >= o); n++);
                                                n == R && I(), R = n
                                            }
                                            if (o = i.charCodeAt(R), 101 == o || 69 == o) {
                                                for (o = i.charCodeAt(++R), (43 == o || 45 == o) && R++, n = R; a > n && (o = i.charCodeAt(n), o >= 48 && 57 >= o); n++);
                                                n == R && I(), R = n
                                            }
                                            return +i.slice(t, R)
                                        }
                                        if (r && I(), "true" == i.slice(R, R + 4)) return R += 4, !0;
                                        if ("false" == i.slice(R, R + 5)) return R += 5, !1;
                                        if ("null" == i.slice(R, R + 4)) return R += 4, null;
                                        I()
                                }
                                return "$"
                            },
                            N = function(e) {
                                var t, n;
                                if ("$" == e && I(), "string" == typeof e) {
                                    if ("@" == (b ? e.charAt(0) : e[0])) return e.slice(1);
                                    if ("[" == e) {
                                        for (t = []; e = L(), "]" != e; n || (n = !0)) n && ("," == e ? (e = L(), "]" == e && I()) : I()), "," == e && I(), t.push(N(e));
                                        return t
                                    }
                                    if ("{" == e) {
                                        for (t = {}; e = L(), "}" != e; n || (n = !0)) n && ("," == e ? (e = L(), "}" == e && I()) : I()), ("," == e || "string" != typeof e || "@" != (b ? e.charAt(0) : e[0]) || ":" != L()) && I(), t[e.slice(1)] = N(L());
                                        return t
                                    }
                                    I()
                                }
                                return e
                            },
                            B = function(e, t, n) {
                                var r = U(e, t, n);
                                r === a ? delete e[t] : e[t] = r
                            },
                            U = function(e, t, n) {
                                var r, o = e[t];
                                if ("object" == typeof o && o)
                                    if (s.call(o) == v)
                                        for (r = o.length; r--;) B(o, r, n);
                                    else i(o, function(e) {
                                        B(o, e, n)
                                    });
                                return n.call(e, t, o)
                            };
                        p.parse = function(e, t) {
                            var n, r;
                            return R = 0, M = "" + e, n = N(L()), "$" != L() && I(), R = M = null, t && s.call(t) == l ? U((r = {}, r[""] = n, r), "", t) : n
                        }
                    }
                }
                c && e(function() {
                    return p
                })
            }(this)
        }, {}],
        39: [function(e, t) {
            function n(e, t) {
                return f(e, t, u)
            }

            function r(e) {
                return function(t) {
                    return null == t ? void 0 : t[e]
                }
            }

            function o(e, t) {
                return function(n, r) {
                    var o = n ? l(n) : 0;
                    if (!a(o)) return e(n, r);
                    for (var i = t ? o : -1, c = s(n);
                        (t ? i-- : ++i < o) && r(c[i], i, c) !== !1;);
                    return n
                }
            }

            function i(e) {
                return function(t, n, r) {
                    for (var o = s(t), i = r(t), a = i.length, c = e ? a : -1; e ? c-- : ++c < a;) {
                        var u = i[c];
                        if (n(o[u], u, o) === !1) break
                    }
                    return t
                }
            }

            function a(e) {
                return "number" == typeof e && e > -1 && 0 == e % 1 && p >= e
            }

            function s(e) {
                return c(e) ? e : Object(e)
            }

            function c(e) {
                var t = typeof e;
                return !!e && ("object" == t || "function" == t)
            }
            var u = e("lodash.keys"),
                p = 9007199254740991,
                d = o(n),
                f = i(),
                l = r("length");
            t.exports = d
        }, {
            "lodash.keys": 61
        }],
        50: [function(e, t) {
            t.exports = e("./lib/")
        }, {
            "./lib/": 62
        }],
        59: [function(e, t, n) {
            var r = self,
                o = e("isarray"),
                i = e("./is-buffer");
            n.deconstructPacket = function(e) {
                function t(e) {
                    if (!e) return e;
                    if (i(e)) {
                        var r = {
                            _placeholder: !0,
                            num: n.length
                        };
                        return n.push(e), r
                    }
                    if (o(e)) {
                        for (var a = new Array(e.length), s = 0; s < e.length; s++) a[s] = t(e[s]);
                        return a
                    }
                    if ("object" == typeof e && !(e instanceof Date)) {
                        var a = {};
                        for (var c in e) a[c] = t(e[c]);
                        return a
                    }
                    return e
                }
                var n = [],
                    r = e.data,
                    a = e;
                return a.data = t(r), a.attachments = n.length, {
                    packet: a,
                    buffers: n
                }
            }, n.reconstructPacket = function(e, t) {
                function n(e) {
                    if (e && e._placeholder) {
                        var r = t[e.num];
                        return r
                    }
                    if (o(e)) {
                        for (var i = 0; i < e.length; i++) e[i] = n(e[i]);
                        return e
                    }
                    if (e && "object" == typeof e) {
                        for (var a in e) e[a] = n(e[a]);
                        return e
                    }
                    return e
                }
                return e.data = n(e.data), e.attachments = void 0, e
            }, n.removeBlobs = function(e, t) {
                function n(e, c, u) {
                    if (!e) return e;
                    if (r.Blob && e instanceof Blob || r.File && e instanceof File) {
                        a++;
                        var p = new FileReader;
                        p.onload = function() {
                            u ? u[c] = this.result : s = this.result, --a || t(s)
                        }, p.readAsArrayBuffer(e)
                    } else if (o(e))
                        for (var d = 0; d < e.length; d++) n(e[d], d, e);
                    else if (e && "object" == typeof e && !i(e))
                        for (var f in e) n(e[f], f, e)
                }
                var a = 0,
                    s = e;
                n(s), a || t(s)
            }
        }, {
            "./is-buffer": 57,
            isarray: 58
        }],
        63: [function(e, t) {
            function n(e, t) {
                for (var n = -1, r = e.length, o = Array(r); ++n < r;) o[n] = t(e[n], n, e);
                return o
            }
            t.exports = n
        }, {}],
        56: [function(e, t) {
            function n(e) {
                function t(e) {
                    if (!e) return !1;
                    if (r.Buffer && r.Buffer.isBuffer(e) || r.ArrayBuffer && e instanceof ArrayBuffer || r.Blob && e instanceof Blob || r.File && e instanceof File) return !0;
                    if (o(e)) {
                        for (var n = 0; n < e.length; n++)
                            if (t(e[n])) return !0
                    } else if (e && "object" == typeof e) {
                        e.toJSON && (e = e.toJSON());
                        for (var i in e)
                            if (Object.prototype.hasOwnProperty.call(e, i) && t(e[i])) return !0
                    }
                    return !1
                }
                return t(e)
            }
            var r = self,
                o = e("isarray");
            t.exports = n
        }, {
            isarray: 64
        }],
        43: [function(e, t) {
            function n(e) {
                return null == e ? "" : e + ""
            }

            function r(e) {
                if (o(e)) return e;
                var t = [];
                return n(e).replace(i, function(e, n, r, o) {
                    t.push(r ? o.replace(a, "$1") : n || e)
                }), t
            }
            var o = e("lodash.isarray"),
                i = /[^.[\]]+|\[(?:(-?\d+(?:\.\d+)?)|(["'])((?:(?!\2)[^\n\\]|\\.)*?)\2)\]/g,
                a = /\\(\\)?/g;
            t.exports = r
        }, {
            "lodash.isarray": 44
        }],
        45: [function(e, t) {
            function n(e, t) {
                var n = -1,
                    r = o(e) ? Array(e.length) : [];
                return u(e, function(e, o, i) {
                    r[++n] = t(e, o, i)
                }), r
            }

            function r(e) {
                return function(t) {
                    return null == t ? void 0 : t[e]
                }
            }

            function o(e) {
                return null != e && i(f(e))
            }

            function i(e) {
                return "number" == typeof e && e > -1 && 0 == e % 1 && d >= e
            }

            function a(e, t, r) {
                var o = p(e) ? s : n;
                return t = c(t, r, 3), o(e, t)
            }
            var s = e("lodash._arraymap"),
                c = e("lodash._basecallback"),
                u = e("lodash._baseeach"),
                p = e("lodash.isarray"),
                d = 9007199254740991,
                f = r("length");
            t.exports = a
        }, {
            "lodash._arraymap": 63,
            "lodash._basecallback": 65,
            "lodash._baseeach": 66,
            "lodash.isarray": 44
        }],
        64: [function(e, t) {
            t.exports = Array.isArray || function(e) {
                return "[object Array]" == Object.prototype.toString.call(e)
            }
        }, {}],
        67: [function(e, t) {
            function n(e) {
                return !!e && "object" == typeof e
            }

            function r(e, t) {
                var n = null == e ? void 0 : e[t];
                return a(n) ? n : void 0
            }

            function o(e) {
                return i(e) && f.call(e) == s
            }

            function i(e) {
                var t = typeof e;
                return !!e && ("object" == t || "function" == t)
            }

            function a(e) {
                return null == e ? !1 : o(e) ? l.test(p.call(e)) : n(e) && c.test(e)
            }
            var s = "[object Function]",
                c = /^\[object .+?Constructor\]$/,
                u = Object.prototype,
                p = Function.prototype.toString,
                d = u.hasOwnProperty,
                f = u.toString,
                l = RegExp("^" + p.call(d).replace(/[\\^$.*+?()[\]{}|]/g, "\\$&").replace(/hasOwnProperty|(function).*?(?=\\\()| for .+?(?=\\\])/g, "$1.*?") + "$");
            t.exports = r
        }, {}],
        68: [function(e, t) {
            function n(e, t, n) {
                if ("function" != typeof e) return r;
                if (void 0 === t) return e;
                switch (n) {
                    case 1:
                        return function(n) {
                            return e.call(t, n)
                        };
                    case 3:
                        return function(n, r, o) {
                            return e.call(t, n, r, o)
                        };
                    case 4:
                        return function(n, r, o, i) {
                            return e.call(t, n, r, o, i)
                        };
                    case 5:
                        return function(n, r, o, i, a) {
                            return e.call(t, n, r, o, i, a)
                        }
                }
                return function() {
                    return e.apply(t, arguments)
                }
            }

            function r(e) {
                return e
            }
            t.exports = n
        }, {}],
        69: [function(e, t) {
            function n(e) {
                return !!e && "object" == typeof e
            }

            function r(e) {
                return function(t) {
                    return null == t ? void 0 : t[e]
                }
            }

            function o(e) {
                return null != e && i(d(e))
            }

            function i(e) {
                return "number" == typeof e && e > -1 && 0 == e % 1 && p >= e
            }

            function a(e) {
                return n(e) && o(e) && c.call(e, "callee") && !u.call(e, "callee")
            }
            var s = Object.prototype,
                c = s.hasOwnProperty,
                u = s.propertyIsEnumerable,
                p = 9007199254740991,
                d = r("length");
            t.exports = a
        }, {}],
        66: [function(e, t) {
            function n(e, t) {
                return f(e, t, u)
            }

            function r(e) {
                return function(t) {
                    return null == t ? void 0 : t[e]
                }
            }

            function o(e, t) {
                return function(n, r) {
                    var o = n ? l(n) : 0;
                    if (!a(o)) return e(n, r);
                    for (var i = t ? o : -1, c = s(n);
                        (t ? i-- : ++i < o) && r(c[i], i, c) !== !1;);
                    return n
                }
            }

            function i(e) {
                return function(t, n, r) {
                    for (var o = s(t), i = r(t), a = i.length, c = e ? a : -1; e ? c-- : ++c < a;) {
                        var u = i[c];
                        if (n(o[u], u, o) === !1) break
                    }
                    return t
                }
            }

            function a(e) {
                return "number" == typeof e && e > -1 && 0 == e % 1 && p >= e
            }

            function s(e) {
                return c(e) ? e : Object(e)
            }

            function c(e) {
                var t = typeof e;
                return !!e && ("object" == t || "function" == t)
            }
            var u = e("lodash.keys"),
                p = 9007199254740991,
                d = o(n),
                f = i(),
                l = r("length");
            t.exports = d
        }, {
            "lodash.keys": 70
        }],
        62: [function(e, t) {
            t.exports = e("./socket"), t.exports.parser = e("engine.io-parser")
        }, {
            "./socket": 71,
            "engine.io-parser": 72
        }],
        65: [function(e, t) {
            function n(e) {
                return null == e ? "" : e + ""
            }

            function r(e, t, n) {
                var r = typeof e;
                return "function" == r ? void 0 === t ? e : S(e, t, n) : null == e ? y : "object" == r ? a(e) : void 0 === t ? b(e) : s(e, t)
            }

            function o(e, t, n) {
                if (null != e) {
                    void 0 !== n && n in h(e) && (t = [n]);
                    for (var r = 0, o = t.length; null != e && o > r;) e = e[t[r++]];
                    return r && r == o ? e : void 0
                }
            }

            function i(e, t, n) {
                var r = t.length,
                    o = r,
                    i = !n;
                if (null == e) return !o;
                for (e = h(e); r--;) {
                    var a = t[r];
                    if (i && a[2] ? a[1] !== e[a[0]] : !(a[0] in e)) return !1
                }
                for (; ++r < o;) {
                    a = t[r];
                    var s = a[0],
                        c = e[s],
                        u = a[1];
                    if (i && a[2]) {
                        if (void 0 === c && !(s in e)) return !1
                    } else {
                        var p = n ? n(c, u, s) : void 0;
                        if (!(void 0 === p ? w(u, c, n, !0) : p)) return !1
                    }
                }
                return !0
            }

            function a(e) {
                var t = d(e);
                if (1 == t.length && t[0][2]) {
                    var n = t[0][0],
                        r = t[0][1];
                    return function(e) {
                        return null == e ? !1 : e[n] === r && (void 0 !== r || n in h(e))
                    }
                }
                return function(e) {
                    return i(e, t)
                }
            }

            function s(e, t) {
                var n = k(e),
                    r = f(e) && l(t),
                    i = e + "";
                return e = g(e),
                    function(a) {
                        if (null == a) return !1;
                        var s = i;
                        if (a = h(a), !(!n && r || s in a)) {
                            if (a = 1 == e.length ? a : o(a, p(e, 0, -1)), null == a) return !1;
                            s = m(e), a = h(a)
                        }
                        return a[s] === t ? void 0 !== t || s in a : w(t, a[s], void 0, !0)
                    }
            }

            function c(e) {
                return function(t) {
                    return null == t ? void 0 : t[e]
                }
            }

            function u(e) {
                var t = e + "";
                return e = g(e),
                    function(n) {
                        return o(n, e, t)
                    }
            }

            function p(e, t, n) {
                var r = -1,
                    o = e.length;
                t = null == t ? 0 : +t || 0, 0 > t && (t = -t > o ? 0 : o + t), n = void 0 === n || n > o ? o : +n || 0, 0 > n && (n += o), o = t > n ? 0 : n - t >>> 0, t >>>= 0;
                for (var i = Array(o); ++r < o;) i[r] = e[r + t];
                return i
            }

            function d(e) {
                for (var t = C(e), n = t.length; n--;) t[n][2] = l(t[n][1]);
                return t
            }

            function f(e, t) {
                var n = typeof e;
                if ("string" == n && E.test(e) || "number" == n) return !0;
                if (k(e)) return !1;
                var r = !x.test(e);
                return r || null != t && e in h(t)
            }

            function l(e) {
                return e === e && !v(e)
            }

            function h(e) {
                return v(e) ? e : Object(e)
            }

            function g(e) {
                if (k(e)) return e;
                var t = [];
                return n(e).replace(j, function(e, n, r, o) {
                    t.push(r ? o.replace(O, "$1") : n || e)
                }), t
            }

            function m(e) {
                var t = e ? e.length : 0;
                return t ? e[t - 1] : void 0
            }

            function v(e) {
                var t = typeof e;
                return !!e && ("object" == t || "function" == t)
            }

            function y(e) {
                return e
            }

            function b(e) {
                return f(e) ? c(e) : u(e)
            }
            var w = e("lodash._baseisequal"),
                S = e("lodash._bindcallback"),
                k = e("lodash.isarray"),
                C = e("lodash.pairs"),
                x = /\.|\[(?:[^[\]]*|(["'])(?:(?!\1)[^\n\\]|\\.)*?\1)\]/,
                E = /^\w*$/,
                j = /[^.[\]]+|\[(?:(-?\d+(?:\.\d+)?)|(["'])((?:(?!\2)[^\n\\]|\\.)*?)\2)\]/g,
                O = /\\(\\)?/g;
            t.exports = r
        }, {
            "lodash._baseisequal": 73,
            "lodash._bindcallback": 68,
            "lodash.isarray": 44,
            "lodash.pairs": 74
        }],
        61: [function(e, t) {
            function n(e) {
                return function(t) {
                    return null == t ? void 0 : t[e]
                }
            }

            function r(e) {
                return null != e && i(v(e))
            }

            function o(e, t) {
                return e = "number" == typeof e || f.test(e) ? +e : -1, t = null == t ? m : t, e > -1 && 0 == e % 1 && t > e
            }

            function i(e) {
                return "number" == typeof e && e > -1 && 0 == e % 1 && m >= e
            }

            function a(e) {
                for (var t = c(e), n = t.length, r = n && e.length, a = !!r && i(r) && (d(e) || p(e)), s = -1, u = []; ++s < n;) {
                    var f = t[s];
                    (a && o(f, r) || h.call(e, f)) && u.push(f)
                }
                return u
            }

            function s(e) {
                var t = typeof e;
                return !!e && ("object" == t || "function" == t)
            }

            function c(e) {
                if (null == e) return [];
                s(e) || (e = Object(e));
                var t = e.length;
                t = t && i(t) && (d(e) || p(e)) && t || 0;
                for (var n = e.constructor, r = -1, a = "function" == typeof n && n.prototype === e, c = Array(t), u = t > 0; ++r < t;) c[r] = r + "";
                for (var f in e) u && o(f, t) || "constructor" == f && (a || !h.call(e, f)) || c.push(f);
                return c
            }
            var u = e("lodash._getnative"),
                p = e("lodash.isarguments"),
                d = e("lodash.isarray"),
                f = /^\d+$/,
                l = Object.prototype,
                h = l.hasOwnProperty,
                g = u(Object, "keys"),
                m = 9007199254740991,
                v = n("length"),
                y = g ? function(e) {
                    var t = null == e ? void 0 : e.constructor;
                    return "function" == typeof t && t.prototype === e || "function" != typeof e && r(e) ? a(e) : s(e) ? g(e) : []
                } : a;
            t.exports = y
        }, {
            "lodash._getnative": 67,
            "lodash.isarguments": 69,
            "lodash.isarray": 41
        }],
        75: [function(e, t) {
            function n(e) {
                return !!e && "object" == typeof e
            }

            function r(e) {
                return "number" == typeof e && e > -1 && 0 == e % 1 && R >= e
            }

            function o(e) {
                return n(e) && r(e.length) && !!T[A.call(e)]
            }
            var i = "[object Arguments]",
                a = "[object Array]",
                s = "[object Boolean]",
                c = "[object Date]",
                u = "[object Error]",
                p = "[object Function]",
                d = "[object Map]",
                f = "[object Number]",
                l = "[object Object]",
                h = "[object RegExp]",
                g = "[object Set]",
                m = "[object String]",
                v = "[object WeakMap]",
                y = "[object ArrayBuffer]",
                b = "[object Float32Array]",
                w = "[object Float64Array]",
                S = "[object Int8Array]",
                k = "[object Int16Array]",
                C = "[object Int32Array]",
                x = "[object Uint8Array]",
                E = "[object Uint8ClampedArray]",
                j = "[object Uint16Array]",
                O = "[object Uint32Array]",
                T = {};
            T[b] = T[w] = T[S] = T[k] = T[C] = T[x] = T[E] = T[j] = T[O] = !0, T[i] = T[a] = T[y] = T[s] = T[c] = T[u] = T[p] = T[d] = T[f] = T[l] = T[h] = T[g] = T[m] = T[v] = !1;
            var D = Object.prototype,
                A = D.toString,
                R = 9007199254740991;
            t.exports = o
        }, {}],
        76: [function(e, t) {
            t.exports = Object.keys || function(e) {
                var t = [],
                    n = Object.prototype.hasOwnProperty;
                for (var r in e) n.call(e, r) && t.push(r);
                return t
            }
        }, {}],
        77: [function(e, t, n) {
            function r(e) {
                var t, n = !1,
                    r = !1,
                    c = !1 !== e.jsonp;
                if (o.location) {
                    var u = "https:" == location.protocol,
                        p = location.port;
                    p || (p = u ? 443 : 80), n = e.hostname != location.hostname || p != e.port, r = e.secure != u
                }
                if (e.xdomain = n, e.xscheme = r, t = new i(e), "open" in t && !e.forceJSONP) return new a(e);
                if (!c) throw new Error("JSONP disabled");
                return new s(e)
            }
            var o = self,
                i = e("xmlhttprequest"),
                a = e("./polling-xhr"),
                s = e("./polling-jsonp"),
                c = e("./websocket");
            n.polling = r, n.websocket = c
        }, {
            "./polling-jsonp": 80,
            "./polling-xhr": 79,
            "./websocket": 81,
            xmlhttprequest: 78
        }],
        82: [function(e, t) {
            var n = /^(?:(?![^:@]+:[^:@\/]*@)(http|https|ws|wss):\/\/)?((?:(([^:@]*)(?::([^:@]*))?)?@)?((?:[a-f0-9]{0,4}:){2,7}[a-f0-9]{0,4}|[^:\/?#]*)(?::(\d*))?)(((\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[?#]|$)))*\/?)?([^?#\/]*))(?:\?([^#]*))?(?:#(.*))?)/,
                r = ["source", "protocol", "authority", "userInfo", "user", "password", "host", "port", "relative", "path", "directory", "file", "query", "anchor"];
            t.exports = function(e) {
                var t = e,
                    o = e.indexOf("["),
                    i = e.indexOf("]"); - 1 != o && -1 != i && (e = e.substring(0, o) + e.substring(o, i).replace(/:/g, ";") + e.substring(i, e.length));
                for (var a = n.exec(e || ""), s = {}, c = 14; c--;) s[r[c]] = a[c] || "";
                return -1 != o && -1 != i && (s.source = t, s.host = s.host.substring(1, s.host.length - 1).replace(/;/g, ":"), s.authority = s.authority.replace("[", "").replace("]", "").replace(/;/g, ":"), s.ipv6uri = !0), s
            }
        }, {}],
        83: [function(e, t) {
            var n = self,
                r = /^[\],:{}\s]*$/,
                o = /\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g,
                i = /"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g,
                a = /(?:^|:|,)(?:\s*\[)+/g,
                s = /^\s+/,
                c = /\s+$/;
            t.exports = function(e) {
                return "string" == typeof e && e ? (e = e.replace(s, "").replace(c, ""), n.JSON && JSON.parse ? JSON.parse(e) : r.test(e.replace(o, "@").replace(i, "]").replace(a, "")) ? new Function("return " + e)() : void 0) : null
            }
        }, {}],
        84: [function(e, t, n) {
            n.encode = function(e) {
                var t = "";
                for (var n in e) e.hasOwnProperty(n) && (t.length && (t += "&"), t += encodeURIComponent(n) + "=" + encodeURIComponent(e[n]));
                return t
            }, n.decode = function(e) {
                for (var t = {}, n = e.split("&"), r = 0, o = n.length; o > r; r++) {
                    var i = n[r].split("=");
                    t[decodeURIComponent(i[0])] = decodeURIComponent(i[1])
                }
                return t
            }
        }, {}],
        71: [function(e, t) {
            function n(e, t) {
                if (!(this instanceof n)) return new n(e, t);
                if (t = t || {}, e && "object" == typeof e && (t = e, e = null), e && (e = p(e), t.host = e.host, t.secure = "https" == e.protocol || "wss" == e.protocol, t.port = e.port, e.query && (t.query = e.query)), this.secure = null != t.secure ? t.secure : o.location && "https:" == location.protocol, t.host) {
                    var r = t.host.split(":");
                    t.hostname = r.shift(), r.length ? t.port = r.pop() : t.port || (t.port = this.secure ? "443" : "80")
                }
                this.agent = t.agent || !1, this.hostname = t.hostname || (o.location ? location.hostname : "localhost"), this.port = t.port || (o.location && location.port ? location.port : this.secure ? 443 : 80), this.query = t.query || {}, "string" == typeof this.query && (this.query = f.decode(this.query)), this.upgrade = !1 !== t.upgrade, this.path = (t.path || "/engine.io").replace(/\/$/, "") + "/", this.forceJSONP = !!t.forceJSONP, this.jsonp = !1 !== t.jsonp, this.forceBase64 = !!t.forceBase64, this.enablesXDR = !!t.enablesXDR, this.timestampParam = t.timestampParam || "t", this.timestampRequests = t.timestampRequests, this.transports = t.transports || ["polling", "websocket"], this.readyState = "", this.writeBuffer = [], this.callbackBuffer = [], this.policyPort = t.policyPort || 843, this.rememberUpgrade = t.rememberUpgrade || !1, this.binaryType = null, this.onlyBinaryUpgrades = t.onlyBinaryUpgrades, this.pfx = t.pfx || null, this.key = t.key || null, this.passphrase = t.passphrase || null, this.cert = t.cert || null, this.ca = t.ca || null, this.ciphers = t.ciphers || null, this.rejectUnauthorized = t.rejectUnauthorized || null, this.open()
            }

            function r(e) {
                var t = {};
                for (var n in e) e.hasOwnProperty(n) && (t[n] = e[n]);
                return t
            }
            var o = self,
                i = e("./transports"),
                a = e("component-emitter"),
                s = e("debug")("engine.io-client:socket"),
                c = e("indexof"),
                u = e("engine.io-parser"),
                p = e("parseuri"),
                d = e("parsejson"),
                f = e("parseqs");
            t.exports = n, n.priorWebsocketSuccess = !1, a(n.prototype), n.protocol = u.protocol, n.Socket = n, n.Transport = e("./transport"), n.transports = e("./transports"), n.parser = e("engine.io-parser"), n.prototype.createTransport = function(e) {
                s('creating transport "%s"', e);
                var t = r(this.query);
                t.EIO = u.protocol, t.transport = e, this.id && (t.sid = this.id);
                var n = new i[e]({
                    agent: this.agent,
                    hostname: this.hostname,
                    port: this.port,
                    secure: this.secure,
                    path: this.path,
                    query: t,
                    forceJSONP: this.forceJSONP,
                    jsonp: this.jsonp,
                    forceBase64: this.forceBase64,
                    enablesXDR: this.enablesXDR,
                    timestampRequests: this.timestampRequests,
                    timestampParam: this.timestampParam,
                    policyPort: this.policyPort,
                    socket: this,
                    pfx: this.pfx,
                    key: this.key,
                    passphrase: this.passphrase,
                    cert: this.cert,
                    ca: this.ca,
                    ciphers: this.ciphers,
                    rejectUnauthorized: this.rejectUnauthorized
                });
                return n
            }, n.prototype.open = function() {
                var e;
                if (this.rememberUpgrade && n.priorWebsocketSuccess && -1 != this.transports.indexOf("websocket")) e = "websocket";
                else {
                    if (0 == this.transports.length) {
                        var t = this;
                        return setTimeout(function() {
                            t.emit("error", "No transports available")
                        }, 0), void 0
                    }
                    e = this.transports[0]
                }
                this.readyState = "opening";
                var e;
                try {
                    e = this.createTransport(e)
                } catch (r) {
                    return this.transports.shift(), this.open(), void 0
                }
                e.open(), this.setTransport(e)
            }, n.prototype.setTransport = function(e) {
                s("setting transport %s", e.name);
                var t = this;
                this.transport && (s("clearing existing transport %s", this.transport.name), this.transport.removeAllListeners()), this.transport = e, e.on("drain", function() {
                    t.onDrain()
                }).on("packet", function(e) {
                    t.onPacket(e)
                }).on("error", function(e) {
                    t.onError(e)
                }).on("close", function() {
                    t.onClose("transport close")
                })
            }, n.prototype.probe = function(e) {
                function t() {
                    if (f.onlyBinaryUpgrades) {
                        var t = !this.supportsBinary && f.transport.supportsBinary;
                        d = d || t
                    }
                    d || (s('probe transport "%s" opened', e), p.send([{
                        type: "ping",
                        data: "probe"
                    }]), p.once("packet", function(t) {
                        if (!d)
                            if ("pong" == t.type && "probe" == t.data) {
                                if (s('probe transport "%s" pong', e), f.upgrading = !0, f.emit("upgrading", p), !p) return;
                                n.priorWebsocketSuccess = "websocket" == p.name, s('pausing current transport "%s"', f.transport.name), f.transport.pause(function() {
                                    d || "closed" != f.readyState && (s("changing transport and sending upgrade packet"), u(), f.setTransport(p), p.send([{
                                        type: "upgrade"
                                    }]), f.emit("upgrade", p), p = null, f.upgrading = !1, f.flush())
                                })
                            } else {
                                s('probe transport "%s" failed', e);
                                var r = new Error("probe error");
                                r.transport = p.name, f.emit("upgradeError", r)
                            }
                    }))
                }

                function r() {
                    d || (d = !0, u(), p.close(), p = null)
                }

                function o(t) {
                    var n = new Error("probe error: " + t);
                    n.transport = p.name, r(), s('probe transport "%s" failed because of error: %s', e, t), f.emit("upgradeError", n)
                }

                function i() {
                    o("transport closed")
                }

                function a() {
                    o("socket closed")
                }

                function c(e) {
                    p && e.name != p.name && (s('"%s" works - aborting "%s"', e.name, p.name), r())
                }

                function u() {
                    p.removeListener("open", t), p.removeListener("error", o), p.removeListener("close", i), f.removeListener("close", a), f.removeListener("upgrading", c)
                }
                s('probing transport "%s"', e);
                var p = this.createTransport(e, {
                        probe: 1
                    }),
                    d = !1,
                    f = this;
                n.priorWebsocketSuccess = !1, p.once("open", t), p.once("error", o), p.once("close", i), this.once("close", a), this.once("upgrading", c), p.open()
            }, n.prototype.onOpen = function() {
                if (s("socket open"), this.readyState = "open", n.priorWebsocketSuccess = "websocket" == this.transport.name, this.emit("open"), this.flush(), "open" == this.readyState && this.upgrade && this.transport.pause) {
                    s("starting upgrade probes");
                    for (var e = 0, t = this.upgrades.length; t > e; e++) this.probe(this.upgrades[e])
                }
            }, n.prototype.onPacket = function(e) {
                if ("opening" == this.readyState || "open" == this.readyState) switch (s('socket receive: type "%s", data "%s"', e.type, e.data), this.emit("packet", e), this.emit("heartbeat"), e.type) {
                    case "open":
                        this.onHandshake(d(e.data));
                        break;
                    case "pong":
                        this.setPing();
                        break;
                    case "error":
                        var t = new Error("server error");
                        t.code = e.data, this.emit("error", t);
                        break;
                    case "message":
                        this.emit("data", e.data), this.emit("message", e.data)
                } else s('packet received with socket readyState "%s"', this.readyState)
            }, n.prototype.onHandshake = function(e) {
                this.emit("handshake", e), this.id = e.sid, this.transport.query.sid = e.sid, this.upgrades = this.filterUpgrades(e.upgrades), this.pingInterval = e.pingInterval, this.pingTimeout = e.pingTimeout, this.onOpen(), "closed" != this.readyState && (this.setPing(), this.removeListener("heartbeat", this.onHeartbeat), this.on("heartbeat", this.onHeartbeat))
            }, n.prototype.onHeartbeat = function(e) {
                clearTimeout(this.pingTimeoutTimer);
                var t = this;
                t.pingTimeoutTimer = setTimeout(function() {
                    "closed" != t.readyState && t.onClose("ping timeout")
                }, e || t.pingInterval + t.pingTimeout)
            }, n.prototype.setPing = function() {
                var e = this;
                clearTimeout(e.pingIntervalTimer), e.pingIntervalTimer = setTimeout(function() {
                    s("writing ping packet - expecting pong within %sms", e.pingTimeout), e.ping(), e.onHeartbeat(e.pingTimeout)
                }, e.pingInterval)
            }, n.prototype.ping = function() {
                this.sendPacket("ping")
            }, n.prototype.onDrain = function() {
                for (var e = 0; e < this.prevBufferLen; e++) this.callbackBuffer[e] && this.callbackBuffer[e]();
                this.writeBuffer.splice(0, this.prevBufferLen), this.callbackBuffer.splice(0, this.prevBufferLen), this.prevBufferLen = 0, 0 == this.writeBuffer.length ? this.emit("drain") : this.flush()
            }, n.prototype.flush = function() {
                "closed" != this.readyState && this.transport.writable && !this.upgrading && this.writeBuffer.length && (s("flushing %d packets in socket", this.writeBuffer.length), this.transport.send(this.writeBuffer), this.prevBufferLen = this.writeBuffer.length, this.emit("flush"))
            }, n.prototype.write = n.prototype.send = function(e, t) {
                return this.sendPacket("message", e, t), this
            }, n.prototype.sendPacket = function(e, t, n) {
                if ("closing" != this.readyState && "closed" != this.readyState) {
                    var r = {
                        type: e,
                        data: t
                    };
                    this.emit("packetCreate", r), this.writeBuffer.push(r), this.callbackBuffer.push(n), this.flush()
                }
            }, n.prototype.close = function() {
                function e() {
                    r.onClose("forced close"), s("socket closing - telling transport to close"), r.transport.close()
                }

                function t() {
                    r.removeListener("upgrade", t), r.removeListener("upgradeError", t), e()
                }

                function n() {
                    r.once("upgrade", t), r.once("upgradeError", t)
                }
                if ("opening" == this.readyState || "open" == this.readyState) {
                    this.readyState = "closing";
                    var r = this;
                    this.writeBuffer.length ? this.once("drain", function() {
                        this.upgrading ? n() : e()
                    }) : this.upgrading ? n() : e()
                }
                return this
            }, n.prototype.onError = function(e) {
                s("socket error %j", e), n.priorWebsocketSuccess = !1, this.emit("error", e), this.onClose("transport error", e)
            }, n.prototype.onClose = function(e, t) {
                if ("opening" == this.readyState || "open" == this.readyState || "closing" == this.readyState) {
                    s('socket close with reason: "%s"', e);
                    var n = this;
                    clearTimeout(this.pingIntervalTimer), clearTimeout(this.pingTimeoutTimer), setTimeout(function() {
                        n.writeBuffer = [], n.callbackBuffer = [], n.prevBufferLen = 0
                    }, 0), this.transport.removeAllListeners("close"), this.transport.close(), this.transport.removeAllListeners(), this.readyState = "closed", this.id = null, this.emit("close", e, t)
                }
            }, n.prototype.filterUpgrades = function(e) {
                for (var t = [], n = 0, r = e.length; r > n; n++) ~c(this.transports, e[n]) && t.push(e[n]);
                return t
            }
        }, {
            "./transport": 85,
            "./transports": 77,
            "component-emitter": 49,
            debug: 86,
            "engine.io-parser": 72,
            indexof: 53,
            parsejson: 83,
            parseqs: 84,
            parseuri: 82
        }],
        87: [function(e, t) {
            function n(e) {
                return !!e && "object" == typeof e
            }

            function r(e, t) {
                var n = null == e ? void 0 : e[t];
                return a(n) ? n : void 0
            }

            function o(e) {
                return i(e) && f.call(e) == s
            }

            function i(e) {
                var t = typeof e;
                return !!e && ("object" == t || "function" == t)
            }

            function a(e) {
                return null == e ? !1 : o(e) ? l.test(p.call(e)) : n(e) && c.test(e)
            }
            var s = "[object Function]",
                c = /^\[object .+?Constructor\]$/,
                u = Object.prototype,
                p = Function.prototype.toString,
                d = u.hasOwnProperty,
                f = u.toString,
                l = RegExp("^" + p.call(d).replace(/[\\^$.*+?()[\]{}|]/g, "\\$&").replace(/hasOwnProperty|(function).*?(?=\\\()| for .+?(?=\\\])/g, "$1.*?") + "$");
            t.exports = r
        }, {}],
        88: [function(e, t) {
            function n(e) {
                return !!e && "object" == typeof e
            }

            function r(e) {
                return function(t) {
                    return null == t ? void 0 : t[e]
                }
            }

            function o(e) {
                return null != e && i(d(e))
            }

            function i(e) {
                return "number" == typeof e && e > -1 && 0 == e % 1 && p >= e
            }

            function a(e) {
                return n(e) && o(e) && c.call(e, "callee") && !u.call(e, "callee")
            }
            var s = Object.prototype,
                c = s.hasOwnProperty,
                u = s.propertyIsEnumerable,
                p = 9007199254740991,
                d = r("length");
            t.exports = a
        }, {}],
        86: [function(e, t, n) {
            function r() {
                return "WebkitAppearance" in document.documentElement.style || window.console && (console.firebug || console.exception && console.table) || navigator.userAgent.toLowerCase().match(/firefox\/(\d+)/) && parseInt(RegExp.$1, 10) >= 31
            }

            function o() {
                var e = arguments,
                    t = this.useColors;
                if (e[0] = (t ? "%c" : "") + this.namespace + (t ? " %c" : " ") + e[0] + (t ? "%c " : " ") + "+" + n.humanize(this.diff), !t) return e;
                var r = "color: " + this.color;
                e = [e[0], r, "color: inherit"].concat(Array.prototype.slice.call(e, 1));
                var o = 0,
                    i = 0;
                return e[0].replace(/%[a-z%]/g, function(e) {
                    "%%" !== e && (o++, "%c" === e && (i = o))
                }), e.splice(i, 0, r), e
            }

            function i() {
                return "object" == typeof console && "function" == typeof console.log && Function.prototype.apply.call(console.log, console, arguments)
            }

            function a(e) {
                try {
                    null == e ? localStorage.removeItem("debug") : localStorage.debug = e
                } catch (t) {}
            }

            function s() {
                var e;
                try {
                    e = localStorage.debug
                } catch (t) {}
                return e
            }
            n = t.exports = e("./debug"), n.log = i, n.formatArgs = o, n.save = a, n.load = s, n.useColors = r, n.colors = ["lightseagreen", "forestgreen", "goldenrod", "dodgerblue", "darkorchid", "crimson"], n.formatters.j = function(e) {
                return JSON.stringify(e)
            }, n.enable(s())
        }, {
            "./debug": 89
        }],
        74: [function(e, t) {
            function n(e) {
                return r(e) ? e : Object(e)
            }

            function r(e) {
                var t = typeof e;
                return !!e && ("object" == t || "function" == t)
            }

            function o(e) {
                e = n(e);
                for (var t = -1, r = i(e), o = r.length, a = Array(o); ++t < o;) {
                    var s = r[t];
                    a[t] = [s, e[s]]
                }
                return a
            }
            var i = e("lodash.keys");
            t.exports = o
        }, {
            "lodash.keys": 70
        }],
        73: [function(e, t) {
            function n(e) {
                return !!e && "object" == typeof e
            }

            function r(e, t) {
                for (var n = -1, r = e.length; ++n < r;)
                    if (t(e[n], n, e)) return !0;
                return !1
            }

            function o(e, t, r, a, s, c) {
                return e === t ? !0 : null == e || null == t || !u(e) && !n(t) ? e !== e && t !== t : i(e, t, o, r, a, s, c)
            }

            function i(e, t, n, r, o, i, u) {
                var f = p(e),
                    g = p(t),
                    m = h,
                    v = h;
                f || (m = x.call(e), m == l ? m = b : m != b && (f = d(e))), g || (v = x.call(t), v == l ? v = b : v != b && (g = d(t)));
                var y = m == b,
                    w = v == b,
                    S = m == v;
                if (S && !f && !y) return s(e, t, m);
                if (!o) {
                    var k = y && C.call(e, "__wrapped__"),
                        E = w && C.call(t, "__wrapped__");
                    if (k || E) return n(k ? e.value() : e, E ? t.value() : t, r, o, i, u)
                }
                if (!S) return !1;
                i || (i = []), u || (u = []);
                for (var j = i.length; j--;)
                    if (i[j] == e) return u[j] == t;
                i.push(e), u.push(t);
                var O = (f ? a : c)(e, t, n, r, o, i, u);
                return i.pop(), u.pop(), O
            }

            function a(e, t, n, o, i, a, s) {
                var c = -1,
                    u = e.length,
                    p = t.length;
                if (u != p && !(i && p > u)) return !1;
                for (; ++c < u;) {
                    var d = e[c],
                        f = t[c],
                        l = o ? o(i ? f : d, i ? d : f, c) : void 0;
                    if (void 0 !== l) {
                        if (l) continue;
                        return !1
                    }
                    if (i) {
                        if (!r(t, function(e) {
                                return d === e || n(d, e, o, i, a, s)
                            })) return !1
                    } else if (d !== f && !n(d, f, o, i, a, s)) return !1
                }
                return !0
            }

            function s(e, t, n) {
                switch (n) {
                    case g:
                    case m:
                        return +e == +t;
                    case v:
                        return e.name == t.name && e.message == t.message;
                    case y:
                        return e != +e ? t != +t : e == +t;
                    case w:
                    case S:
                        return e == t + ""
                }
                return !1
            }

            function c(e, t, n, r, o, i, a) {
                var s = f(e),
                    c = s.length,
                    u = f(t),
                    p = u.length;
                if (c != p && !o) return !1;
                for (var d = c; d--;) {
                    var l = s[d];
                    if (!(o ? l in t : C.call(t, l))) return !1
                }
                for (var h = o; ++d < c;) {
                    l = s[d];
                    var g = e[l],
                        m = t[l],
                        v = r ? r(o ? m : g, o ? g : m, l) : void 0;
                    if (!(void 0 === v ? n(g, m, r, o, i, a) : v)) return !1;
                    h || (h = "constructor" == l)
                }
                if (!h) {
                    var y = e.constructor,
                        b = t.constructor;
                    if (y != b && "constructor" in e && "constructor" in t && !("function" == typeof y && y instanceof y && "function" == typeof b && b instanceof b)) return !1
                }
                return !0
            }

            function u(e) {
                var t = typeof e;
                return !!e && ("object" == t || "function" == t)
            }
            var p = e("lodash.isarray"),
                d = e("lodash.istypedarray"),
                f = e("lodash.keys"),
                l = "[object Arguments]",
                h = "[object Array]",
                g = "[object Boolean]",
                m = "[object Date]",
                v = "[object Error]",
                y = "[object Number]",
                b = "[object Object]",
                w = "[object RegExp]",
                S = "[object String]",
                k = Object.prototype,
                C = k.hasOwnProperty,
                x = k.toString;
            t.exports = o
        }, {
            "lodash.isarray": 44,
            "lodash.istypedarray": 75,
            "lodash.keys": 70
        }],
        90: [function(t, n, r) {
            var o = self;
            ! function(t) {
                function i(e) {
                    for (var t, n, r = [], o = 0, i = e.length; i > o;) t = e.charCodeAt(o++), t >= 55296 && 56319 >= t && i > o ? (n = e.charCodeAt(o++), 56320 == (64512 & n) ? r.push(((1023 & t) << 10) + (1023 & n) + 65536) : (r.push(t), o--)) : r.push(t);
                    return r
                }

                function a(e) {
                    for (var t, n = e.length, r = -1, o = ""; ++r < n;) t = e[r], t > 65535 && (t -= 65536, o += w(55296 | 1023 & t >>> 10), t = 56320 | 1023 & t), o += w(t);
                    return o
                }

                function s(e) {
                    if (e >= 55296 && 57343 >= e) throw Error("Lone surrogate U+" + e.toString(16).toUpperCase() + " is not a scalar value")
                }

                function c(e, t) {
                    return w(128 | 63 & e >> t)
                }

                function u(e) {
                    if (0 == (4294967168 & e)) return w(e);
                    var t = "";
                    return 0 == (4294965248 & e) ? t = w(192 | 31 & e >> 6) : 0 == (4294901760 & e) ? (s(e), t = w(224 | 15 & e >> 12), t += c(e, 6)) : 0 == (4292870144 & e) && (t = w(240 | 7 & e >> 18), t += c(e, 12), t += c(e, 6)), t += w(128 | 63 & e)
                }

                function p(e) {
                    for (var t, n = i(e), r = n.length, o = -1, a = ""; ++o < r;) t = n[o], a += u(t);
                    return a
                }

                function d() {
                    if (b >= y) throw Error("Invalid byte index");
                    var e = 255 & v[b];
                    if (b++, 128 == (192 & e)) return 63 & e;
                    throw Error("Invalid continuation byte")
                }

                function f() {
                    var e, t, n, r, o;
                    if (b > y) throw Error("Invalid byte index");
                    if (b == y) return !1;
                    if (e = 255 & v[b], b++, 0 == (128 & e)) return e;
                    if (192 == (224 & e)) {
                        var t = d();
                        if (o = (31 & e) << 6 | t, o >= 128) return o;
                        throw Error("Invalid continuation byte")
                    }
                    if (224 == (240 & e)) {
                        if (t = d(), n = d(), o = (15 & e) << 12 | t << 6 | n, o >= 2048) return s(o), o;
                        throw Error("Invalid continuation byte")
                    }
                    if (240 == (248 & e) && (t = d(), n = d(), r = d(), o = (15 & e) << 18 | t << 12 | n << 6 | r, o >= 65536 && 1114111 >= o)) return o;
                    throw Error("Invalid UTF-8 detected")
                }

                function l(e) {
                    v = i(e), y = v.length, b = 0;
                    for (var t, n = [];
                        (t = f()) !== !1;) n.push(t);
                    return a(n)
                }
                var h = "object" == typeof r && r,
                    g = "object" == typeof n && n && n.exports == h && n,
                    m = "object" == typeof o && o;
                (m.global === m || m.window === m) && (t = m);
                var v, y, b, w = String.fromCharCode,
                    S = {
                        version: "2.0.0",
                        encode: p,
                        decode: l
                    };
                if ("function" == typeof e && "object" == typeof e.amd && e.amd) e(function() {
                    return S
                });
                else if (h && !h.nodeType)
                    if (g) g.exports = S;
                    else {
                        var k = {},
                            C = k.hasOwnProperty;
                        for (var x in S) C.call(S, x) && (h[x] = S[x])
                    } else t.utf8 = S
            }(this)
        }, {}],
        70: [function(e, t) {
            function n(e) {
                return function(t) {
                    return null == t ? void 0 : t[e]
                }
            }

            function r(e) {
                return null != e && i(v(e))
            }

            function o(e, t) {
                return e = "number" == typeof e || f.test(e) ? +e : -1, t = null == t ? m : t, e > -1 && 0 == e % 1 && t > e
            }

            function i(e) {
                return "number" == typeof e && e > -1 && 0 == e % 1 && m >= e
            }

            function a(e) {
                for (var t = c(e), n = t.length, r = n && e.length, a = !!r && i(r) && (d(e) || p(e)), s = -1, u = []; ++s < n;) {
                    var f = t[s];
                    (a && o(f, r) || h.call(e, f)) && u.push(f)
                }
                return u
            }

            function s(e) {
                var t = typeof e;
                return !!e && ("object" == t || "function" == t)
            }

            function c(e) {
                if (null == e) return [];
                s(e) || (e = Object(e));
                var t = e.length;
                t = t && i(t) && (d(e) || p(e)) && t || 0;
                for (var n = e.constructor, r = -1, a = "function" == typeof n && n.prototype === e, c = Array(t), u = t > 0; ++r < t;) c[r] = r + "";
                for (var f in e) u && o(f, t) || "constructor" == f && (a || !h.call(e, f)) || c.push(f);
                return c
            }
            var u = e("lodash._getnative"),
                p = e("lodash.isarguments"),
                d = e("lodash.isarray"),
                f = /^\d+$/,
                l = Object.prototype,
                h = l.hasOwnProperty,
                g = u(Object, "keys"),
                m = 9007199254740991,
                v = n("length"),
                y = g ? function(e) {
                    var t = null == e ? void 0 : e.constructor;
                    return "function" == typeof t && t.prototype === e || "function" != typeof e && r(e) ? a(e) : s(e) ? g(e) : []
                } : a;
            t.exports = y
        }, {
            "lodash._getnative": 87,
            "lodash.isarguments": 88,
            "lodash.isarray": 44
        }],
        91: [function(e, t) {
            t.exports = function(e, t, n) {
                var r = e.byteLength;
                if (t = t || 0, n = n || r, e.slice) return e.slice(t, n);
                if (0 > t && (t += r), 0 > n && (n += r), n > r && (n = r), t >= r || t >= n || 0 === r) return new ArrayBuffer(0);
                for (var o = new Uint8Array(e), i = new Uint8Array(n - t), a = t, s = 0; n > a; a++, s++) i[s] = o[a];
                return i.buffer
            }
        }, {}],
        92: [function(e, t, n) {
            ! function(e) {
                "use strict";
                n.encode = function(t) {
                    var n, r = new Uint8Array(t),
                        o = r.length,
                        i = "";
                    for (n = 0; o > n; n += 3) i += e[r[n] >> 2], i += e[(3 & r[n]) << 4 | r[n + 1] >> 4], i += e[(15 & r[n + 1]) << 2 | r[n + 2] >> 6], i += e[63 & r[n + 2]];
                    return 2 === o % 3 ? i = i.substring(0, i.length - 1) + "=" : 1 === o % 3 && (i = i.substring(0, i.length - 2) + "=="), i
                }, n.decode = function(t) {
                    var n, r, o, i, a, s = .75 * t.length,
                        c = t.length,
                        u = 0;
                    "=" === t[t.length - 1] && (s--, "=" === t[t.length - 2] && s--);
                    var p = new ArrayBuffer(s),
                        d = new Uint8Array(p);
                    for (n = 0; c > n; n += 4) r = e.indexOf(t[n]), o = e.indexOf(t[n + 1]), i = e.indexOf(t[n + 2]), a = e.indexOf(t[n + 3]), d[u++] = r << 2 | o >> 4, d[u++] = (15 & o) << 4 | i >> 2, d[u++] = (3 & i) << 6 | 63 & a;
                    return p
                }
            }("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/")
        }, {}],
        93: [function(e, t) {
            function n(e, t, n) {
                function o(e, r) {
                    if (o.count <= 0) throw new Error("after called too many times");
                    --o.count, e ? (i = !0, t(e), t = n) : 0 !== o.count || i || t(null, r)
                }
                var i = !1;
                return n = n || r, o.count = e, 0 === e ? t() : o
            }

            function r() {}
            t.exports = n
        }, {}],
        94: [function(e, t) {
            function n(e) {
                for (var t = 0; t < e.length; t++) {
                    var n = e[t];
                    if (n.buffer instanceof ArrayBuffer) {
                        var r = n.buffer;
                        if (n.byteLength !== r.byteLength) {
                            var o = new Uint8Array(n.byteLength);
                            o.set(new Uint8Array(r, n.byteOffset, n.byteLength)), r = o.buffer
                        }
                        e[t] = r
                    }
                }
            }

            function r(e, t) {
                t = t || {};
                var r = new a;
                n(e);
                for (var o = 0; o < e.length; o++) r.append(e[o]);
                return t.type ? r.getBlob(t.type) : r.getBlob()
            }

            function o(e, t) {
                return n(e), new Blob(e, t || {})
            }
            var i = self,
                a = i.BlobBuilder || i.WebKitBlobBuilder || i.MSBlobBuilder || i.MozBlobBuilder,
                s = function() {
                    try {
                        var e = new Blob(["hi"]);
                        return 2 === e.size
                    } catch (t) {
                        return !1
                    }
                }(),
                c = s && function() {
                    try {
                        var e = new Blob([new Uint8Array([1, 2])]);
                        return 2 === e.size
                    } catch (t) {
                        return !1
                    }
                }(),
                u = a && a.prototype.append && a.prototype.getBlob;
            t.exports = function() {
                return s ? c ? i.Blob : o : u ? r : void 0
            }()
        }, {}],
        85: [function(e, t) {
            function n(e) {
                this.path = e.path, this.hostname = e.hostname, this.port = e.port, this.secure = e.secure, this.query = e.query, this.timestampParam = e.timestampParam, this.timestampRequests = e.timestampRequests, this.readyState = "", this.agent = e.agent || !1, this.socket = e.socket, this.enablesXDR = e.enablesXDR, this.pfx = e.pfx, this.key = e.key, this.passphrase = e.passphrase, this.cert = e.cert, this.ca = e.ca, this.ciphers = e.ciphers, this.rejectUnauthorized = e.rejectUnauthorized
            }
            var r = e("engine.io-parser"),
                o = e("component-emitter");
            t.exports = n, o(n.prototype), n.timestamps = 0, n.prototype.onError = function(e, t) {
                var n = new Error(e);
                return n.type = "TransportError", n.description = t, this.emit("error", n), this
            }, n.prototype.open = function() {
                return ("closed" == this.readyState || "" == this.readyState) && (this.readyState = "opening", this.doOpen()), this
            }, n.prototype.close = function() {
                return ("opening" == this.readyState || "open" == this.readyState) && (this.doClose(), this.onClose()), this
            }, n.prototype.send = function(e) {
                if ("open" != this.readyState) throw new Error("Transport not open");
                this.write(e)
            }, n.prototype.onOpen = function() {
                this.readyState = "open", this.writable = !0, this.emit("open")
            }, n.prototype.onData = function(e) {
                var t = r.decodePacket(e, this.socket.binaryType);
                this.onPacket(t)
            }, n.prototype.onPacket = function(e) {
                this.emit("packet", e)
            }, n.prototype.onClose = function() {
                this.readyState = "closed", this.emit("close")
            }
        }, {
            "component-emitter": 49,
            "engine.io-parser": 72
        }],
        72: [function(e, t, n) {
            function r(e, t) {
                var r = "b" + n.packets[e.type] + e.data.data;
                return t(r)
            }

            function o(e, t, r) {
                if (!t) return n.encodeBase64Packet(e, r);
                var o = e.data,
                    i = new Uint8Array(o),
                    a = new Uint8Array(1 + o.byteLength);
                a[0] = y[e.type];
                for (var s = 0; s < i.length; s++) a[s + 1] = i[s];
                return r(a.buffer)
            }

            function i(e, t, r) {
                if (!t) return n.encodeBase64Packet(e, r);
                var o = new FileReader;
                return o.onload = function() {
                    e.data = o.result, n.encodePacket(e, t, !0, r)
                }, o.readAsArrayBuffer(e.data)
            }

            function a(e, t, r) {
                if (!t) return n.encodeBase64Packet(e, r);
                if (v) return i(e, t, r);
                var o = new Uint8Array(1);
                o[0] = y[e.type];
                var a = new S([o.buffer, e.data]);
                return r(a)
            }

            function s(e, t, n) {
                for (var r = new Array(e.length), o = l(e.length, n), i = function(e, n, o) {
                        t(n, function(t, n) {
                            r[e] = n, o(t, r)
                        })
                    }, a = 0; a < e.length; a++) i(a, e[a], o)
            }
            var c = self,
                u = e("./keys"),
                p = e("has-binary"),
                d = e("arraybuffer.slice"),
                f = e("base64-arraybuffer"),
                l = e("after"),
                h = e("utf8"),
                g = navigator.userAgent.match(/Android/i),
                m = /PhantomJS/i.test(navigator.userAgent),
                v = g || m;
            n.protocol = 3;
            var y = n.packets = {
                    open: 0,
                    close: 1,
                    ping: 2,
                    pong: 3,
                    message: 4,
                    upgrade: 5,
                    noop: 6
                },
                b = u(y),
                w = {
                    type: "error",
                    data: "parser error"
                },
                S = e("blob");
            n.encodePacket = function(e, t, n, i) {
                "function" == typeof t && (i = t, t = !1), "function" == typeof n && (i = n, n = null);
                var s = void 0 === e.data ? void 0 : e.data.buffer || e.data;
                if (c.ArrayBuffer && s instanceof ArrayBuffer) return o(e, t, i);
                if (S && s instanceof c.Blob) return a(e, t, i);
                if (s && s.base64) return r(e, i);
                var u = y[e.type];
                return void 0 !== e.data && (u += n ? h.encode(String(e.data)) : String(e.data)), i("" + u)
            }, n.encodeBase64Packet = function(e, t) {
                var r = "b" + n.packets[e.type];
                if (S && e.data instanceof S) {
                    var o = new FileReader;
                    return o.onload = function() {
                        var e = o.result.split(",")[1];
                        t(r + e)
                    }, o.readAsDataURL(e.data)
                }
                var i;
                try {
                    i = String.fromCharCode.apply(null, new Uint8Array(e.data))
                } catch (a) {
                    for (var s = new Uint8Array(e.data), u = new Array(s.length), p = 0; p < s.length; p++) u[p] = s[p];
                    i = String.fromCharCode.apply(null, u)
                }
                return r += c.btoa(i), t(r)
            }, n.decodePacket = function(e, t, r) {
                if ("string" == typeof e || void 0 === e) {
                    if ("b" == e.charAt(0)) return n.decodeBase64Packet(e.substr(1), t);
                    if (r) try {
                        e = h.decode(e)
                    } catch (o) {
                        return w
                    }
                    var i = e.charAt(0);
                    return Number(i) == i && b[i] ? e.length > 1 ? {
                        type: b[i],
                        data: e.substring(1)
                    } : {
                        type: b[i]
                    } : w
                }
                var a = new Uint8Array(e),
                    i = a[0],
                    s = d(e, 1);
                return S && "blob" === t && (s = new S([s])), {
                    type: b[i],
                    data: s
                }
            }, n.decodeBase64Packet = function(e, t) {
                var n = b[e.charAt(0)];
                if (!c.ArrayBuffer) return {
                    type: n,
                    data: {
                        base64: !0,
                        data: e.substr(1)
                    }
                };
                var r = f.decode(e.substr(1));
                return "blob" === t && S && (r = new S([r])), {
                    type: n,
                    data: r
                }
            }, n.encodePayload = function(e, t, r) {
                function o(e) {
                    return e.length + ":" + e
                }

                function i(e, r) {
                    n.encodePacket(e, a ? t : !1, !0, function(e) {
                        r(null, o(e))
                    })
                }
                "function" == typeof t && (r = t, t = null);
                var a = p(e);
                return t && a ? S && !v ? n.encodePayloadAsBlob(e, r) : n.encodePayloadAsArrayBuffer(e, r) : e.length ? (s(e, i, function(e, t) {
                    return r(t.join(""))
                }), void 0) : r("0:")
            }, n.decodePayload = function(e, t, r) {
                if ("string" != typeof e) return n.decodePayloadAsBinary(e, t, r);
                "function" == typeof t && (r = t, t = null);
                var o;
                if ("" == e) return r(w, 0, 1);
                for (var i, a, s = "", c = 0, u = e.length; u > c; c++) {
                    var p = e.charAt(c);
                    if (":" != p) s += p;
                    else {
                        if ("" == s || s != (i = Number(s))) return r(w, 0, 1);
                        if (a = e.substr(c + 1, i), s != a.length) return r(w, 0, 1);
                        if (a.length) {
                            if (o = n.decodePacket(a, t, !0), w.type == o.type && w.data == o.data) return r(w, 0, 1);
                            var d = r(o, c + i, u);
                            if (!1 === d) return
                        }
                        c += i, s = ""
                    }
                }
                return "" != s ? r(w, 0, 1) : void 0
            }, n.encodePayloadAsArrayBuffer = function(e, t) {
                function r(e, t) {
                    n.encodePacket(e, !0, !0, function(e) {
                        return t(null, e)
                    })
                }
                return e.length ? (s(e, r, function(e, n) {
                    var r = n.reduce(function(e, t) {
                            var n;
                            return n = "string" == typeof t ? t.length : t.byteLength, e + n.toString().length + n + 2
                        }, 0),
                        o = new Uint8Array(r),
                        i = 0;
                    return n.forEach(function(e) {
                        var t = "string" == typeof e,
                            n = e;
                        if (t) {
                            for (var r = new Uint8Array(e.length), a = 0; a < e.length; a++) r[a] = e.charCodeAt(a);
                            n = r.buffer
                        }
                        o[i++] = t ? 0 : 1;
                        for (var s = n.byteLength.toString(), a = 0; a < s.length; a++) o[i++] = parseInt(s[a]);
                        o[i++] = 255;
                        for (var r = new Uint8Array(n), a = 0; a < r.length; a++) o[i++] = r[a]
                    }), t(o.buffer)
                }), void 0) : t(new ArrayBuffer(0))
            }, n.encodePayloadAsBlob = function(e, t) {
                function r(e, t) {
                    n.encodePacket(e, !0, !0, function(e) {
                        var n = new Uint8Array(1);
                        if (n[0] = 1, "string" == typeof e) {
                            for (var r = new Uint8Array(e.length), o = 0; o < e.length; o++) r[o] = e.charCodeAt(o);
                            e = r.buffer, n[0] = 0
                        }
                        for (var i = e instanceof ArrayBuffer ? e.byteLength : e.size, a = i.toString(), s = new Uint8Array(a.length + 1), o = 0; o < a.length; o++) s[o] = parseInt(a[o]);
                        if (s[a.length] = 255, S) {
                            var c = new S([n.buffer, s.buffer, e]);
                            t(null, c)
                        }
                    })
                }
                s(e, r, function(e, n) {
                    return t(new S(n))
                })
            }, n.decodePayloadAsBinary = function(e, t, r) {
                "function" == typeof t && (r = t, t = null);
                for (var o = e, i = [], a = !1; o.byteLength > 0;) {
                    for (var s = new Uint8Array(o), c = 0 === s[0], u = "", p = 1; 255 != s[p]; p++) {
                        if (u.length > 310) {
                            a = !0;
                            break
                        }
                        u += s[p]
                    }
                    if (a) return r(w, 0, 1);
                    o = d(o, 2 + u.length), u = parseInt(u);
                    var f = d(o, 0, u);
                    if (c) try {
                        f = String.fromCharCode.apply(null, new Uint8Array(f))
                    } catch (l) {
                        var h = new Uint8Array(f);
                        f = "";
                        for (var p = 0; p < h.length; p++) f += String.fromCharCode(h[p])
                    }
                    i.push(f), o = d(o, u)
                }
                var g = i.length;
                i.forEach(function(e, o) {
                    r(n.decodePacket(e, t, !0), o, g)
                })
            }
        }, {
            "./keys": 76,
            after: 93,
            "arraybuffer.slice": 91,
            "base64-arraybuffer": 92,
            blob: 94,
            "has-binary": 56,
            utf8: 90
        }],
        78: [function(e, t) {
            var n = e("has-cors");
            t.exports = function(e) {
                var t = e.xdomain,
                    r = e.xscheme,
                    o = e.enablesXDR;
                try {
                    if ("undefined" != typeof XMLHttpRequest && (!t || n)) return new XMLHttpRequest
                } catch (i) {}
                try {
                    if ("undefined" != typeof XDomainRequest && !r && o) return new XDomainRequest
                } catch (i) {}
                if (!t) try {
                    return new ActiveXObject("Microsoft.XMLHTTP")
                } catch (i) {}
            }
        }, {
            "has-cors": 95
        }],
        89: [function(e, t, n) {
            function r() {
                return n.colors[p++ % n.colors.length]
            }

            function o(e) {
                function t() {}

                function o() {
                    var e = o,
                        t = +new Date,
                        i = t - (u || t);
                    e.diff = i, e.prev = u, e.curr = t, u = t, null == e.useColors && (e.useColors = n.useColors()), null == e.color && e.useColors && (e.color = r());
                    var a = Array.prototype.slice.call(arguments);
                    a[0] = n.coerce(a[0]), "string" != typeof a[0] && (a = ["%o"].concat(a));
                    var s = 0;
                    a[0] = a[0].replace(/%([a-z%])/g, function(t, r) {
                        if ("%%" === t) return t;
                        s++;
                        var o = n.formatters[r];
                        if ("function" == typeof o) {
                            var i = a[s];
                            t = o.call(e, i), a.splice(s, 1), s--
                        }
                        return t
                    }), "function" == typeof n.formatArgs && (a = n.formatArgs.apply(e, a));
                    var c = o.log || n.log || console.log.bind(console);
                    c.apply(e, a)
                }
                t.enabled = !1, o.enabled = !0;
                var i = n.enabled(e) ? o : t;
                return i.namespace = e, i
            }

            function i(e) {
                n.save(e);
                for (var t = (e || "").split(/[\s,]+/), r = t.length, o = 0; r > o; o++) t[o] && (e = t[o].replace(/\*/g, ".*?"), "-" === e[0] ? n.skips.push(new RegExp("^" + e.substr(1) + "$")) : n.names.push(new RegExp("^" + e + "$")))
            }

            function a() {
                n.enable("")
            }

            function s(e) {
                var t, r;
                for (t = 0, r = n.skips.length; r > t; t++)
                    if (n.skips[t].test(e)) return !1;
                for (t = 0, r = n.names.length; r > t; t++)
                    if (n.names[t].test(e)) return !0;
                return !1
            }

            function c(e) {
                return e instanceof Error ? e.stack || e.message : e
            }
            n = t.exports = o, n.coerce = c, n.disable = a, n.enable = i, n.enabled = s, n.humanize = e("ms"), n.names = [], n.skips = [], n.formatters = {};
            var u, p = 0
        }, {
            ms: 96
        }],
        96: [function(e, t) {
            function n(e) {
                var t = /^((?:\d+)?\.?\d+) *(ms|seconds?|s|minutes?|m|hours?|h|days?|d|years?|y)?$/i.exec(e);
                if (t) {
                    var n = parseFloat(t[1]),
                        r = (t[2] || "ms").toLowerCase();
                    switch (r) {
                        case "years":
                        case "year":
                        case "y":
                            return n * p;
                        case "days":
                        case "day":
                        case "d":
                            return n * u;
                        case "hours":
                        case "hour":
                        case "h":
                            return n * c;
                        case "minutes":
                        case "minute":
                        case "m":
                            return n * s;
                        case "seconds":
                        case "second":
                        case "s":
                            return n * a;
                        case "ms":
                            return n
                    }
                }
            }

            function r(e) {
                return e >= u ? Math.round(e / u) + "d" : e >= c ? Math.round(e / c) + "h" : e >= s ? Math.round(e / s) + "m" : e >= a ? Math.round(e / a) + "s" : e + "ms"
            }

            function o(e) {
                return i(e, u, "day") || i(e, c, "hour") || i(e, s, "minute") || i(e, a, "second") || e + " ms"
            }

            function i(e, t, n) {
                return t > e ? void 0 : 1.5 * t > e ? Math.floor(e / t) + " " + n : Math.ceil(e / t) + " " + n + "s"
            }
            var a = 1e3,
                s = 60 * a,
                c = 60 * s,
                u = 24 * c,
                p = 365.25 * u;
            t.exports = function(e, t) {
                return t = t || {}, "string" == typeof e ? n(e) : t.long ? o(e) : r(e)
            }
        }, {}],
        80: [function(e, t) {
            function n() {}

            function r(e) {
                i.call(this, e), this.query = this.query || {}, s || (o.___eio || (o.___eio = []), s = o.___eio), this.index = s.length;
                var t = this;
                s.push(function(e) {
                    t.onData(e)
                }), this.query.j = this.index, o.document && o.addEventListener && o.addEventListener("beforeunload", function() {
                    t.script && (t.script.onerror = n)
                }, !1)
            }
            var o = self,
                i = e("./polling"),
                a = e("component-inherit");
            t.exports = r;
            var s, c = /\n/g,
                u = /\\n/g;
            a(r, i), r.prototype.supportsBinary = !1, r.prototype.doClose = function() {
                this.script && (this.script.parentNode.removeChild(this.script), this.script = null), this.form && (this.form.parentNode.removeChild(this.form), this.form = null, this.iframe = null), i.prototype.doClose.call(this)
            }, r.prototype.doPoll = function() {
                var e = this,
                    t = document.createElement("script");
                this.script && (this.script.parentNode.removeChild(this.script), this.script = null), t.async = !0, t.src = this.uri(), t.onerror = function(t) {
                    e.onError("jsonp poll error", t)
                };
                var n = document.getElementsByTagName("script")[0];
                n.parentNode.insertBefore(t, n), this.script = t;
                var r = "undefined" != typeof navigator && /gecko/i.test(navigator.userAgent);
                r && setTimeout(function() {
                    var e = document.createElement("iframe");
                    document.body.appendChild(e), document.body.removeChild(e)
                }, 100)
            }, r.prototype.doWrite = function(e, t) {
                function n() {
                    r(), t()
                }

                function r() {
                    if (o.iframe) try {
                        o.form.removeChild(o.iframe)
                    } catch (e) {
                        o.onError("jsonp polling iframe removal error", e)
                    }
                    try {
                        var t = '<iframe src="javascript:0" name="' + o.iframeId + '">';
                        i = document.createElement(t)
                    } catch (e) {
                        i = document.createElement("iframe"), i.name = o.iframeId, i.src = "javascript:0"
                    }
                    i.id = o.iframeId, o.form.appendChild(i), o.iframe = i
                }
                var o = this;
                if (!this.form) {
                    var i, a = document.createElement("form"),
                        s = document.createElement("textarea"),
                        p = this.iframeId = "eio_iframe_" + this.index;
                    a.className = "socketio", a.style.position = "absolute", a.style.top = "-1000px", a.style.left = "-1000px", a.target = p, a.method = "POST", a.setAttribute("accept-charset", "utf-8"), s.name = "d", a.appendChild(s), document.body.appendChild(a), this.form = a, this.area = s
                }
                this.form.action = this.uri(), r(), e = e.replace(u, "\\\n"), this.area.value = e.replace(c, "\\n");
                try {
                    this.form.submit()
                } catch (d) {}
                this.iframe.attachEvent ? this.iframe.onreadystatechange = function() {
                    "complete" == o.iframe.readyState && n()
                } : this.iframe.onload = n
            }
        }, {
            "./polling": 97,
            "component-inherit": 98
        }],
        81: [function(e, t) {
            function n(e) {
                var t = e && e.forceBase64;
                t && (this.supportsBinary = !1), r.call(this, e)
            }
            var r = e("../transport"),
                o = e("engine.io-parser"),
                i = e("parseqs"),
                a = e("component-inherit"),
                s = e("debug")("engine.io-client:websocket"),
                c = e("ws");
            t.exports = n, a(n, r), n.prototype.name = "websocket", n.prototype.supportsBinary = !0, n.prototype.doOpen = function() {
                if (this.check()) {
                    var e = this.uri(),
                        t = void 0,
                        n = {
                            agent: this.agent
                        };
                    n.pfx = this.pfx, n.key = this.key, n.passphrase = this.passphrase, n.cert = this.cert, n.ca = this.ca, n.ciphers = this.ciphers, n.rejectUnauthorized = this.rejectUnauthorized, this.ws = new c(e, t, n), void 0 === this.ws.binaryType && (this.supportsBinary = !1), this.ws.binaryType = "arraybuffer", this.addEventListeners()
                }
            }, n.prototype.addEventListeners = function() {
                var e = this;
                this.ws.onopen = function() {
                    e.onOpen()
                }, this.ws.onclose = function() {
                    e.onClose()
                }, this.ws.onmessage = function(t) {
                    e.onData(t.data)
                }, this.ws.onerror = function(t) {
                    e.onError("websocket error", t)
                }
            }, "undefined" != typeof navigator && /iPad|iPhone|iPod/i.test(navigator.userAgent) && (n.prototype.onData = function(e) {
                var t = this;
                setTimeout(function() {
                    r.prototype.onData.call(t, e)
                }, 0)
            }), n.prototype.write = function(e) {
                function t() {
                    n.writable = !0, n.emit("drain")
                }
                var n = this;
                this.writable = !1;
                for (var r = 0, i = e.length; i > r; r++) o.encodePacket(e[r], this.supportsBinary, function(e) {
                    try {
                        n.ws.send(e)
                    } catch (t) {
                        s("websocket closed before onclose event")
                    }
                });
                setTimeout(t, 0)
            }, n.prototype.onClose = function() {
                r.prototype.onClose.call(this)
            }, n.prototype.doClose = function() {
                "undefined" != typeof this.ws && this.ws.close()
            }, n.prototype.uri = function() {
                var e = this.query || {},
                    t = this.secure ? "wss" : "ws",
                    n = "";
                return this.port && ("wss" == t && 443 != this.port || "ws" == t && 80 != this.port) && (n = ":" + this.port), this.timestampRequests && (e[this.timestampParam] = +new Date), this.supportsBinary || (e.b64 = 1), e = i.encode(e), e.length && (e = "?" + e), t + "://" + this.hostname + n + this.path + e
            }, n.prototype.check = function() {
                return !(!c || "__initialize" in c && this.name === n.prototype.name)
            }
        }, {
            "../transport": 85,
            "component-inherit": 98,
            debug: 86,
            "engine.io-parser": 72,
            parseqs: 84,
            ws: 99
        }],
        99: [function(e, t) {
            function n(e, t) {
                var n;
                return n = t ? new o(e, t) : new o(e)
            }
            var r = function() {
                    return this
                }(),
                o = r.WebSocket || r.MozWebSocket;
            t.exports = o ? n : null, o && (n.prototype = o.prototype)
        }, {}],
        98: [function(e, t) {
            t.exports = function(e, t) {
                var n = function() {};
                n.prototype = t.prototype, e.prototype = new n, e.prototype.constructor = e
            }
        }, {}],
        79: [function(e, t) {
            function n() {}

            function r(e) {
                if (c.call(this, e), a.location) {
                    var t = "https:" == location.protocol,
                        n = location.port;
                    n || (n = t ? 443 : 80), this.xd = e.hostname != a.location.hostname || n != e.port, this.xs = e.secure != t
                }
            }

            function o(e) {
                this.method = e.method || "GET", this.uri = e.uri, this.xd = !!e.xd, this.xs = !!e.xs, this.async = !1 !== e.async, this.data = void 0 != e.data ? e.data : null, this.agent = e.agent, this.isBinary = e.isBinary, this.supportsBinary = e.supportsBinary, this.enablesXDR = e.enablesXDR, this.pfx = e.pfx, this.key = e.key, this.passphrase = e.passphrase, this.cert = e.cert, this.ca = e.ca, this.ciphers = e.ciphers, this.rejectUnauthorized = e.rejectUnauthorized, this.create()
            }

            function i() {
                for (var e in o.requests) o.requests.hasOwnProperty(e) && o.requests[e].abort()
            }
            var a = self,
                s = e("xmlhttprequest"),
                c = e("./polling"),
                u = e("component-emitter"),
                p = e("component-inherit"),
                d = e("debug")("engine.io-client:polling-xhr");
            t.exports = r, t.exports.Request = o, p(r, c), r.prototype.supportsBinary = !0, r.prototype.request = function(e) {
                return e = e || {}, e.uri = this.uri(), e.xd = this.xd, e.xs = this.xs, e.agent = this.agent || !1, e.supportsBinary = this.supportsBinary, e.enablesXDR = this.enablesXDR, e.pfx = this.pfx, e.key = this.key, e.passphrase = this.passphrase, e.cert = this.cert, e.ca = this.ca, e.ciphers = this.ciphers, e.rejectUnauthorized = this.rejectUnauthorized, new o(e)
            }, r.prototype.doWrite = function(e, t) {
                var n = "string" != typeof e && void 0 !== e,
                    r = this.request({
                        method: "POST",
                        data: e,
                        isBinary: n
                    }),
                    o = this;
                r.on("success", t), r.on("error", function(e) {
                    o.onError("xhr post error", e)
                }), this.sendXhr = r
            }, r.prototype.doPoll = function() {
                d("xhr poll");
                var e = this.request(),
                    t = this;
                e.on("data", function(e) {
                    t.onData(e)
                }), e.on("error", function(e) {
                    t.onError("xhr poll error", e)
                }), this.pollXhr = e
            }, u(o.prototype), o.prototype.create = function() {
                var e = {
                    agent: this.agent,
                    xdomain: this.xd,
                    xscheme: this.xs,
                    enablesXDR: this.enablesXDR
                };
                e.pfx = this.pfx, e.key = this.key, e.passphrase = this.passphrase, e.cert = this.cert, e.ca = this.ca, e.ciphers = this.ciphers, e.rejectUnauthorized = this.rejectUnauthorized;
                var t = this.xhr = new s(e),
                    n = this;
                try {
                    if (d("xhr open %s: %s", this.method, this.uri), t.open(this.method, this.uri, this.async), this.supportsBinary && (t.responseType = "arraybuffer"), "POST" == this.method) try {
                        this.isBinary ? t.setRequestHeader("Content-type", "application/octet-stream") : t.setRequestHeader("Content-type", "text/plain;charset=UTF-8")
                    } catch (r) {}
                    "withCredentials" in t && (t.withCredentials = !0), this.hasXDR() ? (t.onload = function() {
                        n.onLoad()
                    }, t.onerror = function() {
                        n.onError(t.responseText)
                    }) : t.onreadystatechange = function() {
                        4 == t.readyState && (200 == t.status || 1223 == t.status ? n.onLoad() : setTimeout(function() {
                            n.onError(t.status)
                        }, 0))
                    }, d("xhr data %s", this.data), t.send(this.data)
                } catch (r) {
                    return setTimeout(function() {
                        n.onError(r)
                    }, 0), void 0
                }
                a.document && (this.index = o.requestsCount++, o.requests[this.index] = this)
            }, o.prototype.onSuccess = function() {
                this.emit("success"), this.cleanup()
            }, o.prototype.onData = function(e) {
                this.emit("data", e), this.onSuccess()
            }, o.prototype.onError = function(e) {
                this.emit("error", e), this.cleanup(!0)
            }, o.prototype.cleanup = function(e) {
                if ("undefined" != typeof this.xhr && null !== this.xhr) {
                    if (this.hasXDR() ? this.xhr.onload = this.xhr.onerror = n : this.xhr.onreadystatechange = n, e) try {
                        this.xhr.abort()
                    } catch (t) {}
                    a.document && delete o.requests[this.index], this.xhr = null
                }
            }, o.prototype.onLoad = function() {
                var e;
                try {
                    var t;
                    try {
                        t = this.xhr.getResponseHeader("Content-Type").split(";")[0]
                    } catch (n) {}
                    e = "application/octet-stream" === t ? this.xhr.response : this.supportsBinary ? "ok" : this.xhr.responseText
                } catch (n) {
                    this.onError(n)
                }
                null != e && this.onData(e)
            }, o.prototype.hasXDR = function() {
                return "undefined" != typeof a.XDomainRequest && !this.xs && this.enablesXDR
            }, o.prototype.abort = function() {
                this.cleanup()
            }, a.document && (o.requestsCount = 0, o.requests = {}, a.attachEvent ? a.attachEvent("onunload", i) : a.addEventListener && a.addEventListener("beforeunload", i, !1))
        }, {
            "./polling": 97,
            "component-emitter": 49,
            "component-inherit": 98,
            debug: 86,
            xmlhttprequest: 78
        }],
        95: [function(e, t) {
            var n = e("global");
            try {
                t.exports = "XMLHttpRequest" in n && "withCredentials" in new n.XMLHttpRequest
            } catch (r) {
                t.exports = !1
            }
        }, {
            global: 100
        }],
        100: [function(e, t) {
            t.exports = function() {
                return this
            }()
        }, {}],
        97: [function(e, t) {
            function n(e) {
                var t = e && e.forceBase64;
                (!c || t) && (this.supportsBinary = !1), r.call(this, e)
            }
            var r = e("../transport"),
                o = e("parseqs"),
                i = e("engine.io-parser"),
                a = e("component-inherit"),
                s = e("debug")("engine.io-client:polling");
            t.exports = n;
            var c = function() {
                var t = e("xmlhttprequest"),
                    n = new t({
                        xdomain: !1
                    });
                return null != n.responseType
            }();
            a(n, r), n.prototype.name = "polling", n.prototype.doOpen = function() {
                this.poll()
            }, n.prototype.pause = function(e) {
                function t() {
                    s("paused"), n.readyState = "paused", e()
                }
                var n = this;
                if (this.readyState = "pausing", this.polling || !this.writable) {
                    var r = 0;
                    this.polling && (s("we are currently polling - waiting to pause"), r++, this.once("pollComplete", function() {
                        s("pre-pause polling complete"), --r || t()
                    })), this.writable || (s("we are currently writing - waiting to pause"), r++, this.once("drain", function() {
                        s("pre-pause writing complete"), --r || t()
                    }))
                } else t()
            }, n.prototype.poll = function() {
                s("polling"), this.polling = !0, this.doPoll(), this.emit("poll")
            }, n.prototype.onData = function(e) {
                var t = this;
                s("polling got data %s", e);
                var n = function(e) {
                    return "opening" == t.readyState && t.onOpen(), "close" == e.type ? (t.onClose(), !1) : (t.onPacket(e), void 0)
                };
                i.decodePayload(e, this.socket.binaryType, n), "closed" != this.readyState && (this.polling = !1, this.emit("pollComplete"), "open" == this.readyState ? this.poll() : s('ignoring poll - transport state "%s"', this.readyState))
            }, n.prototype.doClose = function() {
                function e() {
                    s("writing close packet"), t.write([{
                        type: "close"
                    }])
                }
                var t = this;
                "open" == this.readyState ? (s("transport open - closing"), e()) : (s("transport not open - deferring close"), this.once("open", e))
            }, n.prototype.write = function(e) {
                var t = this;
                this.writable = !1;
                var n = function() {
                        t.writable = !0, t.emit("drain")
                    },
                    t = this;
                i.encodePayload(e, this.supportsBinary, function(e) {
                    t.doWrite(e, n)
                })
            }, n.prototype.uri = function() {
                var e = this.query || {},
                    t = this.secure ? "https" : "http",
                    n = "";
                return !1 !== this.timestampRequests && (e[this.timestampParam] = +new Date + "-" + r.timestamps++), this.supportsBinary || e.sid || (e.b64 = 1), e = o.encode(e), this.port && ("https" == t && 443 != this.port || "http" == t && 80 != this.port) && (n = ":" + this.port), e.length && (e = "?" + e), t + "://" + this.hostname + n + this.path + e
            }
        }, {
            "../transport": 85,
            "component-inherit": 98,
            debug: 86,
            "engine.io-parser": 72,
            parseqs: 84,
            xmlhttprequest: 78
        }]
    }, {}, [1])(1)
});

// grab the room from the URL
	var vroom = location.search && location.search.split('?')[1];
	var room=vroom.split('|')[0];
	var to=vroom.split('|')[1];
	if (!room) room=location.search && location.search.split('?')[1];
	var mobile=getCookie('mobile')
	host = 'ws://199.91.65.94:1338/?login='+mobile+'&mobile='+mobile;
	try {
		socket = new WebSocket(host);
		socket.onopen = function(msg) {
			socket.send("new_msg|"+to+"|"+getCookie('long_code')+"|"+"Hello! I am here - turn on your camera so we can chat!"+"|")
		}
	} catch (e) {
		console.info(e);
	}

// create our webrtc connection
var webrtc = new SimpleWebRTC({
	// the id/element dom element that will hold "our" video
	localVideoEl: 'localVideo',
	// the id/element dom element that will hold remote videos
	remoteVideosEl: 'remoteVideo',
	// immediately ask for camera access
	autoRequestMedia: true,
	debug: false,
	detectSpeakingEvents: true,
	autoAdjustMic: false
});

// when it's ready, join if we got a room from the URL
webrtc.on('readyToCall', function () {
	// you can name it anything
	if (room) webrtc.joinRoom(room);
});

function showVolume(el, volume) {
	if (!el) return;
	if (volume < -45) volume = -45; // -45 to -20 is
	if (volume > -20) volume = -20; // a good range
	el.value = volume;
}

// we got access to the camera
webrtc.on('localStream', function (stream) {
	var button = document.querySelector('form>button');
	if (button) button.removeAttribute('disabled');
	$('#localVolume').show();
});
// we did not get access to the camera
webrtc.on('localMediaError', function (err) {
});


// a peer video has been added
webrtc.on('videoAdded', function (video, peer) {
	console.log('video added', peer);
	var remotes = document.getElementById('remotes');
	if (remotes) {
		var container = document.createElement('div');
		container.className = 'videoContainer';
		container.id = 'container_' + webrtc.getDomId(peer);
		container.appendChild(video);
		video.style.marginTop='0px'
		//video.videoWidth=300
		//video.videoHeight=300
		console.log($('#localScreenContainer'))
		// suppress contextmenu
		video.oncontextmenu = function () { return false; };

		container.style.width = video.videoWidth + 'px';
		container.style.height = video.videoHeight + 'px';
	
		var localVideo = document.getElementById('localVideo').style;
		var remoteVideo = container.style;
		if (localVideo.height > localVideo.width) {
			localVideo.transform='scaleX(1)'
		} else {
			localVideo.transform='scaleX(1)'
		}
		remotes.style.width='100%'
		container.style.width='100%'
		localVideo.width='100%'
		video.style.width='100%'
		video.style.position='absolute'
		video.style.zIndex='999999999999999999999999'
		if (video.videoHeight > video.videoWidth) {
			video.style.transform='scaleY(1)'
		} else {
			video.style.transform='scaleX(1)'
		}
		// show the remote volume
		var vol = document.createElement('meter');
		vol.id = 'volume_' + peer.id;
		vol.className = 'volume';
		vol.min = -45;
		vol.max = -20;
		vol.low = -40;
		vol.high = -25;
		container.appendChild(vol);

		// show the ice connection state
		if (peer && peer.pc) {
			var connstate = document.createElement('div');
			connstate.className = 'connectionstate';
			container.appendChild(connstate);
			peer.pc.on('iceConnectionStateChange', function (event) {
				switch (peer.pc.iceConnectionState) {
				case 'checking':
					connstate.innerText = 'Connecting to peer...';
					break;
				case 'connected':
				case 'completed': // on caller side
					$(vol).show();
				 //   connstate.innerText = 'Connection established.';
					break;
				case 'disconnected':
				//    connstate.innerText = 'Disconnected.';
					break;
				case 'failed':
				//    connstate.innerText = 'Connection failed.';
					break;
				case 'closed':
				//    connstate.innerText = 'Connection closed.';
					break;
				}
			});
		}
		document.documentElement.appendChild(container);
	}
});
// a peer was removed
webrtc.on('videoRemoved', function (video, peer) {
	console.log('video removed ', peer);
	var remotes = document.getElementById('remotes');
	var el = document.getElementById(peer ? 'container_' + webrtc.getDomId(peer) : 'localScreenContainer');
	if (remotes && el) {
		remotes.removeChild(el);
	}
});

// local volume has changed
webrtc.on('volumeChange', function (volume, treshold) {
	showVolume(document.getElementById('localVolume'), volume);
});
// remote volume has changed
webrtc.on('remoteVolumeChange', function (peer, volume) {
	showVolume(document.getElementById('volume_' + peer.id), volume);
});
if (room) {
 //   setRoom(room);
} else {
		webrtc.createRoom(room, function (err, name) {
			console.log(' create room cb', arguments);

			var newUrl = location.pathname + '?' + name;
			if (!err) {
				history.replaceState({foo: 'bar'}, null, newUrl);
			} else {
				console.log(err);
			}
		});
}


		function delCookie(cname) {
			var d = new Date();
			d.setTime(d.getTime());
			var expires = "expires="+d.toGMTString();
			document.cookie = cname + "=" + "" + "; " + expires;
		 }

		function setCookie(cname,cvalue,exdays)	{
			var d = new Date();
			d.setTime(d.getTime()+(exdays*24*60*60*1000));
			var expires = "expires="+d.toGMTString();
			document.cookie = cname + "=" + cvalue + "; " + expires;
		 }

		function getCookie(cname)	{
			var name = cname + "=";
			var ca = document.cookie.split(';');
			for(var i=0; i<ca.length; i++) {
			  var c = ca[i].trim();
			  if (c.indexOf(name)==0) return c.substring(name.length,c.length);
			}
			return "";
		}