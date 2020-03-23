class chatApp extends IWebSocketClientApplication {
    state = 0;
    userName = "";
    state="offLine";
    constructor(){
        super();
        var conn = WebSocketServerConnection.get();
        this.state="offLine";
        conn.registerApplication(this);
        conn.connect();
    }
    static instance = new chatApp();
    login() {
        WebSocketServerConnection.get().send("L"+this.userName);
    }
    sendMessage(Message){
        WebSocketServerConnection.get().send("M"+Message);
    }
    sendState(State){
        WebSocketServerConnection.get().send("S"+State);
    }
    promptUserName(){
        while(this.userName==""){
            this.userName = prompt("Entrez votre pseudo :", "");
        }
    }
    onStart(){
        console.log("onStart");
        this.promptUserName();
    }
    onEnd(){
        this.state="offLine";
        //this.sendState("offLine - onEnd");
    }

    onRestart(){
        this.sendState("onLine");
    }
    onSleep(){
        if (this.state=="offLine") { return; }
        console.log("onSleep");
        this.sendState("Idle - onSleep");
    }

    onOpen(e){
        console.log("onOpen");
        this.login();
    }
    onMessage(e){
        var msg = e.data;
        if (this.state=="offLine"){
            if (msg.substr(0,3)=="NOK"){
                console.log(msg);
                this.userName = "";
                this.promptUserName();
                this.login();
                return;
            } else {
                this.state="onLine";
            }
        }
        console.log(msg);
    }
    onError(e){
        console.log("onError");
    }
    onClose(e){
        console.log("La connection avec le serveur a été fermée.");
    }
}