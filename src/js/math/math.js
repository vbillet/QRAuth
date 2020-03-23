class math{
	constructor(){}
	Sin(ss){ return Math.sin(ss);}
	Cos(cc){ return Math.cos(cc);}
	Sqrt(qq){ return Math.sqrt(qq);}
	Atan2(xx,yy) { return Math.atan2(xx,yy);}
	Acos(cc){ return Math.acos(cc);}
	Asin(cc){ return Math.asin(cc);}
	Apow(cc){ return Math.pow(2,cc);}
	PI() {return 3.1415926535897932384626433832795; }
	Rand() { return Math.floor(Math.random()*8191); }
	getTime() { return new Date().getTime(); }
}
var mm=new math();
var epsilon = 0.000001;
var deux = 2;
var cinq = 5;
var demitour = 180;
var unsqrt2 = 1/mm.Sqrt(deux);
var phi = (1+mm.Sqrt(cinq))/2;
var phiM1 = phi-1;
var phi2 = phi*phi;
var tour = 360;
var hexa = 16;
var bit8 = 255;
var bit11=2047;
var pm=5479;
