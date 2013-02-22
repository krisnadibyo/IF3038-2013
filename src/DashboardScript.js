


function Tes(a) {
	if (a==1) {
		document.getElementById("TugasOOP").style.display = "inherit";
		document.getElementById("AddTugasOOP").style.display = "inherit";
		document.getElementById("TugasStima").style.display = "none";
		document.getElementById("TugasOS").style.display = "none";
	}
	else if (a==2) {
		document.getElementById("TugasStima").style.display = "inherit";
		document.getElementById("TugasOOP").style.display = "none";
		document.getElementById("TugasOS").style.display = "none";
		document.getElementById("AddTugasStima").style.display = "inherit";
	}
	else if (a == 3) {
		document.getElementById("TugasOS").style.display = "inherit";
		document.getElementById("TugasOOP").style.display = "none";
		document.getElementById("TugasStima").style.display = "none";
		document.getElementById("AddTugasOS").style.display = "inherit";
	}
	
	
}

function nongol(a) {
	if (a==1) {
		document.getElementById("isiKategoribaru").style.display = "inherit";
	}
	else if (a== 0) {
		document.getElementById("isiKategoribaru").style.display = "none";
	}
}