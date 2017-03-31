/*
 * Jake King
 * Functions related to the table on the meeting rooms page
 */

var starttime;
var endtime;
var mainRoom;
var number;
var date;

function selectStart(event) {
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
		if (mainRoom == room && time > starttime) {
			endtime = time;
			date = document.getElementById("dateSelector").value;
		}
	}
}
