const EState = {
    ST_NONE : 0,
    ST_ENTERING : 1,
    ST_EXECUTING : 2,
    ST_EXITING : 3
}
class StateManager {
    constructor(){
        if (StateManager.get()!=undefined) throw "Singleton are unique.";
        this.currentState = undefined;
        this.currentStateStep = EState.ST_NONE;
    }
    static get(){ return this.instance; }
    static instance = new StateManager();
    isValidState(state){
        try {
            var st = state.get();
            if ((!st.isValid()) || (!st.isState()))
            {
                console.error("Error : Invalid state");
                return false;
            }
        } catch (error) {
            console.error("Error : Invalid state");
            return false;
        }
        return true;
    }
    static setNextState(state){
        var sm = StateManager.get();
        if (!sm.isValidState(state)) return;
        if (sm.currentState!=undefined) 
        {
            if (sm.currentStateStep != EState.ST_EXECUTING)
            { 
                GUI.Error("You must execute the current state before exiting it.");
                return;
            }
            sm.currentStateStep = EState.ST_EXITING;
            sm.currentState.onExitState();
        }
        sm.currentState = state.get();
        sm.currentStateStep = EState.ST_ENTERING;
        sm.currentState.onEnterState();
    }
    static getState() { return StateManager.get().currentState; }
    static executeState() { 
        var sm = StateManager.get();
        var st = StateManager.getState();
        if ((sm.currentStateStep != EState.ST_ENTERING) && (sm.currentStateStep != EState.ST_EXECUTING))
        {
            GUI.Error("You must enter this state before executing it !");
        }
        if (st != undefined)
        {
            sm.currentStateStep = EState.ST_EXECUTING;
            st.onExecuteState();
        } else
        {
            GUI.Error("No state assigned.");
        }
    }
}
