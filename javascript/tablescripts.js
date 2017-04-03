/*
 * Jake King
 * Functions related to the table on the meeting rooms page
 */

var starttime;
var endtime;
var mainRoom;
var number;

function selectStart(event) {
	if (endtime) {
		starttime = null;
		endtime = null;
		mainRoom = null;
		number = null;
	}
	if (starttime) {
		selectEnd(event);
		return;
	}
	var target;
	if (!event) {
		event = window.event;
	}
	if (event.target) {
		target = event.target;
	} else if (event.srcElement) {
		target = event.srcElement;
	}
	var id = target.id;
	var i = id.indexOf(".");
	if (i != -1) {
		var time = id.substr(0, i);
		var room = id.substr(i + 1);
		starttime = time;
		mainRoom = room;
	}
	document.getElementById(id).className = "selected";
 	//alert("The start time is" + starttime + " the room is " + mainRoom + ".");
}

function select(event) {
	try {
		selectStart(event);
	} catch(e) {
		alert(e);
	}
}

/*
 * Is called by selectStart after the user has select a start time
 * launches propmt to ask user for number
 * puts data into form and submits
 */
function selectEnd(event) {
	var target;
	if (!event) {
		event = window.event;
	}
	if (event.target) {
		target = event.target;
	} else if (event.srcElement) {
		target = event.srcElement;
	}
	var id = target.id;
	var i = id.indexOf(".");
	if (i != -1) {
		var time = id.substr(0, i);
		var room = id.substr(i + 1);
		if (mainRoom == room) {
			endtime = time;
		}
	}
	
	document.getElementById(id).className = "selected";
	var localNumber = parseInt(prompt("Please enter number of people", 0), 10);
	alert("The end time is " + endtime + " the number is " + localNumber +   ".");
	addToForm();
}

function addToForm() {
	//if (endtime && startime && room && number) {
		document.getElementById("HIDDEN_ROOM").value = mainRoom;
		document.getElementById("HIDDEN_NUMBER").value = number;
		document.getElementById("HIDDEN_START").value = starttime;
		document.getElementById("HIDDEN_END").value = endtime;
	//}
	var frm = document.getElementById("HIDDEN_FORM");
	frm.submit();
}
