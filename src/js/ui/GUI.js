var TRUE=true;
var FALSE=!TRUE;
var UNDEFINED=undefined;

//OBFSSTRINGTAB
//OBFSNUMBERTAB
//OBFSSTRINGFUN

class GUI{
	constructor() {
		this.listeners = [];
		if (GUI.getInstance()!=UNDEFINED) throw "Singletons are unique.";
	}
	static getInstance(){ return this.instance; }
    static instance = new GUI();
	// Par identifiant de div (général)
	// display = "block" : affiché, "none" : aucun
	static getStyle(elt) { return GUI.get(elt).style; }
	static setDisplay(elt,aff) { GUI.getStyle(elt).display = aff; }
	// Affiche un div par son identifiant
	static show(elt) { GUI.setDisplay(elt,"block");}
	// cache un div par son identifiant
	static hide(elt) { GUI.setDisplay(elt,"none");}
	// true si le div est invisible.
	static isHidden(elt) { return GUI.getStyle(elt).display == "none"; }
	// swith div visibility
	static switch_display(elt) { if(GUI.isHidden(elt)) GUI.show(elt); else GUI.hide(elt); }

	// switch la visibilité d'un id de div modal.
	static switch_modal(id) {var elt = GUI.get(id); if(elt.className.indexOf("w3-show")==-1) elt.className+=" w3-show"; else elt.className = elt.className.replace(" w3-show","");}
	static hide_modal(id) {var elt = GUI.get(id); if(elt.className.indexOf("w3-show")>=0) elt.className = elt.className.replace(" w3-show","");}
	static show_modal(id) {var elt = GUI.get(id); if(elt.className.indexOf("w3-show")==-1) elt.className+=" w3-show";}
	static setTitle(elt,lvl,txt) { GUI.setInner(elt,"<h"+lvl+">"+lib+"</h"+lvl+">"); }
	static setTitle2(elt,lvl,txt1,txt2) { GUI.setInner(elt,"<h"+lvl+">"+txt1+"<span style='float:right;'>"+txt2+"</span></h"+lvl+">"); }
	static removeColor(elt,col) { var elt = GUI.get(elt); if(elt.className.indexOf(col)>-1) elt.className = elt.className.replace(" "+col,"");}
	static addColor(elt,col) { var elt = GUI.get(elt); if(elt.className.indexOf(col)==-1) elt.className += " "+col;}
	static get(elt) { 
		var retelt = document.getElementById(elt); 
		if (retelt==null) { console.log(elt); } 
		return retelt; 
	}
	static getInner(elt) { return GUI.get(elt).innerHTML; }
	static getOuter(elt) { return GUI.get(elt).outerHTML; }
	static setInner(elt,cont) { return GUI.get(elt).innerHTML=cont; }
	static setOuter(elt,cont) { return GUI.get(elt).outerHTML=cont; }
	static getByName(name) { return document.getElementsByName(name); }
	static Log(msg) { console.log(msg);}
	static Error(msg) { console.error(msg);}
	static getValue(elt) { return GUI.get(elt).value; }
	static setValue(elt,valeur) { GUI.get(elt).value=valeur; }
	static isChecked(elt,valeur) { return GUI.get(elt).checked=valeur; }
	static setChecked(elt,valeur) { GUI.get(elt).checked=valeur; }
	static blur(elt) { GUI.get(elt).blur(); }
	static focus(elt) { GUI.get(elt).focus(); }
	static setSrc(elt,url) { GUI.get(elt).src=url; }
	static getChildNodes(elt) { return GUI.get(elt).childNodes; }
	static getByClass(elt) {return document.getElementsByClassName(elt); }
	static addListener(elt,evt,func) { 
		try {
			GUI.get(elt).addEventListener(evt,func); 
		} catch(err){
			GUI.Error("Impossible de trouver l'élément : "+elt);
		}
	}
	static removeListener(elt,evt,func){
		try {
			GUI.get(elt).removeEventListener(evt,func); 
		} catch(err){
			GUI.Error("Impossible de trouver l'élément : "+elt);
		}
	}
	static addClassListener(cls,evt,func){
		var elts = GUI.getByClass(cls);
		var cnti = elts.length;
		for (var ii = 0;ii<cnti;ii++){
			elts[ii].addEventListener(evt,func);
		}
	}
	static removeClassListener(cls,evt,func){
		var elts = GUI.getByClass(cls);
		var cnti = elts.length;
		for (var ii = 0;ii<cnti;ii++){
			elts[ii].removeEventListener(evt,func);
		}
	}
	static getAttrib(elt,pAttr) { return elt.getAttribute(pAttr); }
	static getByClass(name) { return document.getElementsByClassName(name); }
	static setBgColor(elt,col) { GUI.get(elt).style.backgroundColor = col; }
	static getBgColor(elt) { return GUI.get(elt).style.backgroundColor; }
	static Print(elt,url) {
		var frm = GUI.get("frameprint");
		frm.onload=function(){
			GUI.Log("Impression...");
			window.frames["frameprint"].contentDocument.getElementById("printframe").contentWindow.print();
			GUI.switch_modal("impBox");
			window.frames["frameprint"].onload=null;
			//window.stop();
		};
		frm.src = url+"&l=p";
		GUI.switch_modal("impBox");
	}
	static Timer(func,deltaT){window.setTimeout(func,deltaT);}
	static Location(url){document.location=url;}
}
