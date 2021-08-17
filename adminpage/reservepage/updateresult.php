<?php
/*******************************************
*  updateresult.php
*  Store changes to itemfields based on ID
*  Lenn van Esveld
*  18-06-2021
*  (C) Lennghc.nl
*******************************************/

include("../../database/config.php");
include("../../database/opendb.php");
include("../../functions/alertMsg.php");

// Check and load parameters

$id = $_GET['id'];

//Sets the appointment to 1 and mark it as "Done"

$query  = "UPDATE bookings ";
$query .= "SET done = 1 ";
$query .= "WHERE id = ? ";

$preparedquery = $dbaselink->prepare($query);
$preparedquery->bind_param("i",$id);
$result = $preparedquery->execute();

if (($result===false) || ($preparedquery->errno)) {
	session_start();
	$_SESSION['message'] = alertMsg('Onverwachte fout opgetreden. Probeer het later opnieuw.', null, "danger");
	header("Location: index.php");
} else {
	session_start();
	$_SESSION['message'] = alertMsg('Afspraak succesvol beëindigd', null, "succes");
	header("Location: index.php");
}
$preparedquery->close();

include("../../database/closedb.php");
?>