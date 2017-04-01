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
 	alert("The start time is" + starttime + " the room is " + mainRoom + ".");
}

/*
 * Color all the blocks in the table that are between the start and end
 */
function colorBetweenSelect() {
	var f_hour, f_min, e_hour, e_min;
	var i = starttime.indexOf(":");
 	f_hour = starttime.substr(0, i);
	f_min = starttime.substr(i + 1, i + 3);
	i = endtime.indexOf(":");
 	e_hour = starttime.substr(0, i);
	e_min = starttime.substr(i + 1, i + 3);
	var time = f_hour + ":" + f_min + ":00";
	var hour = parseInt(f_hour);
	var min = parseInt(f_min);
	while (endtime > time) {
		
		if ((++m) > 59) {
			hour++;
			min = 0;
		}

	}
}

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
	alert("The end time is " + endtime + "the number is " + localNumber +   ".");
}
