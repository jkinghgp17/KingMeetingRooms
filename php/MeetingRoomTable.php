<!DOCTYPE html>
<?php 
session_start();
?>
<html>
<body>

<form action="MeetingRoomTable.php" method="GET" align='center'>
<input name="dateUpdate" type="date">
<br/><input type="submit" value="Change Date">
</form>
	
<table class='centered' height='0' border='0' padding='0'>
		<tr>
			<?php  
				include 'functions_2.php';
				$db = get_database_connection();
				include 'includeInc_2.php';
				if (isset($_GET['dateUpdate'])) {
					$date=$_GET['dateUpdate'];
				} else {
					$date = date('Y/m/d');
				}
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
			$hour = 8;
			$min = 0;
			for($row = 0; $row < 13 * 60; $row++) {
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
								$str = $r['teacher'] . " " . $r['number'];
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
</body>
</html>