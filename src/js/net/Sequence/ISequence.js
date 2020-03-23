class ISequence extends interfaceDefinition {
    constructor(){
        super();
        this.defineInterface("setSequenceKind",this.setSequenceKind);
        this.defineInterface("onSetURL",this.onSetURL);
        this.defineInterface("onSend",this.onSend);
        this.validateInterface();
        this.dataType = UNDEFINED;
        this.url = "";
        this.data = "";
        this.setSequenceKind();
        this.onSetURL();
    }
    static get(){ return this.instance; }
    isSequence() { return true;}
    isSequenceEvent() { return this.dataType == ESequenceType.SEQ_EVENT;}
    isSequenceJSON() { return this.dataType == ESequenceType.SEQ_JSON;}
    isSequenceXML() { return this.dataType == ESequenceType.SEQ_XML;}
}