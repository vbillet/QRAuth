class IState extends interfaceDefinition{
    constructor() { 
        super();
        this.defineInterface("onEnterState",this.onEnterState);
        this.defineInterface("onExitState",this.onExitState);
        this.defineInterface("onExecuteState",this.onExecuteState);
        this.validateInterface();
    }
    static get(){ return this.instance; }
    isState() { return true;}
}
