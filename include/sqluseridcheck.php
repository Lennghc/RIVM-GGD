<?php
include("../agendapage/database/config.php");
include("../agendapage/database/opendb.php");

$query  = "SELECT userid";
$query .= "FROM bookings ";

$preparedquery = $dbaselink->prepare($query);
$preparedquery->execute();

if ($preparedquery->errno) {
	echo "Fout bij uitvoeren commando";
} else {
	$result = $preparedquery->get_result();

	if($result->num_rows === 0) {
		echo "Geen userid gevonden";
	} else {
		while($row = $result->fetch_assoc()) {
			echo "gaat u door";
            
  		}
  	}
}
$preparedquery->close();

include("../agendapage/database/dbclose.php");

?>