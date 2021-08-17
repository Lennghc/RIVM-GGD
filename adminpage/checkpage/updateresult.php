<?php
/*******************************************
* updateresult.php
* Store changes to itemfields based on ID
*  Lenn van Esveld
*  18-06-2021
*  (C) Lennghc.nl
*******************************************/

include("../../database/config.php");
include("../../database/opendb.php");
include("../../functions/alertMsg.php");

if(!isset($_SESSION)){
    session_start();
}

if( !isset($_SESSION['userid']) ){

    $_SESSION['message'] = alertMsg('U moet ingelogd zijn om deze functie te gebruiken!!', null, "danger");
    header("Location: /index.php");
    exit;
} else {
    if( !((isset($_SESSION['ranking'])) && ($_SESSION['ranking'] == 3)) ){
        $_SESSION['message'] = alertMsg('Alleen administratoren mogen dit doen.', null, "danger");
        header("Location: /adminpage");
        exit;
    }
}

// Check and load parameters

if ( (empty($_GET['id'])) || !(isset($_GET['id'])) || !(is_numeric($_GET['id'])) ){
    $_SESSION['message'] = alertMsg('Error: Geen id meegegeven!', null, "danger");
    header("Location: ../index.php");
    exit;
} else {
    $id = $_GET['id'];
}

$query  = "UPDATE bookings ";
$query .= "SET done = ? ";
$query .= "WHERE id = ? ";

$done = 1;

$preparedquery = $dbaselink->prepare($query);
$preparedquery->bind_param("ii",$done, $id);
$result = $preparedquery->execute();

if (($result===false) || ($preparedquery->errno)) {
	echo "Fout bij uitvoeren commando";
} else {
	$_SESSION['message'] = alertMsg('Successvol beindingd', null, "success");
	$preparedquery->close();
    header("Location: ../index.php");
    exit;
}
$preparedquery->close();

include("../../database/closedb.php");


echo "</body>";
echo "</html>";
?>