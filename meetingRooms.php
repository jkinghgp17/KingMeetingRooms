<?php
session_start();
include 'functions_2.php';
//teacher_only();
//password_protect("login_2.meetingRooms=1");
$db = get_database_connection();
include 'includeInc_2.php';
dohtml_header("Schedule Meeting Rooms");
echo "<table class ='centered'><tr><td>";
homeLogout();
date_default_timezone_set('America/New_York');

if (isset($_GET['dateUpdate'])) {
	$date=$_GET['dateUpdate'];
} else {
	$date = date('Y/m/d');
}

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

<form action="meetingRooms.php" method="GET" align='center'>
<input name="dateUpdate" type="date" value=<?php $date ?>>
<br/><input type="submit" value="Change Date">
</form>

<div style='overflow:scroll; height:400px;'>
<table class='centered' height='0' border='0' padding='0'>
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
			$query = $db->prepare('SELECT * FROM meetingroom WHERE date=:date');
			$query->bindValue(':date', $date);
			$query->execute();
			$rows = $query->fetchAll();
			$hour = 7;
			$min = 30;
			for($row = 0; $row < 14 * 60; $row++) {
				echo "<tr>";
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
					
					$color = '#FF000000';
					$str = "";
					foreach ($rows as $r) {
						if ($rooms[$col] == $r['room']) {
							if ($time >= $r['starttime'] && $time <= $r['endtime'])
								$color = 'Red';
							if ($time == $r['starttime']) {
								$str = $r['teacher'] . " " . $r['number'] . " " . "<br/><form action=meetingRooms.php method=POST><input type=hidden name=room value=" . $rooms[$col] . "><input type=hidden name=date value=" . $date . "><input type=hidden name=time value=" . $time . "><input type=number name=number min=0 max=999><input type=submit></form>";
							}
						}
					}
					echo "<td style='background-color:" . $color . ";'>" . $str . "</td>";
				}
				$min++;
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