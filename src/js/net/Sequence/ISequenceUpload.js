class ISequenceUpload extends ISequence{
    constructor() { super(); }
    static get(){ return this.instance; }
    setSequenceKind() { this.dataType = ESequenceType.SEQ_IMAGE;}
}
