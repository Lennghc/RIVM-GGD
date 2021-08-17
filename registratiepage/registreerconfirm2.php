<?php

// creator: Tygo Houweling
// date: 13-5-2021
include("../database/config.php");
include("../database/opendb.php");
include("../functions/alertMsg.php");

if(!isset($_SESSION)){
    session_start();
}

//Redirect user if already loggedin
if( isset($_SESSION['userid']) ){
    header("Location: /index.php");
    exit;
}

$bsn = $_POST['bsn'];
$id = $_POST['id'];
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$birthdate = $_POST['birthdate'];


$pattern="/^[0-9]{9}$/";
$result = preg_match($pattern,$bsn); 

if(!$result){
    $_SESSION['message'] = alertMsg('Uw burgerservicenummer (BSN) is niet goed ingevuld. Probeer nogmaals', null, "danger");
    header("Location: registreer2.php?id=".$id);
    exit;
}


include("../include/sqlselectbsn.php");

$query = "UPDATE users ";
$query .= "SET firstname = ?, lastname = ?, bsn = ?, birthdate = ? ";
$query .= "WHERE id = ? ";


$preparedquary = $dbaselink->prepare($query);
$preparedquary->bind_param("ssisi", $firstname, $lastname, $bsn, $birthdate, $id);
$result = $preparedquary->execute();

if (($result===false) || ($preparedquary->errno)){
    echo "Oops, fout";
} else {
    $_SESSION['message'] = alertMsg('Ga nu door met inloggen, om uw account te gebruiken', null, "success");
    $preparedquary->close();
    header("Location: /loginpage/login.php");
    exit;
}

$preparedquary->close();

mysqli_commit($dbaselink);

include("../database/closedb.php");

?>