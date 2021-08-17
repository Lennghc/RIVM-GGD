<?php

/* 
10-06-2021
(C) Tygo Houweling
*/
include("../../database/config.php");
include("../../database/opendb.php");
include("../../functions/alertMsg.php");

//Make sure the session is running
if(!isset($_SESSION)){
    session_start();
}

if( !isset($_SESSION['userid']) ){

    $_SESSION['message'] = alertMsg('U moet ingelogd zijn om deze functie te gebruiken!', null, "danger");
    header("Location: /index.php");
    exit;
} else {
    if( !((isset($_SESSION['ranking'])) && ($_SESSION['ranking'] == 3)) ){
        $_SESSION['message'] = alertMsg('Alleen administratoren mogen dit doen.', null, "danger");
        header("Location: /adminpage");
        exit;
    }
}

if ( (empty($_GET['id'])) || !(isset($_GET['id'])) || !(is_numeric($_GET['id'])) ){
    $_SESSION['message'] = alertMsg('Error: Geen id meegegeven!', null, "danger");
    header("Location: index.php");
    exit;
} else {
    $id = $_GET['id'];
}

$query = "DELETE FROM vaccinations ";
$query .= "WHERE id = ? ";
$query .= "LIMIT 1 ";

$preparedquery = $dbaselink->prepare($query);
$preparedquery->bind_param("i",$id);
$result = $preparedquery->execute();

if (($result===false) || ($preparedquery->errno)) {
    $_SESSION['message'] = alertMsg('Een fout is opgetreden bij het verwijderen.', null, "success");
    $preparedquery->close();
    include("../../database/closedb.php");
    header("Location: index.php");
    exit;
} else {
    $_SESSION['message'] = alertMsg('Vaccinatie is verwijderd', null, "success");
    $preparedquery->close();
    include("../../database/closedb.php");
    header("Location: index.php");
    exit;
}

?>
