<?php
/*******************************************
*  sqlrankinguser.php
*  Robbie Makkink
*  23-06-21
*  (C) 
*******************************************/

include("../../database/config.php");
include("../../database/opendb.php");

if(!isset($_SESSION)){
    session_start();
}

if( !isset($_SESSION['userid']) ){

    $_SESSION['message'] = alertMsg('U moet ingelogd zijn om deze functie te gebruiken!!', null, "danger");
    header("Location: /index.php");
    exit;
} else {
    if( !((isset($_SESSION['ranking'])) && ($_SESSION['ranking'] >= 2)) ){
        $_SESSION['message'] = alertMsg('U heeft niet de juiste role', null, "danger");
        header("Location: /index.php");
        exit;
    }
}

$query  = "SELECT id, name ";
$query .= "FROM rank ";

$preparedquery = $dbaselink->prepare($query);
$preparedquery->execute();

if ($preparedquery->errno) {
    //echo "Fout bij uitvoeren commando";
} else {
    $result = $preparedquery->get_result();

    if($result->num_rows === 0) {
        echo "Geen ranking gevonden";
    } else {
        
        while($row = $result->fetch_assoc()) {		

            if ( isset($ranking) && ($ranking == $row['id']) ){
                echo "<option selected value=" . $row['id'] . ">" . $row['name'] . "</option>";
            } else {
                echo "<option value=" . $row['id'] . ">" . $row['name'] . "</option>";
            }
            
        };
    }
}

?>