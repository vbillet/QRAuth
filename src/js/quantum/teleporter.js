class QuantumTeleporter {
	constructor(){
		if (QuantumTeleporter.get()!=undefined) throw "Singletons are unique.";
		this.setKey(pm);
	}
	static get(){ return this.instance; }
	static instance = new QuantumTeleporter();
	getCharCode(car){ return car.charCodeAt();}
	setKey(cle){ this.cle = cle; }
	encodeChar(car){
		var cc = this.getCharCode(car);
		var po=1;
		var qubit = new Qubit(0,0,1,0);
		qubit = GH.doOp(qubit);
		for ( var qq = 1;qq<9;qq++){
			//console.log(po+" "+cc);
			if((po & cc)==po){
				//console.log(cc+" "+qq);
				qubit.apply(new Gate(0,0,1,mm.PI()/mm.Apow(8-qq)));
				//qubit.dump(po);
			}
			po=po*2;
		}
		po=1;
		for ( var qq = 1;qq<9;qq++){
			//console.log(po+" "+cc);
			if((po & this.cle)==po){
				//console.log(qq);
				qubit.apply(new Gate(0,1,0,mm.PI()/mm.Apow(8-qq)));
			}
			po=po*2;
		}
		qubit.apply(new Gate(1,0,0,this.cle/10));
		var r1 = parseInt((bit11*((qubit.psi()+demitour)/tour)).toFixed(0)).toString(hexa);
		if (r1.length == 1) { r1="00"+r1;}
		if (r1.length == 2) { r1="0"+r1;}
		var r2 = parseInt((bit8*((qubit.theta())/demitour)).toFixed(0)).toString(hexa);
		if (r2.length == 1) { r2="0"+r2;}
		return r1+r2;
	}
	encodeString(str){
		var cnt = str.length;
		var result = "";
		for(var ii=0;ii<cnt;ii++) {
			result = result + this.encodeChar(str.substr(ii,1));
			this.cle++;
		}
		var cs=0;
		cnt = result.length;
		//console.log(result);
		for(var ii=0;ii<cnt;ii++) {
			//console.log(ii+":"+cs+"+"+this.getCharCode(result.substr(ii,1))+"("+result.substr(ii,1)+")");
			cs = cs+this.getCharCode(result.substr(ii,1));
		}
		//console.log(cs);
		cs = (cs & bit8).toString(hexa);
		//console.log(cs);
		if (cs.length==1) { cs="0"+cs;}
		result=result+cs;
		return result;
	}
	static Teleport(chaine){
		var lt = mm.getTime();
		var r = mm.Rand();
		SequenceManager.get().getRequest().send("q="+QuantumTeleporter.get().encodeString( r + "=rand&" + "lt=" + lt + "&" + chaine));
	}
}
