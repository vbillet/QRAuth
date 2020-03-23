class IModalState extends IState {
    constructor(){
        super();
        this.defineInterface("onSetModal",this.onSetModal);
        this.validateInterface();
        this._modal = UNDEFINED;
    }
    setModal(modal) { this._modal = modal.get(); }
    onEnterState() {
        GUI.Log("[App] Entering ModalState : " + this._modal);
        this.onSetModal();
        if (this._modal == UNDEFINED) { throw "You must specificy a Modal"; }
        try {
            this._modal.isModal();
        } catch (error) {
            throw "Invalid interface";
        }
        this._modal.show();
    }
    onExitState() { 
        GUI.Log("[App] Exiting ModalState : " + this._modal);
        this._modal.hide(); 
    }
}