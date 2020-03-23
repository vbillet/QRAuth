class interfaceDefinition{
    constructor() {
        this.methodNames = [];
        this.methods = [];
        this._isValid = false;
    }
    defineInterface(methName,meth)
    {
        this.methodNames.push(methName);
        this.methods.push(meth);
    }
    dumpInterface(){
        var cnt = this.methodNames.length;
        for (var ii = 0;ii<cnt;ii++){
            console.log(this.methodNames[ii]);
            console.log(this.methods[ii]);
        }
    }
    callInterface(meth)
    {
        var cnt = this.methodNames.length;
        for (var ii = 0;ii<cnt;ii++){
            if(this.methodNames[ii] == meth){
                this.methods[ii]();
            }
        }
    }
    validateInterface(){
        var cnt = this.methods.length;
        for (var ii = 0;ii<cnt;ii++){
            if (!this.haveMethod(this.methods[ii]))
            {
                if (!this.hasOwnProperty(this.methods[ii]))
                {
                    var objectName = this.__proto__.constructor.name;
                    this._isValid = false;
                    GUI.Log(this);
                    throw "Missing interface definition : " + objectName+"."+this.methodNames[ii];
                }
            }
        }
        this._isValid = true;
    }
    haveMethod(meth) { return (typeof(meth)==="function"); }
    isValid() { return this._isValid;}
    queryInterface() { return this.methodNames;}
}
