class ISequenceState extends IState {
    constructor(){
        super();
        this.defineInterface("onOK",this.onOK);
        this.defineInterface("onError",this.onError);
        this.defineInterface("onSetSequence",this.onSetSequence);
        this.validateInterface();
        this.sequence = undefined;
        this.onSetSequence();
    }
    static get(){ return this.instance; }
    isSequence() { return TRUE;}
    setSequence(seq) { this.sequence = seq.get(); }
    onEnterState() { this.sequence.onSend(); SequenceManager.DoSequence(this.sequence); }
    onExitState() { }
    onExecuteState() { 
        if (SequenceManager.isRequestSuccessfull()) {
            this.onOK(SequenceManager.getResponse());
        } else {
            this.onError(SequenceManager.getResponse(),SequenceManager.getErrorCode());
        }
    }
}
