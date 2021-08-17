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
$city = $_POST['city'];
$adres = $_POST['street'];
$zipcode = $_POST['zipcode'];
$number = $_POST['number'];

//Check for spaces
$pattern = ' ';
$city = str_replace($pattern, '', $city);
$adres = str_replace($pattern, '', $adres);
$zipcode = str_replace($pattern, '', $zipcode);
$number = str_replace($pattern, '', $number);

if( ($city == "") || ($adres == "") || ($zipcode == "") || ($number == "") ){
    $_SESSION['message'] = alertMsg('Formulier niet volledig ingevuld.', null, "danger");
    header("Location: addform.php");
    exit;
}

//Make sure our zipcode is in uppercase
//And that the city or adres starts with uppercase letter

$city = ucfirst( strtolower($city) );
$adres = ucfirst( strtolower($adres) );
$zipcode = strtoupper($zipcode);

//Check the zipcode against our preg

$pattern = "/^[1-9][0-9]{3} ?[A-Za-z]{2}$/";
$result = preg_match($pattern, $zipcode);

if (!$result){
    $_SESSION['message'] = alertMsg('Ongeldige postcode!', null, "danger");
    header("Location: addform.php");
    exit;
}

$pattern="/ /";
$zipcode = preg_replace($pattern,"",$zipcode);

$query = "UPDATE locations ";
$query .= "SET city = ?, ";
$query .= "adres = ?, ";
$query .= "zipcode = ?, ";
$query .= "houseNumber = ? ";
$query .= "WHERE id = ? ";


$preparedquery = $dbaselink->prepare($query);
$preparedquery->bind_param("sssii", $city, $adres,$zipcode,$number, $id);
$result = $preparedquery->execute();
 
if (($result===false) || ($preparedquery->errno)) {
    $_SESSION['message'] = alertMsg('Locatie kon niet worden bewerkt, probeer het later nog een keer.', null, "danger");
    $preparedquery->close();
    include("../../database/closedb.php");
    header("Location: updateform.php");
    
    exit;
} else {
    $_SESSION['message'] = alertMsg('Locatie is bewerkt', null, "success");
    $preparedquery->close();
    header("Location: index.php");
    exit;
}
$preparedquery->close();
 
include("../../database/closedb.php");



 
?>