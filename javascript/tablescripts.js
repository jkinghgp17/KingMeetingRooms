/*
 * Jake King
 * Functions related to the table on the meeting rooms page
 */

var start;
var end;

function selectStart(event) {
	start = event.target;
	alert("Start time selected as " + start + ".");
}

function selectEnd(event) {
	end = event.target;
	alert("End time selected as " + start + ".");
}
