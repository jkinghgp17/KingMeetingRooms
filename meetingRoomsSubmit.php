<?php
session_start();
include 'functions_2.php';
//password_protect();
include 'includeInc_2.php';
dohtml_header("Schedule Meeting Rooms");
echo "<table class ='centered'><tr><td>";
homeLogout();
echo "</td></tr></table>";

$db = get_database_connection();

$query2 = $db->prepare("SELECT * FROM meetingroom WHERE date=:date and room=:room");
$query2->bindValue(":date", $_POST['date']);
$query2->bindValue(":room", $_POST['room']);
$query2->execute();

$check = $query2->fetchAll();

foreach ($check as $c) {
	if (($_POST['starttime'] <= $c['endtime'] && $_POST['starttime'] >= $c['starttime'])
		||($_POST['endtime'] <= $c['endtime'] && $_POST['endtime'] >= $c['starttime'])
		||($_POST['starttime'] <= $c['starttime'] && $_POST['endtime'] >= $c['endtime'])) {
		echo "<p style=color:#000000 align=center>Entered Date overlaps with date already entered</p>";
		exit();
	}
}

if ($_POST['date'] == NULL) {
	echo "No date entered";
} else if ($_POST['number'] == 0) {
	echo "No number entered";
} else if ($_POST['starttime'] == NULL || $_POST['starttime'] == "00:00:00") {
	echo "No start time entered";
} else if ($_POST['endtime'] == NULL || $_POST['endtime'] == "00:00:00") {
	echo "No end time entered";
} else {
	$query = $db->prepare("INSERT INTO meetingroom(teacher, number, date, room, starttime, endtime) VALUES(:teacher, :number, :date, :room, :starttime, :endtime)");
	$query->bindValue(':teacher', /*$_SESSION['name']*/ "admin");
	$query->bindValue(':date', $_POST['date']);
	$query->bindValue(':number', $_POST['number']);
	$query->bindValue(':room', $_POST['room']);
	$query->bindValue(':starttime', $_POST['starttime']);
	$query->bindValue(':endtime', $_POST['endtime']);

	$query->execute();
	
	echo "Success";	
}

echo "<br/>";
$db = null;
dohtml_footer(true);
?>