class IinitState extends IState {
    constructor() { super(); }
    onEnterState() { StateManager.executeState(); }
    onExitState() { }
}
