/*
 * Jake King
 * Functions related to the table on the meeting rooms page
 */

var starttime;
var endtime;
var mainRoom;
var number;

function selectStart(event) {
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
	document.getElementById(id).innerHTML = "start";
 	alert("The start time is" + starttime + " the room is " + mainRoom + ".");
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
	
	document.getElementById(id).innerHTML = "end";
	var localNumber = parseInt(prompt("Please enter number of people", 0), 10);
	alert("The end time is " + endtime + "the number is " + localNumber +   ".");
}
