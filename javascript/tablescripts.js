/*
 * Jake King
 * Functions related to the table on the meeting rooms page
 */


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
	alert("The target id is " + id + ".");
}


function selectEnd(event) {

}
