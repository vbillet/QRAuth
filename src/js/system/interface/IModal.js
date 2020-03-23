class IModal extends interfaceDefinition {
    constructor(){
        super();
        this.defineInterface("show",this.show);
        this.defineInterface("hide",this.hide);
        this.defineInterface("initListeners",this.initListeners);
        this.defineInterface("removeListeners",this.removeListeners);
        this.validateInterface();
    }
    get() { return this.instance;}
    isModal() { return TRUE; }
}