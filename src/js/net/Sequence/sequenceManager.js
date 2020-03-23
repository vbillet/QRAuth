const ESequenceType = {
    SEQ_UNKNOWN : 0,
    SEQ_JSON : 1,
    SEQ_EVENT : 2,
    SEQ_IMAGE : 3
}
class SequenceManager {
    constructor(){
        if (SequenceManager.get()!=undefined) throw "Singletons are unique.";
        this.Request = new XMLHttpRequest();
        this.currentSequence = UNDEFINED;
        this._response = "";
        this.errCode = 0;
        this.guielt = UNDEFINED;
    }
    static get(){ return this.instance; }
    static instance = new SequenceManager();
    static setGUI(elt) {
        var guielt = GUI.get(elt);
        if (guielt==UNDEFINED) { return; }
        SequenceManager.get().guielt = guielt;
    }
    isSequenceValid(seq) {
        if (seq==UNDEFINED) return FALSE;
        try {
            if (seq.isSequence()) return TRUE;
        } catch (error) {
            return FALSE;
        }
    }
    Send() {
        var seq = this.currentSequence;
        if (seq.dataType == ESequenceType.SEQ_UNKNOWN) { throw "Unknown Sequence Type ! " }
        this.Request.onreadystatechange = function(evt) {
            SequenceManager.get().processResponse(this.readyState,this.status,this.responseText);
        };
        this.setProgressBar(25);
        this.Request.open("POST",seq.url,TRUE);
        this.Request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        if (seq.dataType == ESequenceType.SEQ_JSON) QuantumTeleporter.Teleport("j="+encodeURI(seq.data));
        if (seq.dataType == ESequenceType.SEQ_EVENT) QuantumTeleporter.Teleport(encodeURI(seq.data));
        if (seq.dataType == ESequenceType.SEQ_IMAGE) this.Request.send(seq.data);
    }
    static isRequestSuccessfull(){
        return SequenceManager.get().errCode == 200;
    }
    static getResponse() {
        return SequenceManager.get().response;
    }
    static getErrorCode() {
        return SequenceManager.get().errCode;
    }
    setProgressBar(val) { 
        if (this.guielt == UNDEFINED) { return; }
        this.guielt.style.width = val + "%"; 
    }
    processResponse(ready,state,resp){
        if (ready === 1 ) this.setProgressBar(40);
        if (ready === 2 ) this.setProgressBar(75);
        if (ready === 3 ) this.setProgressBar(90);
        if (ready === 4) {
            this.response = resp;
            if (state === 200) { this.errCode = 200; } else
            if (state === 300) { this.errCode = 300; } else
            if (state === 301) { this.errCode = 301; } else
            if (state === 302) { this.errCode = 302; } else
            if (state === 303) { this.errCode = 303; } else
            if (state === 400) { this.errCode = 400; } else
            if (state === 401) { this.errCode = 401; } else
            if (state === 402) { this.errCode = 402; } else
            if (state === 403) { this.errCode = 403; } else
            if (state === 404) { this.errCode = 404; } else
            if (state === 500) { this.errCode = 500; } else
            if (state === 501) { this.errCode = 501; } else
            if (state === 502) { this.errCode = 502; } else
            if (state === 503) { this.errCode = 503; } else
                this.errCode = 999;
            this.currentSequence = UNDEFINED;
            this.setProgressBar(0);
            StateManager.executeState();
        }
    }
    getRequest() { return this.Request;}
    static DoSequence(seq){
        var sm = SequenceManager.get();
        if (!sm.isSequenceValid(seq)) throw "invalid sequence";
        sm.currentSequence = seq;
        sm.Send();
    }
}