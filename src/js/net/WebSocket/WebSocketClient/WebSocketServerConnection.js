class WebSocketServerConnection {
    constructor(){
        if (WebSocketServerConnection.get()!=undefined) throw "Singletons are unique.";
    }
    static get(){ return this.instance; }
    static instance = new WebSocketServerConnection();
    ws = null;
    app = undefined;
    connect(ip,port){
		if (this.app==undefined) { console.error("You must specify a WebSocketApplication before connection."); return;}
        try{
            this.ws = new WebSocket("ws://"+ip+":"+port);
        } catch (e)
        {
            console.log("Error on WebSocket server connection : "+ip+" on port "+port);
        }
        this.ws.onopen = function(e)    { WebSocketServerConnection.onOpen(e); };
        this.ws.onmessage = function(e) { WebSocketServerConnection.onMessage(e); };
        this.ws.onerror = function(e)   { WebSocketServerConnection.onError(e); };
        this.ws.onclose = function(e)   { WebSocketServerConnection.onClose(e); };
    }
    static getApp() 	{ return WebSocketServerConnection.get().app; }
    static onOpen(e)	{ WebSocketServerConnection.getApp().onOpen(e); }
    static onMessage(e)	{ WebSocketServerConnection.getApp().onMessage(e); }
    static onError(e)	{ WebSocketServerConnection.getApp().onError(e); }
    static onClose(e)	{ WebSocketServerConnection.getApp().onClose(e); }
    send(message) 		{ this.ws.send(message); }
    registerApplication(appli) { 
		if (appli == undefined) { console.error("Undefined application"); return; }
		if (!appli.isWebSocketClientApplication()) { console.error("Application is not a webSocket Application."); return;}
        this.app = appli;
    }
}
