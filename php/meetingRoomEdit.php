<?php
/*
 * Jake King April 2017
 * Meeting Room Edit Page EHGP
 * Allows User to edit their sign out
 */
session_start();
include 'functions_2.php';
//teacher_only();
//password_protect("login_2.meetingRooms=1");
$db = get_database_connection();
include 'includeInc_2.php';
dohtml_header("Edit Meeting Room");
echo "<table class='centered'><tr><td>";
homelogout();

if ((isset($_POST['starttime']) && isset($_POST['date']) && isset($_POST['room']) && isset($_POST['endtime']) && isset($_POST['teacher']) && isset($_POST['id']))) {
	$updateQuery = $db->prepare("UPDATE meetingroom SET number=:number, date=:date, room=:room, endtime=:endtime, starttime=:stattime, teacher=:teacher WHERE id=:id");
	$updateQuery->bindValue(":number", $_POST['number']);
	$updateQuery->bindValue(":date", $_POST['date']);
	$updateQuery->bindValue(":room", $_POST['room']);
	$updateQuery->bindValue(":starttime", $_POST['starttime']);
	$updateQuery->bindValue(":endtime", $_POST['endtime']);
	$updateQuery->bindValue(":teacher", $_POST['teacher']);
	$updateQuery->bindValue(":id", $_POST['id']);
	$updateQuery->execute();
}

echo "</td></tr></table>";

if (isset($_POST['id'])) {
    $_POST['id'] = $_POST['id'];
}
?>

<form action="meetingRoomsEdit.php" method="POST" align='center'>
	Select Room <br/>
	<select name="room">
		<option value="Fr_Brown"> Fr. Brown </option>
		<option value="Sager"> Sager </option>
		<option value="Brottier"> Brottier </option>
		<option value="Laval"> Laval </option>
		<option value="Sawicki"> Sawichi </option>
		<option value="Maletz"> Maletz </option>
		<option value="Spir_Dinning"> Spir. Dinning </option>
		<option value="Spir_Conference"> Spir. Conference </option>
		<option value="Library"> Library </option>
	</select> <br/>
	Date: 
	<input type="date" name="date"> <br/>
	Start time: 
	<input type="time" name="starttime" min="07:30:00" max="20:30:00"><br/>
	End time:
	<input type="time" name="endtime" min="08:00:00" max="21:00:00"><br/>
	Number of People:
	<input type="number" name="number" min="1" max="999"> <br/> 
	<input type="submit" value="Submit">
</form>

<?php
	$db = null;
	dohtml_footer(true);
?>
