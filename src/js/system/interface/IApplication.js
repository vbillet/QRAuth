class IApplication extends interfaceDefinition {
    constructor(){
        super();
        this.defineInterface("onStart",this.onStart);
        this.defineInterface("onEnd",this.onEnd);
        this.defineInterface("onRestart",this.onRestart);
        this.defineInterface("onSleep",this.onSleep);
        this.validateInterface();
        window.addEventListener("load" , (event) => { this.onStart(); });
        window.addEventListener("beforeunload" , (event) => { this.onEnd(); });
        document.addEventListener("visibilitychange" , (event) => { if(document.hidden) this.onSleep(); else this.onRestart(); });
    }
    isApplication() { return TRUE;}
}