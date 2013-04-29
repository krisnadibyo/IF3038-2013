

function setVal (x,y,z) {
    var a = (document.getElementById(x).value);
    if (a == y) {
	document.getElementById(x).value = z;
    } else {
	
    }
}
function unhiddeninfo () {
    if (isJudulValid() || isAssigneeValid() || isExtValid() || isNamaFileValid() ||  isTanggalValid()) {
	var a = (document.getElementById('divinfo'));
	a.setAttribute('style','visibility:visible');
    } else {
	var a = (document.getElementById('divinfo'));
	a.setAttribute('style','visibility:hidden');
    }
}
function setInfoJudul () {
    var a = (document.getElementById('nnamatugas').value);
    document.getElementById('infojudul').value = a;
    unhiddeninfo();
    return false;
}
function setInfoAssignee () {
    var a = (document.getElementById('nassignee').value);
    document.getElementById('infoassignee').value = a;
    unhiddeninfo();
    return false;
}
function setInfoFile () {
    var a = (document.getElementById('nfile').value);
    var d = a.lastIndexOf(".");
    var ext = a.substr(d+1,a.length);
    var n = a.lastIndexOf(ext);
    var l = a.lastIndexOf("\\");
    var nama = a.substring(l+1,n-1);
    
    document.getElementById('infonama').value = nama;
    document.getElementById('infoext').value = ext;
    
    unhiddeninfo();
    return false;
}
function setInfoTag () {
    var a = (document.getElementById('ntag').value);
    
    var b = a.split(",");
    //alert(b.length+" "+b);
    
    var ni = document.getElementById('divinfotag');
    while (ni.firstChild) {
	ni.removeChild(ni.firstChild);
    }
    if (a!="") {
	var num = b.length;    
	var i=0;
	for (i=0; i<num; i++) {
	    var newdiv = document.createElement('input');
	    var divIdName = 'tagno'+i;    
	    newdiv.setAttribute('id',divIdName);
	    newdiv.setAttribute('class'," iteks bghijau1 twarna3 borderradius");
	    newdiv.setAttribute('type','button');
	    newdiv.setAttribute('value',b[i]);
	    
	    ni.appendChild(newdiv);
	}
    }
    
    unhiddeninfo();
    return false;
}
function setInfoAssignee () {
    var a = (document.getElementById('nassignee').value);
    document.getElementById('infoassignee').value = a;
    unhiddeninfo();
    return false;
}
function setInfoTanggal () {
    var a = (document.getElementById('ntanggal').value);
    var b = (document.getElementById('nbulan').value);
    var c = (document.getElementById('ntahun').value);
    document.getElementById('infotanggal').value = a;
    document.getElementById('infobulan').value = b;
    document.getElementById('infotahun').value = c;
    unhiddeninfo();
    return false;
}

function isJudulValid () {
    var a = (document.getElementById('nnamatugas').value);
    var bool = ((a.length <=25)&&(a!="")&&(a!="Nama Tugas"));
    if (!bool) {
	return false;
    } else {
	return true;
    }
}
function isAssigneeValid () {
    var a = (document.getElementById('nassignee').value);
    var bool = ((a!="")&&(a!="Assignee"));
    if (!bool) {
	return false;
    } else {
	return true;
    }
}
function isExtValid () {
    var a = (document.getElementById('infoext').value);
    var bool = ((a=="jpg")||(a=="png")||(a=="gif")||(a=="bmp")||(a=="pdf")||(a=="doc")||(a=="docx")||(a=="mp4")||(a=="3gp")||(a=="avi")||(a=="flv")||(a=="mkv"));
    bool = !(!bool&&(a==""));
    if (!bool) {
	return false;
    } else {
	return true;
    }
}
function isNamaFileValid () {
    var a = (document.getElementById('infonama').value);
    var bool = ((a!=""));
    if (!bool) {
	return false;
    } else {
	return true;
    }
}
function isTanggalValid () {
    var a = (document.getElementById('infotanggal').value);
    var b = (document.getElementById('infobulan').value);
    var c = (document.getElementById('infotahun').value);
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
function validateAll() {
    validateJudul("#ffffff");
    validateExt("#ffffff");
    validateAssignee("#ffffff");
    validateNama("#ffffff");
    validateTanggal("#ffffff");
}
function isValidAll() {
    var b = isJudulValid() && isAssigneeValid() && isExtValid() && isNamaFileValid() &&  isTanggalValid();
    if (!b)
	alert('masukkan anda salah');
    return b;
}
function validateJudul(x) {
    var warnaasli = x;
    var warnasalah = '#FF6666';
    if (!isJudulValid())
	document.getElementById('infojudul').style.backgroundColor=warnasalah;
    else
	document.getElementById('infojudul').style.backgroundColor= warnaasli;
    return false;
}
function validateAssignee(x) {
    var warnaasli = x;
    var warnasalah = '#FF6666';
    if (!isAssigneeValid())
	document.getElementById('infoassignee').style.backgroundColor=warnasalah;
    else
	document.getElementById('infoassignee').style.backgroundColor= warnaasli;
    return false;
}
function validateExt(x) {
    var warnaasli = x;
    var warnasalah = '#FF6666';
    if (!isExtValid())
	document.getElementById('infoext').style.backgroundColor=warnasalah;
    else
	document.getElementById('infoext').style.backgroundColor= warnaasli;
    return false;
}
function validateNama(x) {
    var warnaasli = x;
    var warnasalah = '#FF6666';
    if (!isExtValid())
	document.getElementById('infonama').style.backgroundColor=warnasalah;
    else
	document.getElementById('infonama').style.backgroundColor= warnaasli;
    return false;
}
function validateTanggal(x) {
    var warnaasli = x;
    var warnasalah = '#FF6666';
    if (!isTanggalValid()) {
	document.getElementById('infotanggal').style.backgroundColor=warnasalah;
	document.getElementById('infobulan').style.backgroundColor=warnasalah;
	document.getElementById('infotahun').style.backgroundColor=warnasalah;
    }
    else {
	document.getElementById('infotanggal').style.backgroundColor=warnaasli;
	document.getElementById('infobulan').style.backgroundColor=warnaasli;
	document.getElementById('infotahun').style.backgroundColor=warnaasli;
    }
    return false;
}













