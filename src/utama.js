

function setVal (x,y,z) {
    var a = (document.getElementById(x).value);
    if (a == y) {
	document.getElementById(x).value = z;
	document.getElementById(x).style.backgroundColor='#FFFFFF';
    } else {

    }
}

function tes(){
	window.alert("Bitch");	
}

function alertUsername(x,y,z,p,q){
	var a = (document.getElementById(x).value);
    if (a.length<5) {
		document.getElementById(x).style.backgroundColor='#F66666';
		document.getElementById(q).disabled=true;				

    } else {
			if (a == document.getElementById(p).value) {
				document.getElementById(x).style.backgroundColor='#F66666';
				document.getElementById(q).disabled=true;				
			}else{
				document.getElementById(x).style.backgroundColor='#FFFFFF';
				document.getElementById(q).disabled=false;				
			}
    }	
	
}

function alertPwd(x,y,z,p,q,g){
	var a = (document.getElementById(x).value);
    if (a.length<8) {
				document.getElementById(x).style.backgroundColor='#F66666';
				document.getElementById(g).disabled=true;				
    } else {
			if ((a == document.getElementById(p).value) || (a == document.getElementById(q).value)){
				document.getElementById(x).style.backgroundColor='#F66666';
				document.getElementById(g).disabled=true;								
			}else{
				document.getElementById(x).style.backgroundColor='#FFFFFF';	
				document.getElementById(g).disabled=false;												
			}
    }		
}

function alertCnfr(x,y,z,q){
	if((document.getElementById(x).value)==(document.getElementById(y).value)){
				document.getElementById(x).style.backgroundColor='#FFFFFF';
				document.getElementById(q).disabled=false;								
	}else{
				document.getElementById(x).style.backgroundColor='#FF6666';
				document.getElementById(q).disabled=true;								
	}
}

function alertName(x,z,q){
	var a = (String(document.getElementById(x).value));
	var b = /[A-Za-z0-9][A-Za-z0-9]* [A-Za-z0-9][A-Za-z0-9]*$/;	
	
	if(b.test(a)){
		document.getElementById(x).style.backgroundColor='#FFFFFF';
				document.getElementById(q).disabled=false;						
				return (true);
	}else{
				document.getElementById(x).style.backgroundColor='#FF6666';
				document.getElementById(q).disabled=true;								
				return (false);
	}
}

function alertEmail(x,y,z,q){
	var a = (String(document.getElementById(x).value));
	var c = (String(document.getElementById(y).value));
	var b = /[A-Za-z0-9][A-Za-z0-9]*@[A-Za-z0-9]+.[A-Za-z0-9][A-Za-z0-9]+$/;	
	if(b.test(a)){
		if(a!=c){
					document.getElementById(x).style.backgroundColor='#FFFFFF';
				document.getElementById(q).disabled=false;									
			return (true);				
		}else{
				document.getElementById(x).style.backgroundColor='#F66666';
				document.getElementById(q).disabled=true;								
		return (false);
		}
	}else{
				document.getElementById(x).style.backgroundColor='#F66666';
				document.getElementById(q).disabled=true;								

				return (false);
	}
	
}

function disable(x){
	if(alertEmail('em','pwd','Masukkan Email') && alertName('nl','Masukkan Nama Lengkap')){
		document.getElementById(x).disabled=true;
	}else{
			document.getElementById(x).disabled=false;
	}
}

function validateTanggalIndex(x) {
    var warnaasli = x;
    var warnasalah = '#FF6666';
    if (!isTanggalValid()) {
	document.getElementById('ntanggal').style.backgroundColor=warnasalah;
	document.getElementById('nbulan').style.backgroundColor=warnasalah;
	document.getElementById('ntahun').style.backgroundColor=warnasalah;
    }
    else {
	document.getElementById('ntanggal').style.backgroundColor=warnaasli;
	document.getElementById('nbulan').style.backgroundColor=warnaasli;
	document.getElementById('ntahun').style.backgroundColor=warnaasli;
    }
    return false;
}

function isTanggalValid () {
    var a = (document.getElementById('ntanggal').value);
    var b = (document.getElementById('nbulan').value);
    var c = (document.getElementById('ntahun').value);
    var z = ''+a+'-'+b+'-'+c;
    var s = z.split('-');
    var d = new Date(+s[2], s[1]-1, +s[0]);
    if (Object.prototype.toString.call(d) === "[object Date]") {
	    if (!isNaN(d.getTime()) && d.getDate() == s[0] && 
		    d.getMonth() == (s[1] - 1)) {
		    return true;
	    } else
		return false;
    } else
	return false;
}
function setInfoTanggalIdx () {
    var a = (document.getElementById('ntanggal').value);
    var b = (document.getElementById('nbulan').value);
    var c = (document.getElementById('ntahun').value);
    document.getElementById('infotanggal').value = a;
    document.getElementById('infobulan').value = b;
    document.getElementById('infotahun').value = c;
    unhiddeninfo();
    return false;
}
