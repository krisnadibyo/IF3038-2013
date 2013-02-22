

function isiSemua () {
    document.getElementById('infojudul').value = "Tubes Satu";
    document.getElementById('infoassignee').value = "Pandu";
    document.getElementById('infonama').value = "Jenna McDougall - Grow Old With You (Adam Sandler)";
    document.getElementById('infoext').value = "mp4";
    document.getElementById('infotanggal').value = "31";
    document.getElementById('infobulan').value = "12";
    document.getElementById('infotahun').value = "2012";
    //document.getElementById('').value = "";    
}

function editAssignee() {
    var a = document.getElementById('editassignee').value;
    if (a=="edit") {
	document.getElementById('infoassignee').removeAttribute('readonly');
	document.getElementById('infoassignee').focus();
	document.getElementById('editassignee').value="oke";
    } else {
	document.getElementById('infoassignee').setAttribute('readonly');
	document.getElementById('editassignee').value="edit";	
    }
}

function editDate() {
    var a = document.getElementById('editdate').value;
    if (a=="edit") {
	document.getElementById('ntanggal').value = document.getElementById('infotanggal').value;
	document.getElementById('nbulan').value = document.getElementById('infobulan').value;
	document.getElementById('ntahun').value = document.getElementById('infotahun').value;    
	document.getElementById('divinfowrapper').setAttribute('class','disnone');
	document.getElementById('diveditdatewrapper').setAttribute('class','infowrapper');
	document.getElementById('editdate').value="oke";
    } else { // oke
	if(isTanggalValid()) {
	    document.getElementById('divinfowrapper').setAttribute('class','infowrapper');
	    document.getElementById('diveditdatewrapper').setAttribute('class','disnone');
	    document.getElementById('editdate').value="edit";
	} else
	    alert('Tanggal tak valid');
    }
    validateTanggal();
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
function validateTanggal() {
    var warnaasli = '#A3FFA3';
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
function lihatAttachment () {
    document.getElementById('bground').style.display='block';
    document.getElementById('fhkotakijowrapper').style.display='block';
    if (!dapatkanAttachment()) {
	takLihatAttachment();
    }
}
function takLihatAttachment() {
    if (true) {
	document.getElementById('bground').style.display='none';
	document.getElementById('fhkotakijowrapper').style.display='none';
    }
}
function dapatkanAttachment (){
    var a = document.getElementById('infonama').value;
    var b = document.getElementById('infoext').value;
    var c = a + "." + b;
    
    var bool_gmb = ((b=="jpg")||(b=="png")||(b=="gif")||(b=="bmp"));
    var bool_vid = ((b=="mp4")||(b=="3gp")||(b=="avi")||(b=="flv")||(b=="mkv")||(b=="mpg"));
    var bool_doc = ((b=="pdf")||(b=="doc")||(b=="docx"));
    var att;
    
    if (bool_gmb) {
	var att = document.createElement('img');
	att.setAttribute('id','attachment');
	att.setAttribute('src','images/'+c);
	if (att.style.height > att.style.width)
	    att.style.width="100%";
	else
	    att.style.height="100%";
    } else
    if (bool_vid) {
	var att = document.createElement('video');
	att.setAttribute('id','attachment');
	att.setAttribute('controls','');
	att.innerHTML="<source src='videos/"+c+"' type='video/"+b+"'>";
	if (att.style.height > att.style.width)
	    att.style.height="100%";
	else
	    att.style.width="100%";
	
    } else
    if (bool_doc) {
	var att = document.createElement('img');
	att.setAttribute('id','attachment');
	if (att.style.height > att.style.width)
	    att.style.width="100%";
	else
	    att.style.height="100%";
    } else {}
    
    var no = document.createElement('div');
    var ni = document.getElementById('fhkotakijo');
    no.style.position="relative";
    no.style.display="table-cell";
    no.style.verticalAlign="middle";
    no.style.width = ni.offsetWidth;
    no.style.height = ni.offsetHeight;
    no.appendChild(att);
    while (ni.firstChild) {
	ni.removeChild(ni.firstChild);
    }
    ni.setAttribute("align","center");
    ni.appendChild(no);
    return true;
}

function editTag() {
    
    var prefiks = "tagno";
    var a = document.getElementById('edittag').value;
    
    if (a=="edit") {
	var x = new Array();
	var ni = document.getElementById('divtagwrapper');
	var i = 0;
	while (ni.firstChild) {
	    if (ni.firstChild.value!=null) {
		x[i] = ni.firstChild.value;
		i++;
	    }
	    ni.removeChild(ni.firstChild);
	}
	var y = new Array();
	var z = x.join(",");
	document.getElementById('inputedittag').value = z;
	document.getElementById('divtagwrapper').setAttribute('class','disnone');
	document.getElementById('divedittagwrapper').setAttribute('class','infowrapper');
	document.getElementById('edittag').value="oke";
    }
	else // oke
    { 
	var p =document.getElementById('inputedittag').value;
	var q = new Array();
	q = p.split(",");
	
	var ni = document.getElementById('divtagwrapper');
	for (var i=0; i<q.length; i++) {
	    var newdiv = document.createElement('input');
	    var divIdName = 'tagno'+i;    
	    newdiv.setAttribute('id',divIdName);
	    newdiv.setAttribute('class'," iteks bghijau1 twarna3 borderradius");
	    newdiv.setAttribute('type','button');
	    newdiv.setAttribute('value',q[i]);
	    
	    ni.appendChild(newdiv);
	}
	
	document.getElementById('divtagwrapper').setAttribute('class','infowrapper');
	document.getElementById('divedittagwrapper').setAttribute('class','disnone');
	document.getElementById('edittag').value="edit";
    }
}



