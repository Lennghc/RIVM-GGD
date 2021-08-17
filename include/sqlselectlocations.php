<?php
/*******************************************
*  sqlselectlocations.php
*  Lenn van Esveld
*  14-06-2021
*  (C) Lennghc.nl
*******************************************/
include("../agendapage/database/config.php");
include("../agendapage/database/opendb.php");

$query  = "SELECT id, city ";
$query .= "FROM locations ";

$preparedquery = $dbaselink->prepare($query);
$preparedquery->execute();

if ($preparedquery->errno) {
	echo "Fout bij uitvoeren commando";
} else {
	$result = $preparedquery->get_result();

	if($result->num_rows === 0) {
		echo "Geen locatie's gevonden";
	} else {
        echo "<select class='form-control' id='room_select' name=\"location\">";

		while($row = $result->fetch_assoc()) {
  
            echo "<option value=\"" . $row['id'] . "\" ";
			echo ">";
            echo $row['city'];
            echo "</option>";
            
  		};
          echo "</select>";
  	}
}
$preparedquery->close();

include("../agendapage/database/dbclose.php");

?>