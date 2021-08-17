<?php
/*******************************************
*  sqldupeuserid.php
*  Lenn van Esveld
*  14-06-2021
*  (C) Lennghc.nl
*******************************************/
include("../agendapage/database/config.php");
include("../agendapage/database/opendb.php");
include("../functions/alertMsg.php");


if(isset($_SESSION['userid'])){
	$userid = $_SESSION['userid'];
}

$query  = "SELECT userid ";
$query .= "FROM bookings ";
$query .= "WHERE userid= '$userid'";

$preparedquery = $dbaselink->prepare($query);
$preparedquery->execute();

if ($preparedquery->errno) {
	echo "Fout bij uitvoeren commando";
} else {
	$result = $preparedquery->get_result();

	if($result->num_rows >= 2) {
		session_start();
		$_SESSION['message'] = alertMsg('U heeft al 2 reserveringen gemaakt!', null, "danger");
		header("Location: ../index.php");
		
		exit;
	}
}

$preparedquery->close();


include("../agendapage/database/dbclose.php");

?>