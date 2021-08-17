<?php
/*******************************************
*  updateresult.php
*  Nigal van Maanen, Tygo Houweling en Robbie Makkink
*  23-06-21
*  (C) 
*******************************************/

include("../../database/config.php");
include("../../database/opendb.php");
include("../../functions/encrypt.php");
include("../../functions/alertMsg.php");

//Make sure the session is running
if(!isset($_SESSION)){
    session_start();
}

//Make sure the user is loggedin and has the required role

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

//Define our vars

$id = $_POST['id'];
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$email = $_POST['email'];
$ranking = $_POST['ranking'];
$bsn = $_POST['bsn'];
$birthDate = $_POST['birthDate'];
$password = $_POST['password'];

//Check for spaces
$pattern = ' ';

$firstName = str_replace($pattern, '', $firstName);
$lastName = str_replace($pattern, '', $lastName);
$email = str_replace($pattern, '', $email);
$ranking = str_replace($pattern, '', $ranking);
$bsn = str_replace($pattern, '', $bsn);
$birthDate = str_replace($pattern, '', $birthDate);
$password = str_replace($pattern, '!', $password);

if( ($id == "") || ($firstName == "") || ($lastName == "") || ($email == "") || ($ranking == "") || ($bsn == "") || ($birthDate == "") || (strpos($password, '!') !== false) ){
    $_SESSION['message'] = alertMsg('Formulier gegevens mogen geen spaties bevatten. Probeer nogmaals', null, "danger");
    header("Location: updateform.php?id=" . $id);
    exit;
}

//Make sure our names start with a capital start letter and our email is all small caps
$firstName = ucfirst( strtolower($firstName) );
$lastName = ucfirst( strtolower($lastName) );
$email = strtolower($email);

//Check the bsn if its right
$pattern = "/^[0-9]{9}$/";
$result  = preg_match($pattern,$bsn); 

if(!$result){
    $_SESSION['message'] = alertMsg('Het burgerservicenummer (BSN) is niet goed ingevuld. Probeer nogmaals', null, "danger");
    header("Location: updateform.php?id=" . $id);
    exit;
}

$pattern = "/^[0-9A-Za-z\.]+@[0-9A-Za-z\.]+\.[A-Za-z]{2,3}$/";
$result  = preg_match($pattern, $email);

if(!$result){
    $_SESSION['message'] = alertMsg('Geen geldige email meegegeven. Probeer nogmaals', null, "danger");
    header("Location: updateform.php?id=" . $id);
    exit;
}


if( !empty($password) ) {
    $password = encrypt($_POST['password']);
}

$query = "UPDATE users ";
$query .= "SET firstname = ?, ";
$query .= "lastname = ?, ";
$query .= "email = ?, ";
if( !empty($password) ) {
    $query .= "passwords = ?, ";
}
$query .= "bsn = ?, ";
$query .= "birthdate = ?, ";
$query .= "ranking = ? ";
$query .= "WHERE id = ? ";

$preparedquery = $dbaselink->prepare($query);

if ( !empty($password) ) {
    $preparedquery->bind_param("ssssisii", $firstName, $lastName, $email, $password, $bsn, $birthDate, $ranking, $id);
} else {
    $preparedquery->bind_param("sssisii", $firstName, $lastName, $email, $bsn, $birthDate, $ranking, $id);
}

$result = $preparedquery->execute();
 
if (($result===false) || ($preparedquery->errno)) {

    $_SESSION['message'] = alertMsg('Gebruiker kon niet worden bewerkt. Probeer nogmaals', null, "danger");
    $preparedquery->close();
    include("../../database/closedb.php");
    header("Location: updateform.php?id=" . $id);
    exit;

} else {
    $_SESSION['message'] = alertMsg('Gebruiker is gewijziged', null, "success");
    $preparedquery->close();
    include("../../database/closedb.php");
    header("Location: index.php");
    exit;
}
$preparedquery->close();
 
include("../../database/closedb.php");

?>