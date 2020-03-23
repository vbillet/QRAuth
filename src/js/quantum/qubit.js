class Qubit{
	constructor(ax,ay,az,angle){
		this.setFromAxisAng(ax,ay,az,angle);
		this.normalize();
	}
	set(px,py,pz,pw){
		this.qx=px;
		this.qy=py;
		this.qz=pz;
		this.qw=pw;
	}
	setFromAxisAng(ax,ay,az,angle) {
		var st2 = mm.Sin(angle/deux);
		if (st2==0) { st2=1;}
		this.qx = ax*st2;
		this.qy = ay*st2;
		this.qz = az*st2;
		this.qw = mm.Cos(angle/deux);
	}
	getAxis() {
		var result = new v3();
		var sqw = mm.Sqrt(1-this.qw*this.qw);
		if (this.sqw<epsilon) {
			result.xx = this.qx;
			result.yy = this.qy;
			result.zz = this.qz;
		} else
		{
			result.xx = this.qx/sqw;
			result.yy = this.qy/sqw;
			result.zz = this.qz/sqw;
		}
		result.xx = parseFloat(result.xx.toFixed(7));
		result.yy = parseFloat(result.yy.toFixed(7));
		result.zz = parseFloat(result.zz.toFixed(7));
		return result;
	}
	conjugate() {
		var result = new Qubit();
		result.set( this.qx*-1, this.qy*-1, this.qz*-1, this.qw );
		return result;
	}
	radius() { return mm.Sqrt(this.qw*this.qw+this.qx*this.qx+this.qy*this.qy+this.qz*this.qz); }
	psi()    { var vv=this.getAxis(); return mm.Atan2(vv.yy,-vv.xx)*demitour/mm.PI(); }
	theta()  { var vv=this.getAxis(); return mm.Acos(vv.zz)*demitour/mm.PI(); }
	omega()  { 	
		var rr = new Qubit(0,0,1,0);
		var zz = new Gate(0,0,1,mm.PI());
		rr = zz.doOp(rr);
		if (rr.radius()>epsilon)
		{
			rr.normalize();
			var result = rr.psi() / deux;
			return result;
		} else {
			return 0;
		}
	}
	setFrom(qq) { this.qw=qq.qw; this.qx=qq.qx; this.qy = qq.qy; this.qz = qq.qz;}
	dump(ch) {
		var axe=this.getAxis();
		GUI.Log(ch+" : "+this.qw.toFixed(7)+" - "+axe.xx.toFixed(7)+" "+axe.yy.toFixed(7)+" "+axe.zz.toFixed(7)+" - theta : "+this.theta().toFixed(7)+" psi : "+this.psi().toFixed(7)+" omega : "+this.omega().toFixed(7)+" r : "+this.radius().toFixed(7));
	}
	Mul(q2) {
		var result = new Qubit();
		result.qw = q2.qw*this.qw - q2.qx*this.qx - q2.qy*this.qy - q2.qz*this.qz;
		result.qx = q2.qw*this.qx + q2.qx*this.qw - q2.qy*this.qz + q2.qz*this.qy;
		result.qy = q2.qw*this.qy + q2.qx*this.qz + q2.qy*this.qw - q2.qz*this.qx;
		result.qz = q2.qw*this.qz - q2.qx*this.qy + q2.qy*this.qx + q2.qz*this.qw;
		return result;
	}
	normalize() {
		var dd = this.radius();
		this.set(this.qx/dd, this.qy/dd, this.qz/dd, this.qw/dd);
	}
	apply(gate){
		var qr = gate.doOp(this);
		this.qx = qr.qx;
		this.qy = qr.qy;
		this.qz = qr.qz;
		this.qw = qr.qw;
		return this;
	}
	doGate(gate){
		var qr = gate.doOp(this);
		return qr;
	}
}
