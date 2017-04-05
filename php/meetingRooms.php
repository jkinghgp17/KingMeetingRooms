<?php
/*
 * Jake King April 2017
 * Meeting Room Signout Page EHGP
 * Allows signing out of meeting rooms
 * Editing already signed out rooms
 * Shows what time are taken on any given day
 */ 
session_start();
include 'functions_2.php';
//teacher_only();
//password_protect("login_2.meetingRooms=1");
$db = get_database_connection();
include 'includeInc_2.php';
dohtml_header("Schedule Meeting Rooms");
echo "<table class ='centered'><tr><td>";
homeLogout();
// Was Getting issues in firefox when this was not here
// May or may not still be needed
date_default_timezone_set('America/New_York');

// Set the $date variable
if (isset($_GET['dateUpdate'])) {
	$date=$_GET['dateUpdate'];
} else {
	$date = date('Y/m/d');
}

// Delete or edit 
if (isset($_POST['time']) && isset($_POST['date']) && isset($_POST['room'])) {
	$date = $_POST['date'];
	if ($_POST['number'] == 0) {
		$deleteQuery = $db->prepare("DELETE FROM meetingroom WHERE starttime=:time and date=:date and room=:room");
		$deleteQuery->bindValue(":time", $_POST['time']);
		$deleteQuery->bindValue(":date", $_POST['date']);
		$deleteQuery->bindValue(":room", $_POST['room']);
		$deleteQuery->execute();
	} else {
		$updateQuery = $db->prepare("UPDATE meetingroom SET number=:number WHERE starttime=:time and date=:date and room=:room");
		$updateQuery->bindValue(":time", $_POST['time']);
		$updateQuery->bindValue(":date", $_POST['date']);
		$updateQuery->bindValue(":room", $_POST['room']);
		$updateQuery->bindValue(":number", $_POST['number']);
		$updateQuery->execute();
	}
}
echo "</td></tr></table>";
?>


<link rel="stylesheet" type="text/css" href="css/table.css">
<script type="text/javascript" src="javascript/tablescripts.js"></script>



<!-- Hidden formed used by table -->
<form action="meetingRoomsSubmit.php" id="HIDDEN_FORM" method="POST">
	<input type="hidden" name="number" value="-1" id="HIDDEN_NUMBER">
	<input type="hidden" name="room" value="" id="HIDDEN_ROOM">
	<?php
	/* Never fear this is merely a "temporary workaround" that I will never replace */
	echo "<input type=hidden name=date id=HIDDEN_DATE value=" .  $date . ">"
	?>	
	<input type="hidden" name="starttime" min="07:30:00" max="20:30:00" value="" id="HIDDEN_START">
	<input type="hidden" name="endtime" min="08:00:00" max="21:00:00" value="" id="HIDDEN_END">
	<input type="submit" style="visibility: hidden;">
</form>

<!--
<form action="meetingRoomsSubmit.php" method="POST" align='center'>
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
-->

<!-- This form allows user to change the current date being shown in the table -->
<form action="meetingRooms.php" method="GET" align='center'>
<input name="dateUpdate" type="date" value=<?php $date ?>>
<br/><input type="submit" value="Change Date">
</form>

<div style='overflow:scroll; <!-- height:400px; -->' onmousedown="selectStart(event)">
<table id="TEST"  class='centered meetingRoomTable' height='0' border='0' padding='0'>
		<tr>
			<?php 
	echo "<th>Times</th>";
				$rooms = array("Fr_Brown",
						   "Sager",
						   "Brottier",
						   "Laval",
						   "Sawichi",
						   "Maletz",
						   "Spir_Dinning",
						   "Spir_Conference",
						   "Library");
				for ($i = 0; $i < 9; $i++) {
					echo "<th>";
					echo $rooms[$i];
					echo "</th>";
				}
			?>
		</tr>
	<?php
			define("MAX_HOURS", 14);
			define("MIN_INT", 15);
			define("START_HOUR", 7);
			define("START_MIN", 30);

			$query = $db->prepare('SELECT * FROM meetingroom WHERE date=:date');
			$query->bindValue(':date', $date);
			$query->execute();
			$rows = $query->fetchAll();
			/*
			 * This loop goes though all hours at 30 min intervals 
			 * Sets up table that will be drawn by javascript code at 
			 * a later point
			 */ 
			$hour = START_HOUR;
			$min = START_MIN;
			for($row = 0; $row < MAX_HOURS * (60 / MIN_INT); $row++) {
				echo "<tr>";
				// Sets the $time string 
				if ($min == 0) {
					echo "<td>" . $hour . ":0" . $min . "</td>";
				} else if ($min == 30) {
					echo "<td>" . $hour . ":" . $min . "</td>";
				} else  {
					echo "<td></td>";
				}
				for ($col = 0; $col < 9; $col++) {
					
					$time = "00:00:00";

					if ($hour < 10) {
						if ($min < 10) {
							$time = "0" . $hour . ":" . "0" . $min . ":00";
						} else {
							$time = "0" . $hour . ":" . $min . ":00";
						}
					} else {
						if ($min < 10) {
							$time = $hour . ":" . "0" . $min . ":00";
						} else {
							$time = $hour . ":" . $min . ":00";
						}
					}
					
					$maxTime = date('H:i:s', strtotime($time) + (MIN_INT * 60));
					
					/* For testing only
					if ($time == "17:00:00") {
						echo $time . " " . $maxTime;
					}
					*/
					$class ="default";
					$str = "";
					foreach ($rows as $r) {
						if ($rooms[$col] == $r['room']) {
							if ($time >= $r['starttime'] && $time <= $r['endtime'])
								$class="timetaken";
							// A form is added to the start of every time group to allow for editing
							if ($time <= $r['starttime'] && $maxTime > $r['starttime']) {
								// This line is a bit long (good luck if you are on cli lol)
								$class = 'timetaken';
								$str = $r['teacher'] . " " . $r['number'] . " " . "<br/><form action=meetingRooms.php method=POST><input type=hidden name=room value=" . $rooms[$col] . "><input type=hidden name=date value=" . $date . "><input type=hidden name=time value=" . $time . "><input type=number name=number min=0 max=999><input type=submit></form><br/>
								<form action=meetingRoomEdit.php method=POST><input type=hidden name=roomnumber value=" . $col . "><input type=hidden name=id value=" . $r['id'] . "><input type=hidden name=starttime value=" . $r['starttime'] . "><input type=hidden name=endtime value=" . $r['endtime'] . "><input type=hidden name=date value=" . $r['date'] . "><input type=hidden name=teacher value=" . $r['teacher'] . "><input type=hidden name=number value=" . $r['number'] . "><input type=hidden name=room value=" . $r['room'] . "><input type=submit value=edit></form>";
							}
						}
					}
					echo "<td ' id=" . $time . "." . $rooms[$col] . " class=" . $class . ">" . $str . "</td>";
				}
				$min += MIN_INT;
				if ($min >= 60) {
					$hour++;
					$min = 0;
				}
				echo "</tr>";
			}
		?>
</table>	
</div>
<?php
	$db = null;
	dohtml_footer(true);
?>
