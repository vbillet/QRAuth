class Gate {
	constructor(ax,ay,az,angle){
		this.qubit = new Qubit(ax,ay,az,angle);
	}
	doOp(qu){
		var qb = new Qubit();
		var qa = this.qubit.Mul(qu);
		qb = qa.Mul(this.qubit.conjugate());
		//qb.normalize();
		return qb;
	}

}
var GH = new Gate(unsqrt2,0,unsqrt2,mm.PI());
