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

if ((isset($_POST['starttime']) && isset($_POST['date']) && isset($_POST['room']) && isset($_POST['endtime']) && isset($_POST['teacher']) && isset($_POST['id']) && isset($_POST['number']))) {
	$updateQuery = $db->prepare("UPDATE meetingroom SET number=:number, date=:date, room=:room, endtime=:endtime, starttime=:starttime, teacher=:teacher WHERE id=:id");
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
/*
 * Everyting involving roomnumber is a mess.
 * The roomnumber's only use is to set the default for the select so I wouldn't worry to much
 */
if (isset($_POST['roomnumber']))
	$selected = $_POST['roomnumber'] + 1;
else	
	$selected = -1;
?>

<form action="meetingRoomsEdit.php" method="POST" align='center'>
	Select Room <br/>
	<select name="room">
		<option value="Fr_Brown"<?php if ($selected == 1){echo " selected";}?>> Fr. Brown </option>
		<option value="Sager"<?php if ($selected == 2){echo " selected";}?>> Sager </option>
		<option value="Brottier"<?php if ($selected == 3){echo " selected";}?>> Brottier </option>
		<option value="Laval"<?php if ($selected == 4){echo " selected";}?>> Laval </option>
		<option value="Sawicki"<?php if ($selected == 5){echo " selected";}?>> Sawichi </option>
		<option value="Maletz"<?php if ($selected == 6){echo " selected";}?>> Maletz </option>
		<option value="Spir_Dinning"<?php if ($selected == 7){echo " selected";}?>> Spir. Dinning </option>
		<option value="Spir_Conference"<?php if ($selected == 8){echo " selected";}?>> Spir. Conference </option>
		<option value="Library"<?php if ($selected == 9){echo " selected";}?>> Library </option>
	</select> <br/>
	Date: 
	<input type="date" name="date" value=<?php echo $_POST['date']?>> <br/>
	Start time: 
	<input type="time" name="starttime" min="07:30:00" max="20:30:00" value=<?php echo $_POST['starttime']?>><br/>
	End time:
	<input type="time" name="endtime" min="08:00:00" max="21:00:00" value=<?php echo $_POST['endtime']?>><br/>
	Number of People:
	<input type="number" name="number" min="1" max="999" value=<?php echo $_POST['number']?>> <br/> 
	<input type="submit" value="Submit">
</form>

<?php
	$db = null;
	dohtml_footer(true);
?>
