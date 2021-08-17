<?php
/*******************************************
*  sqlselectvaccination.php
*  Lenn van Esveld & Robbie Makkink
*  14-06-2021
*  (C) Lennghc.nl
*******************************************/
include("../agendapage/database/config.php");
include("../agendapage/database/opendb.php");

$query = "SELECT vaccinations.id, vaccinations.productname, vaccinations.manufacturer ";
$query .= "FROM bookings, vaccinations ";
$query .= "WHERE bookings.userID = ? ";
$query .= "AND vaccinations.id = bookings.vaccination ";

$preparedquery = $dbaselink->prepare($query);
$preparedquery->bind_param("i", $_SESSION['userid']);
$preparedquery->execute();

if ($preparedquery->errno) {
	echo "Fout bij uitvoeren commando";
} else {
	$result = $preparedquery->get_result();

	if($result->num_rows === 0) {
		$result = null;
		
		$preparedquery->close();
		
		$query = "SELECT id, manufacturer ";
		$query .= "FROM vaccinations ";

		$preparedquery = $dbaselink->prepare($query);
		$preparedquery->execute();

		if ($preparedquery->errno) {
			echo "Fout bij uitvoeren commando";
		} else {
			$result = $preparedquery->get_result();
		
			if($result->num_rows > 0)
			{
				echo "<select class='form-control' name=\"vaccination\">";

					while($row = $result->fetch_assoc())
					{
			
						echo "<option value=\"" . $row['id'] . "\" ";
						echo ">";
						echo $row['manufacturer'];
						echo "</option>";
						
					};
				echo "</select>";
			}
		}

	} else {
        echo "<select class='form-control' name=\"vaccination\">";

		while($row = $result->fetch_assoc()) {
  
            echo "<option value=\"" . $row['id'] . "\" ";
			echo ">";
            echo $row['manufacturer'];
            echo "</option>";
            
  		};
          echo "</select>";
  	}
}
$preparedquery->close();

include("../agendapage/database/dbclose.php");

?>