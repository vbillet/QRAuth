class IWebSocketClientApplication extends IApplication {
    constructor(){
        super();
        this.defineInterface("onOpen",this.onOpen);
        this.defineInterface("onMessage",this.onMessage);
        this.defineInterface("onError",this.onError);
        this.defineInterface("onClose",this.onClose);
        this.defineInterface("isWebSocketClientApplication",this.isWebSocketClientApplication);
        this.validateInterface();
    }
    isWebSocketClientApplication() { return true;}
}